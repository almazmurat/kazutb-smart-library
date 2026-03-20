const fs = require("node:fs");
const path = require("node:path");
const crypto = require("node:crypto");

const dotenv = require("dotenv");
const { Client } = require("pg");

dotenv.config({ path: path.resolve(__dirname, "../../.env") });

async function main() {
  const sqlPath = path.resolve(__dirname, "app-ready-schema.sql");
  const sql = fs.readFileSync(sqlPath, "utf8");

  const client = new Client({ connectionString: process.env.DATABASE_URL });
  await client.connect();

  const runId = crypto.randomUUID();

  try {
    await client.query("BEGIN");
    await client.query(sql);

    const metrics = {};

    const countQueries = {
      publishers: "SELECT count(*)::int AS c FROM app.publishers",
      authors: "SELECT count(*)::int AS c FROM app.authors",
      documents: "SELECT count(*)::int AS c FROM app.documents",
      copies: "SELECT count(*)::int AS c FROM app.book_copies",
      readers: "SELECT count(*)::int AS c FROM app.readers",
      qualityFlags: "SELECT count(*)::int AS c FROM app.data_quality_flags",
      reviewTasks: "SELECT count(*)::int AS c FROM app.review_tasks",
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
