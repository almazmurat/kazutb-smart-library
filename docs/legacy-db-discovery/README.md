# Legacy DB Discovery Artifacts

Raw discovery materials exported from the legacy **MARC SQL Server** database (`marc`).

## Files

| File | Description |
|------|-------------|
| `columns_inventory.csv` | Full column inventory: schema, table, column, type, nullable, max-length |
| `table_row_counts.csv` | Row counts per table (168 tables) |
| `foreign_keys.csv` | FK relationships between legacy tables |
| `dbo_DOC_VIEW.csv` | Sample rows from the `DOC_VIEW` view (bibliographic records) |
| `old-db-schema.sql` | DDL schema export from the legacy database |
| `notes.md` | Discovery session notes and observations |

## What is NOT here

| File | Reason |
|------|--------|
| `marc_full.bak` | SQL Server full backup — binary, ~large. Excluded from Git via `.gitignore`. Store locally or on a shared drive only. |

## How to restore marc_full.bak locally

Ask the DBA team for the latest backup file and place it at:

```
docs/legacy-db-discovery/marc_full.bak
```

It will be ignored by Git automatically.

## Key facts (as of discovery phase)

- **Database name**: `marc` (Microsoft SQL Server)
- **Tables**: 168
- **Views**: 1 (`DOC_VIEW`)
- **Bibliographic records (DOC)**: ~8,930
- **Inventory copies (INV)**: ~49,620
- **Readers (READERS)**: ~2,319
- **Search index tables (IDX\*)**: 62

See `docs/migration/legacy-db-analysis.md` for the full grounded analysis.
