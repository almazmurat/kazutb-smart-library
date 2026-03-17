# Data Transformation / Normalization Pipeline

## Purpose

Map cleaned MARC SQL data to the new PostgreSQL schema structure, resolving entities (authors, categories, publishers) as distinct records and preparing import-ready JSON.

## What This Step Does

1. **MARC field mapping** — map each MARC field to the corresponding new schema field (see `docs/migration/concept.md` for the full mapping table)
2. **Author extraction** — split combined author strings into individual Author records; detect duplicates
3. **Publisher normalization** — resolve publisher names to a shared Publisher entity (deduplicated by name)
4. **Category mapping** — map MARC subject headings (650, 610) to Category entities; create new categories if needed
5. **Inventory number assignment** — map copy records to BookCopy entities
6. **Language normalization** — map language codes to ISO 639-1 standard
7. **Missing metadata enrichment** — where derivable (e.g., language from title content), attempt to fill gaps
8. **Search vector preparation** — pre-compute `tsvector` values for FTS (will be re-indexed by DB trigger post-import)
9. **Output validation** — validate all output records against target schema using Zod

## Running the Transform Script

```bash
# Run transformation
node transform.js \
  --input ../../clean/2026-03-17_batch-001_books_clean.json \
  --output ../../normalized/2026-03-17_batch-001_normalized.json \
  --log ../../logs/2026-03-17_batch-001_transform.log
```

## Output

- **Normalized data file** in `migration/normalized/` — JSON containing arrays of:
  - `books[]`
  - `authors[]`
  - `publishers[]`
  - `categories[]`
  - `bookAuthors[]` (join records)
  - `bookCategories[]` (join records)
  - `copies[]`
- **Transform log** in `migration/logs/` — counts, skipped records, mapping decisions

## Script Status

> **TODO:** To be implemented after the clean step schema is confirmed.
> Depends on analysis of actual MARC SQL export format.
