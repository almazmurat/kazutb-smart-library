# Legacy DB Reconstruction Autopilot

## Purpose

This pass adds an automated reconstruction pipeline that reads the restored SQL Server database directly and rebuilds a layered PostgreSQL reconstruction model.

## Entry Point

From `backend/`:

```bash
npm run reconstruct:legacy-db
```

## Direct Source Read

The pipeline does not depend on sample CSVs for reconstruction.

It exports directly from the locally restored SQL Server database using Windows authentication via:

- `backend/scripts/legacy-reconstruction/export-legacy-tables.ps1`

Default source connection:

- instance: `localhost\SQLEXPRESS`
- database: `marc_restored`

These can be overridden with environment variables:

- `LEGACY_SQLSERVER_INSTANCE`
- `LEGACY_SQLSERVER_DATABASE`

## Target PostgreSQL Layers

The runner creates and populates these PostgreSQL schemas:

- `raw`
- `parsed`
- `core`
- `migration`
- `review`

## Source Tables Ingested

- `DOC`
- `DOC_VIEW`
- `INV`
- `READERS`
- `RDRBP`
- `BOOKPOINTS`
- `SIGLAS`
- `POINTSIGLA`
- `LIBS`
- `LIBS_BOOKPOINTS`
- `LIBS_SIGLAS`
- `PUBLISHER`
- `BOOKSTATES`
- `PREORDERS`

## Normalization Rules Implemented

- `DOC.ITEM` treated as bibliographic source of truth
- `DOC_VIEW` used as extraction helper and verification layer
- MARC-like extraction implemented for:
  - `245$a`, `245$b`, `245$h`, `245$z`
  - `100$a` / `1001$a`
  - `700$a` / `7001$a`
  - `260$a`, `260$b`, `260$c`
  - `020$a`
  - `041$a`
  - `653$a`
  - `952$a`, `952$d`, `952$e`, `952$f`, `952$j`
  - `990$d`, `990$e`, `990$f`
- legacy float dates normalized to timestamps/dates while preserving raw values
- duplicate-prone values are classified in `review.quality_issues` instead of deleted

## Quality Issues Generated Automatically

- missing title
- missing author
- missing publication year
- missing ISBN
- missing language code
- suspiciously sparse records
- orphan inventory rows
- orphan reader references
- probable duplicate ISBN groups
- probable duplicate document fingerprints

## Runtime Artifacts

The runner writes:

- exported NDJSON table snapshots under `migration/raw/legacy-reconstruction/<timestamp>/`
- batch summaries under `migration/logs/`

## Current Scope Notes

- reconstruction targets the legacy-to-clean layered model, not the existing app public schema
- no legacy source records are mutated
- no legacy rows are discarded purely because fields are missing
