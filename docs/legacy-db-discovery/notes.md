# Legacy DB Discovery Notes

## Source

Artifacts were exported from the legacy university library server.

## Confirmed facts

- Legacy database name: `marc`
- Legacy DB engine: Microsoft SQL Server
- Backup file available: `marc_full.bak`
- Schema export available: `old-db-schema.sql`
- Columns inventory available: `columns_inventory.csv`
- Table row counts available: `table_row_counts.csv`
- Foreign keys inventory available: `foreign_keys.csv`
- Sample/exported view available: `dbo_DOC_VIEW.csv`

## Legacy environment observations

- Legacy catalog application uses MARC-style bibliographic records
- IIS is used for the old web catalog
- MarcIndex-related errors were observed on the server
- The database appears to include bibliographic, acquisition, state, and indexing-related tables

## Likely important table groups

- `DOC*` — likely bibliographic records / documents
- `BOOKSTATES` — likely copy or availability states
- `BOOKPOINTS` — likely storage / branch / holding points
- `ACQ*` — likely acquisitions / procurement / invoice-related data
- `IDX*` — likely indexing/search support tables

## Open questions

- Which table is the real source of truth for bibliographic records?
- Which tables store physical copies?
- Which tables store readers/users?
- Which tables store circulation history?
- Which structures are generated/index-only and should not be migrated directly?
- How are branch/library ownership units represented in legacy data?

## Next steps

- Inspect schema and row counts
- Identify top domain tables
- Produce old-to-new mapping draft
- Plan migration readiness
- Design Data Quality Workbench for librarian review
