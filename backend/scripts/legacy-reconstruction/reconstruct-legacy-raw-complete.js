const fs = require("node:fs");
const path = require("node:path");
const readline = require("node:readline");
const crypto = require("node:crypto");
const { spawnSync } = require("node:child_process");

const dotenv = require("dotenv");
const { Client } = require("pg");

dotenv.config({ path: path.resolve(__dirname, "../../.env") });

const CHUNK_TABLES = 12;
const CHUNK_ROWS = 500;

const TECH_EXACT = new Set([
  "marcstat",
  "docidxx",
  "moidx",
  "moidxx",
  "metaidx",
  "sysdiagrams",
]);

function ensureDir(dirPath) {
  fs.mkdirSync(dirPath, { recursive: true });
}

function normalizeName(name) {
  return String(name || "")
    .trim()
    .toLowerCase();
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

function classifyTechnical(tableName) {
  const n = normalizeName(tableName);
  if (TECH_EXACT.has(n)) {
    return true;
  }
  if (/^idx/.test(n)) {
    return true;
  }
  if (/idx/.test(n) && /(x$|xx$|_x$|_xx$)/.test(n)) {
    return true;
  }
  if (/^[a-z0-9_]*x$/.test(n) && /idx|support|dict|stat/.test(n)) {
    return true;
  }
  return false;
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

function chunkArray(items, size) {
  const chunks = [];
  for (let i = 0; i < items.length; i += size) {
    chunks.push(items.slice(i, i + size));
  }
  return chunks;
}

function runPwshJson(scriptPath, args) {
  const result = spawnSync("pwsh", ["-File", scriptPath, ...args], {
    encoding: "utf8",
    maxBuffer: 1024 * 1024 * 300,
  });

  if (result.status !== 0) {
    throw new Error(
      result.stderr || result.stdout || `PowerShell failed: ${scriptPath}`,
    );
  }

  const out = result.stdout.trim();
  return out ? JSON.parse(out) : [];
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
      maxBuffer: 1024 * 1024 * 500,
    },
  );

  if (result.status !== 0) {
    throw new Error(result.stderr || result.stdout || "SQL export failed");
  }
}

async function listRawTables(client) {
  const result = await client.query(
    `SELECT table_name
     FROM information_schema.tables
     WHERE table_schema='raw' AND table_type='BASE TABLE'
     ORDER BY table_name`,
  );
  return result.rows.map((row) => row.table_name);
}

async function ensureRawMirrorTable(client, targetTable) {
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

async function flushChunk(client, targetTable, rows) {
  if (rows.length === 0) {
    return;
  }

  const params = [];
  const tuples = rows.map((row, rowIndex) => {
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

async function ingestTableFromNdjson(
  client,
  sourceTable,
  targetTable,
  ndjsonPath,
  runId,
) {
  if (!fs.existsSync(ndjsonPath)) {
    throw new Error(`Missing export file: ${ndjsonPath}`);
  }

  await ensureRawMirrorTable(client, targetTable);

  let sourceRowsRead = 0;
  let uniqueRowsProcessed = 0;
  let chunkRows = [];
  let chunkHashes = new Set();
  const nowIso = new Date().toISOString();

  const rl = readline.createInterface({
    input: fs.createReadStream(ndjsonPath, { encoding: "utf8" }),
    crlfDelay: Infinity,
  });

  for await (const line of rl) {
    const trimmed = line.trim();
    if (!trimmed) {
      continue;
    }

    sourceRowsRead += 1;
    const row = JSON.parse(trimmed);
    const hash = rowHash(sourceTable, row);
    if (chunkHashes.has(hash)) {
      continue;
    }

    chunkHashes.add(hash);
    chunkRows.push([hash, sourceTable, row, nowIso, runId]);
    uniqueRowsProcessed += 1;

    if (chunkRows.length >= CHUNK_ROWS) {
      await flushChunk(client, targetTable, chunkRows);
      chunkRows = [];
      chunkHashes = new Set();
    }
  }

  if (chunkRows.length > 0) {
    await flushChunk(client, targetTable, chunkRows);
  }

  const countResult = await client.query(
    `SELECT count(*)::bigint AS count FROM raw.${targetTable}`,
  );
  return {
    sourceRowsRead,
    uniqueRowsProcessed,
    totalRowsInTargetAfterRun: Number(countResult.rows[0].count),
  };
}

function resolveCoverage(sourceTables, rawTables) {
  const rawSet = new Set(rawTables.map((name) => normalizeName(name)));
  const alreadyMirroredBefore = [];
  const missingNonEmptyBusiness = [];
  const missingNonEmptyTechnical = [];
  const missingEmpty = [];

  for (const row of sourceTables) {
    const source = String(row.table);
    const rowCount = Number(row.rowCount || 0);
    const lower = normalizeName(source);
    const legacyTarget = sanitizeIdentifier(source);
    const mirrored = rawSet.has(lower) || rawSet.has(legacyTarget);

    if (mirrored) {
      alreadyMirroredBefore.push(source);
      continue;
    }

    if (rowCount === 0) {
      missingEmpty.push({ table: source, rowCount });
      continue;
    }

    if (classifyTechnical(source)) {
      missingNonEmptyTechnical.push({ table: source, rowCount });
      continue;
    }

    missingNonEmptyBusiness.push({ table: source, rowCount });
  }

  missingNonEmptyBusiness.sort(
    (a, b) => b.rowCount - a.rowCount || a.table.localeCompare(b.table),
  );
  missingNonEmptyTechnical.sort(
    (a, b) => b.rowCount - a.rowCount || a.table.localeCompare(b.table),
  );
  missingEmpty.sort((a, b) => a.table.localeCompare(b.table));
  alreadyMirroredBefore.sort((a, b) => a.localeCompare(b));

  return {
    alreadyMirroredBefore,
    missingNonEmptyBusiness,
    missingNonEmptyTechnical,
    missingEmpty,
  };
}

async function runPhase(
  client,
  phaseName,
  tables,
  exportScript,
  outputRoot,
  runId,
  logCollector,
) {
  const chunks = chunkArray(tables, CHUNK_TABLES);
  const phaseResults = [];
  const phaseFailures = [];

  for (let idx = 0; idx < chunks.length; idx += 1) {
    const batch = chunks[idx];
    const batchLabel = `${phaseName} batch ${idx + 1}/${chunks.length}`;
    const names = batch.map((row) => row.table);
    const batchDir = path.join(
      outputRoot,
      `${phaseName.toLowerCase()}-batch-${idx + 1}`,
    );
    ensureDir(batchDir);

    try {
      runExport(exportScript, batchDir, names);
    } catch (error) {
      for (const tableInfo of batch) {
        phaseFailures.push({
          phase: phaseName,
          sourceTable: tableInfo.table,
          targetTable: `raw.${sanitizeIdentifier(tableInfo.table)}`,
          status: "FAILED",
          message: `${batchLabel} export failed: ${error.message}`,
        });
      }
      console.error(
        `[${phaseName}] ${batchLabel} export failed, continuing. ${error.message}`,
      );
      continue;
    }

    for (const tableInfo of batch) {
      const sourceTable = tableInfo.table;
      const targetTable = sanitizeIdentifier(sourceTable);
      const ndjsonPath = path.join(
        batchDir,
        `${sourceTable.toLowerCase()}.ndjson`,
      );

      try {
        const counts = await ingestTableFromNdjson(
          client,
          sourceTable,
          targetTable,
          ndjsonPath,
          runId,
        );
        const rowResult = {
          phase: phaseName,
          sourceTable,
          targetTable: `raw.${targetTable}`,
          sourceInventoryRows: Number(tableInfo.rowCount || 0),
          sourceRowsRead: counts.sourceRowsRead,
          uniqueRowsProcessed: counts.uniqueRowsProcessed,
          totalRowsInTargetAfterRun: counts.totalRowsInTargetAfterRun,
          status: "IMPORTED",
        };
        phaseResults.push(rowResult);
        logCollector.push(rowResult);
        console.log(
          `[${phaseName}] ${sourceTable} -> raw.${targetTable}: ${counts.uniqueRowsProcessed} rows`,
        );
      } catch (error) {
        const fail = {
          phase: phaseName,
          sourceTable,
          targetTable: `raw.${targetTable}`,
          status: "FAILED",
          message: error.message,
        };
        phaseFailures.push(fail);
        logCollector.push(fail);
        console.error(`[${phaseName}] FAILED ${sourceTable}: ${error.message}`);
      }
    }
  }

  return { phaseResults, phaseFailures };
}

async function createEmptyMirrors(client, missingEmpty, runId) {
  const created = [];
  const skipped = [];

  for (const row of missingEmpty) {
    const sourceTable = row.table;
    const targetTable = sanitizeIdentifier(sourceTable);
    try {
      await ensureRawMirrorTable(client, targetTable);
      await client.query(
        `INSERT INTO raw.${targetTable} (source_row_hash, source_table, source_payload, source_imported_at, mirror_run_id)
         VALUES ($1, $2, $3, now(), $4)
         ON CONFLICT (source_row_hash) DO NOTHING`,
        [
          `__empty_table_marker__:${sourceTable}`,
          sourceTable,
          { empty_source_table: true },
          runId,
        ],
      );
      await client.query(
        `DELETE FROM raw.${targetTable}
         WHERE source_row_hash = $1 AND mirror_run_id = $2`,
        [`__empty_table_marker__:${sourceTable}`, runId],
      );
      created.push(`raw.${targetTable}`);
    } catch (error) {
      skipped.push({
        table: sourceTable,
        reason: `empty mirror create failed: ${error.message}`,
      });
    }
  }

  return { created, skipped };
}

async function main() {
  const runId = `raw-full-${new Date().toISOString().replace(/[:.]/g, "-")}`;
  const logsRoot = path.resolve(__dirname, "../../../migration/logs");
  const outputRoot = path.resolve(
    __dirname,
    "../../../migration/raw/legacy-full-mirror",
    runId,
  );
  ensureDir(logsRoot);
  ensureDir(outputRoot);

  const listScript = path.resolve(__dirname, "list-legacy-tables.ps1");
  const exportScript = path.resolve(__dirname, "export-legacy-tables.ps1");

  const sourceTables = asArray(
    runPwshJson(listScript, [
      "-Instance",
      process.env.LEGACY_SQLSERVER_INSTANCE || "localhost\\SQLEXPRESS",
      "-Database",
      process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
    ]),
  ).map((row) => ({
    table: String(row.table),
    rowCount: Number(row.rowCount || 0),
  }));

  const client = new Client({ connectionString: process.env.DATABASE_URL });
  await client.connect();

  const events = [];
  try {
    const rawBefore = await listRawTables(client);
    const coverageBefore = resolveCoverage(sourceTables, rawBefore);

    console.log(
      `[PHASE2B] missing business tables: ${coverageBefore.missingNonEmptyBusiness.length}`,
    );
    const phase2b = await runPhase(
      client,
      "PHASE2B",
      coverageBefore.missingNonEmptyBusiness,
      exportScript,
      outputRoot,
      runId,
      events,
    );

    console.log(
      `[PHASE2C] missing technical tables: ${coverageBefore.missingNonEmptyTechnical.length}`,
    );
    const phase2c = await runPhase(
      client,
      "PHASE2C",
      coverageBefore.missingNonEmptyTechnical,
      exportScript,
      outputRoot,
      runId,
      events,
    );

    const emptyMirrors = await createEmptyMirrors(
      client,
      coverageBefore.missingEmpty,
      runId,
    );

    const rawAfter = await listRawTables(client);
    const rawAfterSet = new Set(rawAfter.map((name) => normalizeName(name)));

    const mirroredNonEmpty = sourceTables.filter((row) => {
      if (Number(row.rowCount || 0) === 0) {
        return false;
      }
      const lower = normalizeName(row.table);
      return (
        rawAfterSet.has(lower) || rawAfterSet.has(sanitizeIdentifier(row.table))
      );
    }).length;

    const mirroredTotal = sourceTables.filter((row) => {
      const lower = normalizeName(row.table);
      return (
        rawAfterSet.has(lower) || rawAfterSet.has(sanitizeIdentifier(row.table))
      );
    }).length;

    const finalUnmirrored = sourceTables
      .filter((row) => {
        const lower = normalizeName(row.table);
        return !(
          rawAfterSet.has(lower) ||
          rawAfterSet.has(sanitizeIdentifier(row.table))
        );
      })
      .map((row) => row.table)
      .sort((a, b) => a.localeCompare(b));

    const summary = {
      runId,
      generatedAt: new Date().toISOString(),
      sourceDatabase: process.env.LEGACY_SQLSERVER_DATABASE || "marc_restored",
      sourceInstance:
        process.env.LEGACY_SQLSERVER_INSTANCE || "localhost\\SQLEXPRESS",
      totals: {
        totalSourceTables: sourceTables.length,
        totalNonEmptySourceTables: sourceTables.filter(
          (row) => Number(row.rowCount || 0) > 0,
        ).length,
        totalEmptySourceTables: sourceTables.filter(
          (row) => Number(row.rowCount || 0) === 0,
        ).length,
      },
      alreadyMirroredBeforePass: coverageBefore.alreadyMirroredBefore,
      newlyMirroredInThisPass: [
        ...phase2b.phaseResults.map((row) => row.targetTable),
        ...phase2c.phaseResults.map((row) => row.targetTable),
        ...emptyMirrors.created,
      ],
      failedOrProblematicTables: [
        ...phase2b.phaseFailures,
        ...phase2c.phaseFailures,
      ],
      intentionallySkippedTables: [
        ...emptyMirrors.skipped,
        ...finalUnmirrored.map((table) => ({
          table,
          reason: "not mirrored after all phases",
        })),
      ],
      perTableCountsNewlyIngested: [
        ...phase2b.phaseResults,
        ...phase2c.phaseResults,
      ],
      finalCoverage: {
        mirroredNonEmptyTables: mirroredNonEmpty,
        totalNonEmptyTables: sourceTables.filter(
          (row) => Number(row.rowCount || 0) > 0,
        ).length,
        mirroredTotalTables: mirroredTotal,
        totalSourceTables: sourceTables.length,
      },
      remainingUnmirroredSourceTables: finalUnmirrored,
      rerunCommands: {
        fullRawMirror:
          "node backend/scripts/legacy-reconstruction/reconstruct-legacy-raw-complete.js",
        phase2aSelective:
          "node backend/scripts/legacy-reconstruction/reconstruct-legacy-raw-phase2a.js",
      },
      outputRoot,
    };

    const summaryPath = path.resolve(
      logsRoot,
      `legacy-raw-complete-summary-${runId}.json`,
    );
    fs.writeFileSync(summaryPath, JSON.stringify(summary, null, 2));

    const eventPath = path.resolve(
      logsRoot,
      `legacy-raw-complete-events-${runId}.json`,
    );
    fs.writeFileSync(eventPath, JSON.stringify(events, null, 2));

    console.log(JSON.stringify(summary, null, 2));
  } finally {
    await client.end();
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
