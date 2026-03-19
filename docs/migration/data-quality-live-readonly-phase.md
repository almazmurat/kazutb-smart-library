# Data Quality Workbench Live Read-Only Phase

## Purpose

This phase turns the Data Quality Workbench from static scaffold into a live, read-only review surface.

The implementation is intentionally safe:

- No write-back to legacy SQL Server
- No mutation of source artifacts
- No migration/import execution
- No correction persistence

## Artifact Sources

The backend reads committed artifacts from `docs/legacy-db-discovery/`:

- `dbo_DOC_VIEW.csv`
- `table_row_counts.csv`
- `columns_inventory.csv`
- `foreign_keys.csv`

Runtime does not require direct access to the old MARC SQL Server.

## API Endpoints

Base path: `/api/v1/migration/data-quality`

- `GET /summary`
- `GET /issues`
- `GET /issues/:id`

Supported filters on `summary` and `issues`:

- `stage`
- `severity`
- `issueClass`
- `status`
- `sourceTable`

`issues` also supports:

- `limit`

## Implemented Detection Rules (MVP)

All implemented rules are deterministic and based on parsed `dbo_DOC_VIEW.csv` records.

- `missing_title` (MARC `245$a`/`245$b` absent)
- `missing_author` (MARC `1001$a`, `245$c`, `7001$a` absent)
- `missing_publication_year` (MARC `260$c` unresolved)
- `missing_language_code` (MARC `041$a` absent)
- `malformed_isbn` (invalid ISBN-10/ISBN-13 checksum after normalization)
- `incomplete_publication_metadata` (MARC `260$a` or `260$b` absent)
- `suspiciously_sparse_record` (too few core bibliographic fields present)

## Planned But Not Implemented Yet

- Referential checks across `DOC`, `INV`, `BOOKSTATES`
- Duplicate identity collision checks across full corpus
- Branch ownership validation through `BOOKPOINTS` + `LIBS*`
- Reviewer decision persistence and audit trail write model
- Promotion gates from `clean` to `normalized` based on policy thresholds

## Safety Notes

- API is read-only by design.
- Issues are derived in memory from committed artifacts.
- Review statuses are deterministic mock states for triage UX only.
- The phase improves visibility and readiness without changing migration data.

## Frontend Integration

`Data Quality Workbench` now consumes live API responses for:

- KPI summary cards
- issue queue
- filters
- issue detail panel

Review action buttons remain disabled to preserve read-only behavior.
