# Export from MARC SQL

## Overview

This directory contains instructions and scripts for exporting data from the legacy MARC SQL system.

## Prerequisites

- Access to the university server where MARC SQL is installed
- SQL Server Management Studio (SSMS) or equivalent connection to MARC SQL database
- Sufficient disk space for export files

## Step 1 — Discover the Database Schema

Before writing export queries, you must first analyze the MARC SQL database structure:

```sql
-- List all tables
SELECT TABLE_NAME, TABLE_TYPE
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_TYPE = 'BASE TABLE'
ORDER BY TABLE_NAME;

-- For each key table, inspect columns
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = '<table_name>'
ORDER BY ORDINAL_POSITION;
```

## Step 2 — Identify Key Entities

Look for tables related to:

- Bibliographic records (books, titles, MARC fields)
- Authors / subject headings
- Physical copies / inventory
- Borrower records (if needed)
- Loan/circulation history (if needed)
- Subject categories / classification codes
- Publishers / organizations
- Acquisition invoices

## Step 3 — Export to JSON/CSV

Once the schema is understood, export each entity to a separate JSON or CSV file:

```sql
-- Example: Export bibliographic records
SELECT * FROM <books_table>
FOR JSON PATH, ROOT('books');
```

Save each export to `migration/raw/YYYY-MM-DD_batch-001_<entity>.json`.

## Step 4 — Compute Checksums

After export, compute SHA-256 checksums:

```powershell
# Windows PowerShell
Get-FileHash .\raw\2026-03-17_batch-001_books.json -Algorithm SHA256 |
  Select-Object Hash, Path |
  ConvertTo-Json |
  Out-File .\checksums\2026-03-17_batch-001.sha256
```

## Notes

- MARC SQL is based on the MARC 21 bibliographic format standard.
- Fields are typically stored in a structured way within the DB, either as normalized columns or as raw MARC field codes.
- The exact schema must be analyzed before the transform script can be written.
- Document any MARC field → column mappings discovered during analysis.
