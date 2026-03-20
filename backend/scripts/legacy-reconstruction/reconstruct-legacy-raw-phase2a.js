const fs = require("node:fs");
const path = require("node:path");
const readline = require("node:readline");
const crypto = require("node:crypto");
const { spawnSync } = require("node:child_process");

const dotenv = require("dotenv");
const { Client } = require("pg");

dotenv.config({ path: path.resolve(__dirname, "../../.env") });

const PHASE2A_TABLES = [
  "DBRES",
  "DOCXSUPPORT",
  "OLDDOC",
  "DOC_EMBEDDINGS",
  "DOC_EDIT",
  "TAG",
  "WEBHISTORY",
  "TYPE_LIT",
  "STATES",
  "SERVICE_TYPE",
];

function ensureDir(dirPath) {
  fs.mkdirSync(dirPath, { recursive: true });
}

function sanitizeIdentifier(sourceName) {
  const normalized = String(sourceName || "")
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
    .update(String(sourceName))
    .digest("hex")
    .slice(0, 8);
  return `${prefixed.slice(0, 54)}_${hash}`;
}

function rowHash(tableName, row) {
  return crypto
    .createHash("sha1")
    .update(`${tableName}|${JSON.stringify(row)}`)
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

async function batchUpsert(client, targetTable, values, chunkSize = 250) {
  if (values.length === 0) {
    return;
  }

  for (let offset = 0; offset < values.length; offset += chunkSize) {
    const chunk = values.slice(offset, offset + chunkSize);
    const params = [];
    const tuples = chunk.map((row, rowIndex) => {
      const placeholders = row.map(
        (_, colIndex) => `$${rowIndex * row.length + colIndex + 1}`,
      );
      params.push(...row);
      return `(${placeholders.join(", ")})`;
    });

    await client.query(
      `
      INSERT INTO raw.${targetTable} (source_row_hash, source_table, source_payload, source_imported_at, mirror_run_id)
      VALUES ${tuples.join(",\n")}
      ON CONFLICT (source_row_hash) DO UPDATE
      SET source_payload = EXCLUDED.source_payload,
          source_imported_at = EXCLUDED.source_imported_at,
          mirror_run_id = EXCLUDED.mirror_run_id
      `,
      params,
    );
  }
}

async function ensureTargetTable(client, targetTable) {
  await client.query(`
    CREATE TABLE IF NOT EXISTS raw.${targetTable} (
      source_row_hash text PRIMARY KEY,
      source_table text NOT NULL,
      source_payload jsonb NOT NULL,
      source_imported_at timestamptz NOT NULL DEFAULT now(),
      mirror_run_id text NOT NULL
    )
  `);

  await client.query(
    `CREATE INDEX IF NOT EXISTS idx_${targetTable}_source_table ON raw.${targetTable}(source_table)`,
  );
}

function runExport(exportScript, outputDir, tables) {
  const result = spawnSync(
    "pwsh",
    [
      "-File",
      exportScript,
      "-Instance",
      process.env.LEGACY_SQLSERVER_INSTANCE || "localhost\\SQLEXPRESS",
      "-Database",
      process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
      "-OutputDir",
      outputDir,
      "-Tables",
      tables.join(","),
    ],
    {
      encoding: "utf8",
      maxBuffer: 1024 * 1024 * 300,
    },
  );

  if (result.status !== 0) {
    throw new Error(result.stderr || result.stdout || "SQL export failed");
  }
}

async function main() {
  const runId = `phase2a-${new Date().toISOString().replace(/[:.]/g, "-")}`;
  const outputRoot = path.resolve(
    __dirname,
    "../../../migration/raw/legacy-phase2a",
    runId,
  );
  const logsRoot = path.resolve(__dirname, "../../../migration/logs");
  ensureDir(outputRoot);
  ensureDir(logsRoot);

  const exportScript = path.resolve(__dirname, "export-legacy-tables.ps1");
  runExport(exportScript, outputRoot, PHASE2A_TABLES);

  const client = new Client({ connectionString: process.env.DATABASE_URL });
  await client.connect();

  const results = [];
  const failures = [];

  try {
    for (const sourceTable of PHASE2A_TABLES) {
      const targetTable = sanitizeIdentifier(sourceTable);
      const ndjsonPath = path.join(
        outputRoot,
        `${sourceTable.toLowerCase()}.ndjson`,
      );

      try {
        const rows = await readNdjson(ndjsonPath);
        const uniqueByHash = new Map();
        for (const row of rows) {
          const hash = rowHash(sourceTable, row);
          if (!uniqueByHash.has(hash)) {
            uniqueByHash.set(hash, [
              hash,
              sourceTable,
              row,
              new Date().toISOString(),
              runId,
            ]);
          }
        }

        await ensureTargetTable(client, targetTable);
        await batchUpsert(
          client,
          targetTable,
          Array.from(uniqueByHash.values()),
        );

        const currentCount = await client.query(
          `SELECT count(*)::bigint AS count FROM raw.${targetTable}`,
        );

        const rowResult = {
          sourceTable,
          targetTable: `raw.${targetTable}`,
          sourceRowsRead: rows.length,
          uniqueRowsProcessed: uniqueByHash.size,
          totalRowsInTargetAfterRun: Number(currentCount.rows[0].count),
          status: "IMPORTED",
        };

        results.push(rowResult);
        console.log(
          `[PHASE2A] ${sourceTable} -> raw.${targetTable}: processed ${rowResult.uniqueRowsProcessed}`,
        );
      } catch (error) {
        failures.push({
          sourceTable,
          targetTable: `raw.${targetTable}`,
          status: "FAILED",
          message: error.message,
        });
        console.error(`[PHASE2A] FAILED ${sourceTable}: ${error.message}`);
      }
    }
  } finally {
    await client.end();
  }

  const summary = {
    runId,
    sourceDatabase: process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
    sourceInstance:
      process.env.LEGACY_SQLSERVER_INSTANCE || "localhost\\SQLEXPRESS",
    requestedTables: PHASE2A_TABLES,
    newlyIngestedRawTables: results.map((row) => row.targetTable),
    perTableCounts: results,
    failedTables: failures,
    rerunCommand:
      "node backend/scripts/legacy-reconstruction/reconstruct-legacy-raw-phase2a.js",
    outputRoot,
    generatedAt: new Date().toISOString(),
  };

  const summaryPath = path.resolve(
    logsRoot,
    `legacy-phase2a-summary-${runId}.json`,
  );
  fs.writeFileSync(summaryPath, JSON.stringify(summary, null, 2));
  console.log(JSON.stringify(summary, null, 2));
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
