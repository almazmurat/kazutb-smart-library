# Database Import Script

## Purpose

Import normalized migration data into the PostgreSQL production database using the Prisma ORM. All imports are tracked via the `MigrationBatch` and `MigrationLog` tables.

## Prerequisites

- PostgreSQL running with the `kazutb_library` database
- Prisma migration applied (`npx prisma migrate dev` from `backend/`)
- Normalized data file ready in `migration/normalized/`
- `.env` file in `backend/` with valid `DATABASE_URL`

## Running the Import

```bash
# Run from the backend directory (uses Prisma client)
cd ../../../backend

npx ts-node -r tsconfig-paths/register \
  ../migration/scripts/import/import.ts \
  --batch 2026-03-17_batch-001 \
  --input ../migration/normalized/2026-03-17_batch-001_normalized.json \
  --checksum ../migration/checksums/2026-03-17_batch-001.sha256
```

Or trigger via the Admin Panel: **Admin → Migration → Create Batch → Run**.

## Import Behavior

- **Idempotent:** If a record with the same `inventoryNumber` (for copies) or `isbn` (for books) already exists, the record is skipped and logged as `DUPLICATE`.
- **Transactional per record:** Each record is imported in its own try/catch. A failure on one record does not stop the batch.
- **Batch tracking:** A `MigrationBatch` record is created/updated in the DB with counts and status.
- **Per-record logging:** Each record's result (success/error/duplicate) is logged to `MigrationLog`.
- **Checksum verification:** If a checksum file is provided, the import verifies the normalized file hash before proceeding.

## Post-Import Verification

After import completes:

1. Check `MigrationBatch` status: should be `COMPLETED` or `PARTIAL`
2. Verify `importedRecords + failedRecords + skippedDuplicates == totalRecords`
3. Run a sample query to verify data integrity: `SELECT COUNT(*) FROM books;`
4. Spot-check a few records manually

## Script Status

> **TODO:** To be implemented in Phase 8 (Migration Pipeline).
> Will use `@prisma/client` directly for bulk insertion with controlled batching.
