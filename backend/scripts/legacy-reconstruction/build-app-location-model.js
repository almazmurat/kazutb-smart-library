const fs = require("node:fs");
const path = require("node:path");
const crypto = require("node:crypto");

const dotenv = require("dotenv");
const { Client } = require("pg");

dotenv.config({ path: path.resolve(__dirname, "../../.env") });

async function main() {
  const sqlPath = path.resolve(__dirname, "app-location-model.sql");
  const sql = fs.readFileSync(sqlPath, "utf8");

  const client = new Client({ connectionString: process.env.DATABASE_URL });
  await client.connect();

  const runId = crypto.randomUUID();

  try {
    await client.query("BEGIN");
    await client.query(sql);

    const metrics = {};
    const countQueries = {
      institutionUnits: "SELECT count(*)::int AS c FROM app.institution_units",
      campuses: "SELECT count(*)::int AS c FROM app.campuses",
      servicePoints: "SELECT count(*)::int AS c FROM app.branches",
      siglas: "SELECT count(*)::int AS c FROM app.siglas",
      locationCandidates:
        "SELECT count(*)::int AS c FROM app.location_mapping_candidates",
      readerServicePoints:
        "SELECT count(*)::int AS c FROM app.reader_service_points",
      locationFlags: `SELECT count(*)::int AS c FROM app.data_quality_flags WHERE issue_code IN ('location_mapping_requires_confirmation', 'location_mapping_conflict', 'reader_location_mapping_requires_confirmation')`,
      locationTasks: `SELECT count(*)::int AS c FROM app.review_tasks WHERE task_type = 'LOCATION_MAPPING_REVIEW'`,
    };

    for (const [key, query] of Object.entries(countQueries)) {
      const result = await client.query(query);
      metrics[key] = result.rows[0].c;
    }

    await client.query("COMMIT");

    console.log(
      JSON.stringify(
        {
          runId,
          status: "COMPLETED",
          metrics,
          sqlPath,
        },
        null,
        2,
      ),
    );
  } catch (error) {
    await client.query("ROLLBACK");
    throw error;
  } finally {
    await client.end();
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
