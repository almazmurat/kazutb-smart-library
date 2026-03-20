const fs = require("node:fs");
const path = require("node:path");
const crypto = require("node:crypto");

const dotenv = require("dotenv");
const { Client } = require("pg");

dotenv.config({ path: path.resolve(__dirname, "../../.env") });

async function main() {
  const sqlPath = path.resolve(__dirname, "app-read-layer.sql");
  const sql = fs.readFileSync(sqlPath, "utf8");

  const client = new Client({ connectionString: process.env.DATABASE_URL });
  await client.connect();

  const runId = crypto.randomUUID();

  try {
    await client.query("BEGIN");
    await client.query(sql);

    const metrics = {};
    const countQueries = {
      catalogRows: "SELECT count(*)::int AS c FROM app.catalog_search_mv",
      detailRows: "SELECT count(*)::int AS c FROM app.document_detail_v",
      availabilityRows:
        "SELECT count(*)::int AS c FROM app.document_availability_by_location_v",
      locationSummaryRows:
        "SELECT count(*)::int AS c FROM app.location_inventory_summary_v",
      reviewQueueRows: "SELECT count(*)::int AS c FROM app.review_queue_v",
      facetRows: "SELECT count(*)::int AS c FROM app.catalog_filter_facets_v",
      searchableDocuments:
        "SELECT count(*)::int AS c FROM app.catalog_search_mv WHERE searchable_text IS NOT NULL AND btrim(searchable_text) <> ''",
      documentsWithAvailableCopies:
        "SELECT count(*)::int AS c FROM app.catalog_search_mv WHERE available_copy_count > 0",
      documentsWithOpenReview:
        "SELECT count(*)::int AS c FROM app.catalog_search_mv WHERE has_open_review",
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
