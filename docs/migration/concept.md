# Migration Pipeline — KazUTB Smart Library

## Overview

The migration pipeline moves bibliographic and operational data from the legacy **MARC SQL** system into the new **PostgreSQL** database. This is a one-time migration (executed per batch/acquisition cycle), not a continuous sync.

## Pipeline Stages

```
MARC SQL (source)
      │
      ▼ export
migration/raw/           ← Archive, never modified
      │
      ▼ clean
migration/clean/         ← Deduplicated, encoding-fixed
      │
      ▼ transform
migration/normalized/    ← Mapped to target schema
      │
      ▼ import
PostgreSQL               ← Production database
```

## Data Zone Rules

| Zone          | Rule                                                                                          |
| ------------- | --------------------------------------------------------------------------------------------- |
| `raw/`        | **Read-only after creation.** Never modify raw files. This is the source of truth for audits. |
| `clean/`      | Derived from raw. Re-generatable by re-running the clean script.                              |
| `normalized/` | Derived from clean. Re-generatable by re-running the transform script.                        |
| `PostgreSQL`  | Import is idempotent — re-running the same batch skips already-imported records.              |

## File Naming Convention

```
migration/raw/YYYY-MM-DD_batch-NNN_<description>.<ext>
migration/clean/YYYY-MM-DD_batch-NNN_<description>_clean.json
migration/normalized/YYYY-MM-DD_batch-NNN_<description>_normalized.json
migration/checksums/YYYY-MM-DD_batch-NNN.sha256
migration/logs/YYYY-MM-DD_batch-NNN_migration.log
```

## Running a Migration Batch

### Step 1 — Export from MARC SQL

See `migration/scripts/export/README.md` for instructions on how to export data from MARC SQL.

### Step 2 — Clean the raw data

```bash
cd migration/scripts/clean
node clean.js --input ../../raw/2026-03-17_batch-001_full.json \
              --output ../../clean/2026-03-17_batch-001_full_clean.json \
              --log ../../logs/2026-03-17_batch-001_clean.log
```

### Step 3 — Transform / normalize

```bash
cd migration/scripts/transform
node transform.js --input ../../clean/2026-03-17_batch-001_full_clean.json \
                  --output ../../normalized/2026-03-17_batch-001_full_normalized.json \
                  --log ../../logs/2026-03-17_batch-001_transform.log
```

### Step 4 — Verify checksum

```bash
cd migration/scripts
node verify-integrity.js --file ../normalized/2026-03-17_batch-001_full_normalized.json \
                         --checksum ../checksums/2026-03-17_batch-001.sha256
```

### Step 5 — Import to PostgreSQL (via Admin Panel or script)

```bash
cd migration/scripts/import
node import.js --batch 2026-03-17_batch-001 \
               --input ../../normalized/2026-03-17_batch-001_full_normalized.json
```

Or trigger from the Admin Panel under Migration → Run Batch.

## Integrity Controls

- **Checksums** — SHA-256 computed for raw file and normalized file; stored in `migration/checksums/`
- **Record counts** — file count vs. import count logged in `MigrationBatch`
- **Error capture** — each failed record logged with its raw data in `MigrationLog`
- **Idempotency** — import checks for existing inventory numbers / ISBNs before inserting

## What MARC Fields Map To

| MARC Field        | New Schema Field                     |
| ----------------- | ------------------------------------ |
| 245 $a            | `Book.title`                         |
| 245 $b            | `Book.subtitle`                      |
| 100 $a, 700 $a    | `Author.fullName` (via `BookAuthor`) |
| 020               | `Book.isbn`                          |
| 022               | `Book.issn`                          |
| 080               | `Book.udc`                           |
| 260 $b, 264 $b    | `Publisher.name`                     |
| 260 $c, 264 $c    | `Book.publishYear`                   |
| 260 $a            | `Publisher.city`                     |
| 041 $a            | `Book.language`                      |
| 300 $a            | `Book.pageCount`                     |
| 650, 610          | `Category` (lookup/create)           |
| 520               | `Book.description`                   |
| Custom fund field | `BookCopy.fund`                      |
| Inventory number  | `BookCopy.inventoryNumber`           |

Note: Exact MARC field mapping should be verified against the actual MARC SQL export format. MARC SQL implementations vary. The mapping above is based on standard MARC 21 bibliographic format.

## Assumptions

- MARC SQL can export records as CSV, XML, or JSON (to be confirmed).
- The export includes bibliographic records AND copy/inventory records.
- User data (borrower records) may or may not be exported — TBD after analysis.
- The actual data volume is estimated at 40,000–60,000 bibliographic records (TBC).
