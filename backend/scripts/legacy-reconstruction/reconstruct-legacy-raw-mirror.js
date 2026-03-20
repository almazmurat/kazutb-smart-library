const fs = require("node:fs");
const path = require("node:path");
const readline = require("node:readline");
const crypto = require("node:crypto");
const { spawnSync } = require("node:child_process");

const dotenv = require("dotenv");
const { Client } = require("pg");

dotenv.config({ path: path.resolve(__dirname, "../../.env") });

function ensureDir(dirPath) {
  fs.mkdirSync(dirPath, { recursive: true });
}

function loadJsonFromPwsh(scriptPath, args) {
  const result = spawnSync("pwsh", ["-File", scriptPath, ...args], {
    encoding: "utf8",
    maxBuffer: 1024 * 1024 * 200,
  });

  if (result.status !== 0) {
    throw new Error(
      result.stderr || result.stdout || `PowerShell failed for ${scriptPath}`,
    );
  }

  const output = result.stdout.trim();
  if (!output) {
    return [];
  }
  return JSON.parse(output);
}

function normalizeTableName(tableName) {
  return String(tableName || "")
    .trim()
    .toLowerCase();
}

function sanitizeIdentifier(sourceName) {
  const normalized = sourceName
    .toLowerCase()
    .replace(/[^a-z0-9_]+/g, "_")
    .replace(/^_+|_+$/g, "");

  const prefixed =
    normalized.length > 0 ? `legacy_${normalized}` : "legacy_table";
  if (prefixed.length <= 63) {
    return prefixed;
  }

  const hash = crypto
    .createHash("sha1")
    .update(sourceName)
    .digest("hex")
    .slice(0, 8);
  return `${prefixed.slice(0, 54)}_${hash}`;
}

function stableJsonString(value) {
  return JSON.stringify(value);
}

function rowHash(tableName, row) {
  return crypto
    .createHash("sha1")
    .update(`${tableName}|${stableJsonString(row)}`)
    .digest("hex");
}

async function readNdjson(filePath) {
  if (!fs.existsSync(filePath)) {
    return [];
  }

  const rows = [];
  const rl = readline.createInterface({
    input: fs.createReadStream(filePath, { encoding: "utf8" }),
    crlfDelay: Infinity,
  });

  for await (const line of rl) {
    const trimmed = line.trim();
    if (!trimmed) {
      continue;
    }
    rows.push(JSON.parse(trimmed));
  }

  return rows;
}

async function batchUpsert(client, sql, rows, chunkSize = 250) {
  for (let offset = 0; offset < rows.length; offset += chunkSize) {
    const chunk = rows.slice(offset, offset + chunkSize);
    const values = [];
    const tuples = chunk.map((row, rowIndex) => {
      const placeholders = row.map(
        (_, colIndex) => `$${rowIndex * row.length + colIndex + 1}`,
      );
      values.push(...row);
      return `(${placeholders.join(", ")})`;
    });

    await client.query(sql(tuples.join(",\n")), values);
  }
}

async function getRawTables(client) {
  const result = await client.query(
    `SELECT table_name
     FROM information_schema.tables
     WHERE table_schema = 'raw' AND table_type = 'BASE TABLE'
     ORDER BY table_name`,
  );
  return result.rows.map((row) => row.table_name);
}

async function ensureMirrorTable(client, mirrorTableName) {
  await client.query(`
    CREATE TABLE IF NOT EXISTS raw.${mirrorTableName} (
      source_row_hash text PRIMARY KEY,
      source_table text NOT NULL,
      source_payload jsonb NOT NULL,
      source_imported_at timestamptz NOT NULL DEFAULT now(),
      mirror_batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE
    )
  `);
  await client.query(
    `CREATE INDEX IF NOT EXISTS idx_${mirrorTableName}_source_table ON raw.${mirrorTableName}(source_table)`,
  );
}

async function ensureMirrorReportTable(client) {
  await client.query(`
    CREATE TABLE IF NOT EXISTS migration.raw_mirror_reports (
      id uuid PRIMARY KEY,
      batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
      source_table text NOT NULL,
      target_table text,
      source_row_count bigint NOT NULL,
      imported_row_count bigint NOT NULL,
      status text NOT NULL,
      note text,
      created_at timestamptz NOT NULL DEFAULT now()
    )
  `);
}

function asArray(value) {
  if (Array.isArray(value)) {
    return value;
  }
  if (!value) {
    return [];
  }
  return [value];
}

async function main() {
  const batchId = crypto.randomUUID();
  const timestamp = new Date().toISOString().replace(/[:.]/g, "-");
  const outputRoot = path.resolve(
    __dirname,
    "../../../migration/raw/legacy-full-mirror",
    timestamp,
  );
  const logsRoot = path.resolve(__dirname, "../../../migration/logs");
  ensureDir(outputRoot);
  ensureDir(logsRoot);

  const listScript = path.resolve(__dirname, "list-legacy-tables.ps1");
  const exportScript = path.resolve(__dirname, "export-legacy-tables.ps1");

  const sourceTables = asArray(
    loadJsonFromPwsh(listScript, [
      "-Instance",
      process.env.LEGACY_SQLSERVER_INSTANCE || "localhost\\SQLEXPRESS",
      "-Database",
      process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
    ]),
  )
    .map((row) => ({
      table: String(row.table),
      rowCount: Number(row.rowCount || 0),
    }))
    .sort((a, b) => a.table.localeCompare(b.table));

  const client = new Client({ connectionString: process.env.DATABASE_URL });
  await client.connect();

  const intentionallySkipped = [];
  const newlyMirrored = [];
  const importedCounts = [];

  try {
    await client.query("BEGIN");
    await client.query(
      `INSERT INTO migration.import_batches (id, source_system, source_database, status, notes, metrics)
       VALUES ($1, $2, $3, $4, $5, $6)`,
      [
        batchId,
        "sqlserver-raw-mirror",
        process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
        "IN_PROGRESS",
        "Second-pass complete raw mirror of SQL Server tables into PostgreSQL raw schema",
        {},
      ],
    );
    await ensureMirrorReportTable(client);
    await client.query("COMMIT");
  } catch (error) {
    await client.query("ROLLBACK");
    await client.end();
    throw error;
  }

  try {
    const rawTables = await getRawTables(client);
    const rawTableSet = new Set(
      rawTables.map((name) => normalizeTableName(name)),
    );
    const sourceTableSet = new Set(
      sourceTables.map((row) => normalizeTableName(row.table)),
    );

    const alreadyMirrored = sourceTables.filter((row) =>
      rawTableSet.has(normalizeTableName(row.table)),
    );
    const missingSourceTables = sourceTables.filter(
      (row) => !rawTableSet.has(normalizeTableName(row.table)),
    );
    const emptyInSource = sourceTables.filter((row) => row.rowCount === 0);

    const tablesToMirror = missingSourceTables.filter(
      (row) => normalizeTableName(row.table) !== "sysdiagrams",
    );
    if (
      missingSourceTables.some(
        (row) => normalizeTableName(row.table) === "sysdiagrams",
      )
    ) {
      intentionallySkipped.push({
        table: "sysdiagrams",
        reason:
          "legacy SQL Server diagram metadata table is not useful for business data mirror",
      });
    }

    const tablesCsv = tablesToMirror.map((row) => row.table).join(",");
    if (tablesCsv) {
      const exportResult = spawnSync(
        "pwsh",
        [
          "-File",
          exportScript,
          "-Instance",
          process.env.LEGACY_SQLSERVER_INSTANCE || "localhost\\SQLEXPRESS",
          "-Database",
          process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
          "-OutputDir",
          outputRoot,
          "-Tables",
          tablesCsv,
        ],
        {
          encoding: "utf8",
          maxBuffer: 1024 * 1024 * 500,
        },
      );

      if (exportResult.status !== 0) {
        throw new Error(
          exportResult.stderr ||
            exportResult.stdout ||
            "raw mirror export failed",
        );
      }
    }

    for (const tableInfo of tablesToMirror) {
      const sourceName = tableInfo.table;
      const sourceRowCount = Number(tableInfo.rowCount || 0);
      const targetName = sanitizeIdentifier(sourceName);
      const ndjsonPath = path.join(
        outputRoot,
        `${sourceName.toLowerCase()}.ndjson`,
      );
      const rows = await readNdjson(ndjsonPath);

      await client.query("BEGIN");
      try {
        await ensureMirrorTable(client, targetName);

        const uniqueByHash = new Map();
        for (const row of rows) {
          const hash = rowHash(sourceName, row);
          if (!uniqueByHash.has(hash)) {
            uniqueByHash.set(hash, [hash, sourceName, row, batchId]);
          }
        }
        const values = Array.from(uniqueByHash.values());

        if (values.length > 0) {
          await batchUpsert(
            client,
            (tupleSql) => `
              INSERT INTO raw.${targetName} (source_row_hash, source_table, source_payload, mirror_batch_id)
              VALUES ${tupleSql}
              ON CONFLICT (source_row_hash) DO UPDATE
              SET source_payload = EXCLUDED.source_payload,
                  mirror_batch_id = EXCLUDED.mirror_batch_id,
                  source_imported_at = now()
            `,
            values,
          );
        }

        await client.query(
          `INSERT INTO migration.raw_mirror_reports (id, batch_id, source_table, target_table, source_row_count, imported_row_count, status, note)
           VALUES ($1, $2, $3, $4, $5, $6, $7, $8)`,
          [
            crypto.randomUUID(),
            batchId,
            sourceName,
            `raw.${targetName}`,
            sourceRowCount,
            values.length,
            values.length === sourceRowCount ? "IMPORTED" : "PARTIAL",
            values.length === sourceRowCount
              ? null
              : "imported unique row count differs from SQL Server inventory count",
          ],
        );

        await client.query(
          `INSERT INTO migration.import_logs (id, batch_id, stage, source_table, level, message, row_count, details)
           VALUES ($1, $2, $3, $4, $5, $6, $7, $8)`,
          [
            crypto.randomUUID(),
            batchId,
            "raw-mirror",
            sourceName,
            "INFO",
            `Mirrored ${sourceName} into raw.${targetName}`,
            values.length,
            { targetTable: `raw.${targetName}` },
          ],
        );

        await client.query("COMMIT");
      } catch (error) {
        await client.query("ROLLBACK");
        await client.query(
          `INSERT INTO migration.raw_mirror_reports (id, batch_id, source_table, target_table, source_row_count, imported_row_count, status, note)
           VALUES ($1, $2, $3, $4, $5, $6, $7, $8)`,
          [
            crypto.randomUUID(),
            batchId,
            sourceName,
            `raw.${targetName}`,
            sourceRowCount,
            0,
            "FAILED",
            error.message,
          ],
        );
        throw error;
      }

      newlyMirrored.push(`raw.${targetName}`);
      importedCounts.push({
        sourceTable: sourceName,
        targetTable: `raw.${targetName}`,
        sourceRowCount,
        importedRowCount: values.length,
      });
    }

    const emptyMissing = missingSourceTables.filter(
      (row) => row.rowCount === 0,
    );
    for (const item of emptyMissing) {
      if (normalizeTableName(item.table) === "sysdiagrams") {
        continue;
      }
      intentionallySkipped.push({
        table: item.table,
        reason: "source table is empty",
      });
    }

    const summary = {
      batchId,
      sourceDatabase: process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
      generatedAt: new Date().toISOString(),
      fullSourceTableList: sourceTables,
      alreadyMirroredRawTables: alreadyMirrored.map(
        (row) => `raw.${normalizeTableName(row.table)}`,
      ),
      newlyMirroredRawTables: newlyMirrored,
      sourceTablesStillSkipped: intentionallySkipped,
      countsPerNewlyImportedRawTable: importedCounts,
      rerunSafety:
        "safe to rerun: existing parsed/core/review logic is untouched; raw mirror uses upsert by source_row_hash and does not truncate existing reconstruction tables",
      coverage: {
        totalSourceTables: sourceTables.length,
        alreadyMirroredCount: alreadyMirrored.length,
        missingBeforeThisPassCount: missingSourceTables.length,
        emptyInSourceCount: emptyInSource.length,
        sourceTablesNotRepresentedInRawAfterPass: sourceTables
          .filter((row) => {
            const source = normalizeTableName(row.table);
            if (source === "sysdiagrams") {
              return false;
            }
            if (rawTableSet.has(source)) {
              return false;
            }
            const mirroredName = sanitizeIdentifier(row.table);
            return !newlyMirrored.includes(`raw.${mirroredName}`);
          })
          .map((row) => row.table),
      },
      outputRoot,
    };

    const summaryPath = path.resolve(
      logsRoot,
      `legacy-raw-mirror-summary-${batchId}.json`,
    );
    fs.writeFileSync(summaryPath, JSON.stringify(summary, null, 2));

    await client.query(
      `UPDATE migration.import_batches
       SET status = $2, completed_at = now(), metrics = $3
       WHERE id = $1`,
      [batchId, "COMPLETED", summary],
    );

    console.log(JSON.stringify(summary, null, 2));
  } catch (error) {
    await client
      .query(
        `UPDATE migration.import_batches
       SET status = $2, completed_at = now(), notes = $3, metrics = $4
       WHERE id = $1`,
        [batchId, "FAILED", error.message, { stack: error.stack }],
      )
      .catch(() => undefined);
    throw error;
  } finally {
    await client.end();
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
