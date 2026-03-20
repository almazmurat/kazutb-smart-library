# App-Ready Professional Schema (Phase A-E)

Date: 2026-03-20  
Status: implemented on top of existing reconstruction  
Mode: non-destructive (raw/core/review preserved)

## 1) What Was Built

A new application-facing PostgreSQL schema named `app` was created and populated from current `core` data using normalization and review-flag workflows.

Implemented artifacts:

- SQL schema + backfill: `backend/scripts/legacy-reconstruction/app-ready-schema.sql`
- Runner script: `backend/scripts/legacy-reconstruction/build-app-ready-schema.js`
- NPM command: `npm run build:app-ready-schema`

This does not rerun SQL Server ingestion and does not delete or overwrite legacy evidence in `raw`.

## 2) Phase A - Dirty Value Normalization Policy

Applied policy (high-confidence, reversible):

- Empty/whitespace strings -> `NULL`
- Placeholder junk values (`-`, `--`, `---`, `.`, `unknown`, `нет`, `n/a`, `na`, `null`) -> `NULL`
- ISBN:
  - `isbn_raw` preserved
  - `isbn_normalized` set only when pattern-valid (`13` digits or `10` with possible `X`)
  - invalid/missing ISBN is flagged
- Language:
  - `language_raw` preserved
  - normalized canonical code mapped to `rus`, `kaz`, `eng`, `ger` where deterministic
  - otherwise `language_code = NULL` and flagged
- Orphans/unresolved links:
  - source linkage preserved (`legacy_doc_id`, `legacy_inv_id`)
  - app row marked `needs_review = true`
- Safe auto-corrections included:
  - trimming/collapsing spaces
  - punctuation cleanup via normalized fields
  - high-confidence title substitutions (word-level):
    - `Казак` -> `Қазақ`
    - `Адебиет` -> `Әдебиет`

## 3) Phase B - Final Professional App Schema

Main entities created:

- `app.documents`
- `app.document_titles`
- `app.authors`
- `app.document_authors`
- `app.publishers`
- `app.subjects`
- `app.keywords`
- `app.document_subjects`
- `app.document_keywords`
- `app.book_copies`
- `app.branches`
- `app.siglas`
- `app.readers`
- `app.reader_contacts`
- `app.data_quality_flags`
- `app.review_tasks`
- `app.source_lineage`
- `app.normalization_runs`

Schema is normalized, relation-driven, and ready for Prisma/API/UI usage.

## 4) Required vs Optional Fields (App Layer)

### `app.documents`

Required:

- `id`
- `legacy_doc_id`
- `core_document_id`
- `needs_review`
- `review_reason_codes`

Optional/nullable (normalized where possible):

- titles, subtitle, publication place
- publication year
- ISBN normalized/raw
- language normalized/raw
- publisher relation
- taxonomy fields (`faculty`, `department`, `specialization`)

### `app.book_copies`

Required:

- `id`
- `legacy_inv_id`
- `core_copy_id`
- `needs_review`

Optional/nullable:

- `document_id` (nullable for orphans)
- `branch_id`, `sigla_id`
- normalized inventory/branch hint

### `app.readers`

Required:

- `id`
- `legacy_reader_id`
- `core_reader_id`
- `needs_review`

Optional/nullable:

- normalized code/name variants
- registration fields

### `app.reader_contacts`

Required:

- `reader_id`
- `contact_type`
- placeholder/validation flags

Optional/nullable:

- contact values (can be null placeholder for missing email)

## 5) Phase C - Non-Destructive Backfill

Backfill strategy:

- Source of truth remains `raw` + `core` + `review`
- New app schema is populated from `core` (plus review/lineage logic)
- Legacy IDs are preserved (`legacy_doc_id`, `legacy_inv_id`, `legacy_reader_id`)
- Source payload is copied for traceability
- All risky/unclear values are flagged instead of aggressively rewritten

## 6) Phase D - Safe Auto-Normalization Applied

Auto-normalized:

- trim/collapse spaces
- placeholder-to-null conversion
- ISBN formatting normalization to compact canonical form
- language canonical mapping where deterministic
- selected high-confidence Kazakh Cyrillic corrections

Flag-only (manual review later):

- missing title/ISBN/author links
- orphan document links on copies
- unknown language values
- missing/invalid reader contact data

## 7) Phase E - Librarian Review Readiness

Review model implemented:

- `app.data_quality_flags`: per-entity issue records with raw/normalized/suggested values
- `app.review_tasks`: actionable queue rows for librarian panel
- `app.source_lineage`: link every app entity back to source schema/table/key/payload

This supports UI workflows:

- show problematic rows
- show raw value vs normalized value
- show suggestion
- let librarian confirm or edit later

## 8) Exact Backfill Commands

From `backend`:

```bash
npm run build:app-ready-schema
```

Validation command examples:

```bash
psql -h localhost -p 5432 -U kazutb_user -d kazutb_library -c "select count(*) from app.documents"
psql -h localhost -p 5432 -U kazutb_user -d kazutb_library -c "select count(*) from app.data_quality_flags"
```

## 9) Sample Transformed Rows

Raw -> normalized document examples:

- `legacy_doc_id=3`
  - `isbn_raw=988-604-06-1345-0`
  - `isbn_normalized=9886040613450`
  - `language_raw=kaz`
  - `language_code=kaz`
  - `needs_review=false`

- `legacy_doc_id=8`
  - `isbn_raw=978-1-4715-7388-0`
  - `isbn_normalized=9781471573880`
  - `language_raw=eng`
  - `language_code=eng`
  - `needs_review=true`
  - `review_reason_codes=missing_author_link`

Flagged copy example:

- `legacy_inv_id=39`
  - `legacy_doc_id=5`
  - `inventory_number_normalized=91667`
  - `needs_review=true`
  - `review_reason_codes=orphan_copy_document`

Flagged document example:

- `legacy_doc_id=4`
  - `title_raw=...`
  - `isbn_raw=NULL`
  - `language_code=rus`
  - `needs_review=true`
  - `review_reason_codes=missing_isbn,missing_author_link`

## 10) Confirmation Raw/Core/Review Remain Intact

Post-backfill counts:

- `core.documents = 8930`, `app.documents = 8930`
- `core.book_copies = 49620`, `app.book_copies = 49620`
- `core.legacy_readers = 2319`, `app.readers = 2319`
- `review.quality_issues = 2971` (unchanged)
- `app.data_quality_flags = 4153` (new app-layer review artifacts)

Conclusion:

- Existing `raw/core/review` data was not destroyed or overwritten.
- App-ready schema is demo-ready and supports delayed librarian cleanup.
