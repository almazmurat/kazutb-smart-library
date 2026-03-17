# Data Flow & Migration Concept — KazUTB Smart Library

## System Data Flow

### Normal Operation (Post-Migration)

```
User Browser
     │
     │ HTTPS request
     ▼
React SPA (frontend)
     │
     │ REST API call  /api/v1/...
     ▼
NestJS Backend
     │
     ├─── Auth request ──────► University LDAP / AD
     │                          (verify credentials, get user attributes)
     │
     ├─── Data query ────────► PostgreSQL
     │                          (books, copies, loans, reservations)
     │
     └─── File request ──────► File Storage (local disk)
                                (stream file with security headers)
```

### Authentication Flow

```
1. User submits university username + password on /login
2. Backend connects to LDAP (ldaps://) with service account credentials
3. LDAP verifies user credentials and returns user attributes
4. Backend looks up or creates User record in PostgreSQL (first login auto-provisions)
5. Backend issues JWT access token (1d) + refresh token (7d)
6. Frontend stores tokens; attaches access token to all API requests
7. On 401, frontend uses refresh token to get new access token
8. On refresh failure, redirect to login
```

### Digital File Access Flow (Copyright-Compliant)

```
1. Authenticated user requests file: GET /api/v1/files/:id/view
2. Backend verifies JWT + role (Guest → blocked)
3. Backend looks up BookFile record → gets internal storagePath
4. Backend streams file content to browser with:
   - Content-Type: application/pdf
   - Content-Disposition: inline (NOT attachment — prevents Save As)
   - X-Frame-Options: SAMEORIGIN
   - Cache-Control: no-store
   - Custom viewer disables text selection and right-click context menu
5. storagePath is NEVER exposed to the client
6. File URL tokens expire and cannot be shared
```

---

## Migration Data Pipeline

### Overview

The migration pipeline transforms legacy data from MARC SQL through multiple quality-controlled zones before entering the production database.

```
┌─────────────────────────────────────────────────────────────────────┐
│                        SOURCE                                        │
│                       MARC SQL                                       │
│   (legacy IRBIS-compatible system on university server)             │
└────────────────────────┬────────────────────────────────────────────┘
                         │ Manual export (SQL dump or CSV/XML export)
                         │ Performed once (or per batch for new acquisitions)
                         ▼
┌─────────────────────────────────────────────────────────────────────┐
│                     ZONE 1 — RAW DATA                               │
│                   migration/raw/                                     │
│                                                                      │
│  • Unmodified export from MARC SQL                                   │
│  • Stored as JSON / CSV / XML (original format preserved)           │
│  • NEVER modified — this is the archive snapshot                    │
│  • SHA-256 checksum computed and stored in migration/checksums/     │
│  • Version-tagged by date and batch number                          │
└────────────────────────┬────────────────────────────────────────────┘
                         │ scripts/clean/  (cleaning pipeline)
                         ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    ZONE 2 — CLEAN DATA                              │
│                   migration/clean/                                   │
│                                                                      │
│  Operations performed:                                               │
│  • Remove exact duplicates (same ISBN + title + author)             │
│  • Normalize encoding (UTF-8 standardization)                       │
│  • Fix obviously malformed dates (e.g., years out of range)         │
│  • Trim whitespace, fix casing inconsistencies                      │
│  • Flag records with missing required fields (title, author)        │
│  • Log all records that were modified or flagged                    │
│                                                                      │
│  Output: clean JSON with cleaning report + diff summary             │
└────────────────────────┬────────────────────────────────────────────┘
                         │ scripts/transform/  (normalization pipeline)
                         ▼
┌─────────────────────────────────────────────────────────────────────┐
│                  ZONE 3 — NORMALIZED DATA                           │
│                 migration/normalized/                                │
│                                                                      │
│  Operations performed:                                               │
│  • Map MARC fields to new schema (title, authors, ISBN, UDC, etc.)  │
│  • Extract and normalize author names (split combined fields)       │
│  • Resolve or create Category entries from subject fields           │
│  • Resolve or create Publisher entries                              │
│  • Assign inventory numbers to copies                               │
│  • Enrich missing metadata where derivable                          │
│  • Final deduplication at the normalized model level                │
│  • Generate import-ready JSON in target schema format               │
│                                                                      │
│  Output: normalized JSON files + transformation report              │
└────────────────────────┬────────────────────────────────────────────┘
                         │ scripts/import/  (database import)
                         ▼
┌─────────────────────────────────────────────────────────────────────┐
│                   PRODUCTION DATABASE                               │
│                      PostgreSQL                                      │
│                                                                      │
│  Import process:                                                     │
│  • Batch import via MigrationBatch + MigrationLog tracking         │
│  • Transaction-per-record with error capture (no partial batches)   │
│  • Integrity check: record count in file vs. records imported       │
│  • Checksum verification of normalized data before import           │
│  • Idempotent: re-running the same batch skips already-imported IDs │
│  • Import result written to MigrationBatch status in DB             │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Migration Logging Requirements

Every migration batch must produce a structured log containing:

```json
{
  "batchId": "uuid",
  "batchName": "2026-03-marc-sql-full-export",
  "startedAt": "2026-03-17T10:00:00Z",
  "completedAt": "2026-03-17T11:30:00Z",
  "totalRecords": 45820,
  "importedRecords": 45791,
  "skippedDuplicates": 21,
  "failedRecords": 8,
  "checksumSource": "sha256:abc123...",
  "checksumNormalized": "sha256:def456...",
  "errors": [
    { "recordId": "MARC-12345", "reason": "missing_title", "rawData": {} },
    ...
  ]
}
```

---

## Data Versioning

Every full export from MARC SQL is treated as a **versioned data snapshot**.

Naming convention:

```
migration/raw/YYYY-MM-DD_batch-NNN_description.json
```

Example:

```
migration/raw/2026-03-17_batch-001_full-marc-export.json
migration/raw/2026-06-01_batch-002_new-acquisitions-q2.json
```

This allows:

- Re-running or debugging any past migration
- Comparing data evolution across time
- Auditing what data existed at any point in time

---

## Integrity Controls

| Stage                   | Control                                                       |
| ----------------------- | ------------------------------------------------------------- |
| Raw export              | SHA-256 checksum of raw file stored in `migration/checksums/` |
| Clean → Normalized      | Record count comparison; diff report                          |
| Normalized → PostgreSQL | Count verification after batch import                         |
| Production DB           | Prisma migrations with version history                        |
| Ongoing                 | Audit log on all book/copy changes                            |
