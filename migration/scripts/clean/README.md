# Data Cleaning Pipeline

## Purpose

Transform raw MARC SQL export data into clean, deduplicated, encoding-normalized data suitable for the transformation step.

## What This Step Does

1. **Encoding normalization** — ensure all strings are valid UTF-8
2. **Whitespace cleanup** — trim leading/trailing spaces, normalize multiple spaces
3. **Duplicate detection** — flag exact duplicates by ISBN, then by title+author+year
4. **Date validation** — flag publish years outside valid range (e.g., < 1800 or > current year)
5. **Required field check** — flag records missing `title`
6. **Null normalization** — convert empty strings to null where appropriate
7. **Log all changes** — every modification recorded in the output log

## Running the Clean Script

```bash
# Install dependencies (first time only)
npm install

# Run cleaning pipeline
node clean.js \
  --input ../../raw/2026-03-17_batch-001_books.json \
  --output ../../clean/2026-03-17_batch-001_books_clean.json \
  --log ../../logs/2026-03-17_batch-001_clean.log
```

## Output

- **Clean data file** in `migration/clean/` — JSON with same structure as input but cleaned
- **Cleaning log** in `migration/logs/` — JSON log with:
  - Total records processed
  - Records passed without changes
  - Records modified (with list of changes)
  - Records flagged for review (with reasons)
  - Records removed as duplicates

## Script Status

> **TODO:** This script must be written after the MARC SQL export format is analyzed.
> See `migration/scripts/export/README.md` for the analysis steps.
>
> The script will be implemented as a Node.js / TypeScript program using:
>
> - `zod` for schema validation
> - Custom deduplication logic based on ISBN and title+author similarity
> - `fast-csv` or native JSON parsing depending on export format
