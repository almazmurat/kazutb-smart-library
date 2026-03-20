# App Location Model Alignment

Date: 2026-03-20  
Status: implemented on top of existing app schema  
Mode: non-destructive extension of `app` layer

## 1) Goal

Normalize the KazUTB library operational location model so the app can correctly support:

- search filters by location
- copy availability by location
- librarian workflows by branch/service point
- reporting by college/university/campus
- future circulation and RBAC scoping

This was implemented without rerunning legacy ingestion and without destroying `raw`, `core`, `review`, or the previously built `app` data.

## 2) Legacy Location Signals Analyzed

Signals used:

- `raw.bookpoints`
- `raw.siglas`
- `raw.pointsigla`
- `raw.rdrbp`
- `core.library_branches`
- `core.storage_siglas`
- `core.book_copies.branch_hint`
- `core.legacy_readers.bookpoints_raw`
- existing `app.branches`, `app.siglas`, `app.book_copies`, `app.readers`

Observed baseline:

- `raw.bookpoints`: 5 rows
- `raw.siglas`: 4 rows
- `raw.pointsigla`: 4 rows
- `core.library_branches`: 5 rows
- `core.storage_siglas`: 4 rows

Key legacy service point / storage signals resolved:

- `KSTLIB`
- `ab1`
- `ab2`
- `Абонемент 1`
- `Абонемент 2`
- `Абонемент 3`
- `НБ КазУТБ`
- `Колледж -`

Interpretation used:

- `Абонемент 1` + `ab1` -> University Economic location
- `Абонемент 2` + `ab2` -> University Technological location
- `Абонемент 3` + `Колледж -` -> College Main location
- `KSTLIB` and `НБ КазУТБ` -> University Central / Shared location

## 3) Target Location Hierarchy

Implemented hierarchy:

1. Institution unit

- `COLLEGE`
- `UNIVERSITY`

2. Campus / site

- `COLLEGE_MAIN`
- `UNIVERSITY_ECONOMIC`
- `UNIVERSITY_TECHNOLOGICAL`
- `UNIVERSITY_CENTRAL`

3. Service point

- stored in existing extended `app.branches`

4. Storage / sigla

- stored in existing extended `app.siglas`

## 4) App Schema Changes Implemented

New tables:

- `app.institution_units`
- `app.campuses`
- `app.reader_service_points`
- `app.location_mapping_candidates`

Extended existing tables:

- `app.branches`
  - `institution_unit_id`
  - `campus_id`
  - `branch_kind`
  - `legacy_location_code`
  - `mapping_status`
  - `mapping_confidence`
  - `mapping_method`
  - `location_notes`
- `app.siglas`
  - `institution_unit_id`
  - `campus_id`
  - `mapping_status`
  - `mapping_confidence`
  - `mapping_method`
  - `location_notes`
- `app.book_copies`
  - `institution_unit_id`
  - `campus_id`
  - `location_mapping_status`
  - `location_mapping_confidence`

## 5) Mapping Results

Institution units created:

- `COLLEGE`
- `UNIVERSITY`

Campuses created:

- `COLLEGE_MAIN`
- `UNIVERSITY_ECONOMIC`
- `UNIVERSITY_TECHNOLOGICAL`
- `UNIVERSITY_CENTRAL`

Service points mapped:

- `KSTLIB` -> `UNIVERSITY_CENTRAL`
- `BOOKPOINT_5` (`НБ КазУТБ`) -> `UNIVERSITY_CENTRAL`
- `1` (`Абонемент 1`) -> `UNIVERSITY_ECONOMIC`
- `2` (`Абонемент 2`) -> `UNIVERSITY_TECHNOLOGICAL`
- `3` (`Абонемент 3`) -> `COLLEGE_MAIN`

Storage siglas mapped:

- `KSTLIB` -> `UNIVERSITY_CENTRAL`
- `ab1` -> `UNIVERSITY_ECONOMIC`
- `ab2` -> `UNIVERSITY_TECHNOLOGICAL`
- `Колледж -` -> `COLLEGE_MAIN`

## 6) Was `ab1` / `ab2` / `college` Resolved?

Yes.

Resolution used:

- `ab1` -> `UNIVERSITY_ECONOMIC`
- `ab2` -> `UNIVERSITY_TECHNOLOGICAL`
- `college` signal (`Колледж -`, `Абонемент 3`) -> `COLLEGE_MAIN`

## 7) Counts by Mapped Location

### Copies and distinct documents per campus

- `UNIVERSITY_CENTRAL`: 33879 copies, 5622 distinct documents
- `UNIVERSITY_ECONOMIC`: 7506 copies, 1569 distinct documents
- `UNIVERSITY_TECHNOLOGICAL`: 6084 copies, 1478 distinct documents
- `COLLEGE_MAIN`: 2151 copies, 418 distinct documents

### Readers per campus via reader-service-point mapping

- `UNIVERSITY_CENTRAL`: 2293 readers
- `UNIVERSITY_ECONOMIC`: 996 readers
- `UNIVERSITY_TECHNOLOGICAL`: 983 readers
- `COLLEGE_MAIN`: 982 readers

Note: reader counts are relationship counts through service point access mapping, not mutually exclusive home-campus assignment.

## 8) Ambiguous / Unmapped Rows

Final structural result after mapping:

- `app.location_mapping_candidates`: 0
- `app.book_copies` with non-confirmed location mapping: 0
- `app.reader_service_points` with non-confirmed mapping or location review need: 0

Meaning:

- all currently known structural location signals were mapped without requiring speculative title/subject inference
- no subject-based guessing was used as primary logic

## 9) Sample Rows

### Sample mapped copies

- `legacy_inv_id=3` -> service point `KSTLIB` -> campus `UNIVERSITY_CENTRAL` -> sigla `KSTLIB`
- `legacy_inv_id=13` -> service point `1` -> campus `UNIVERSITY_ECONOMIC` -> sigla `ab1`

### Sample mapped reader service-point relations

Example reader mappings show one reader may have access to multiple service points:

- reader `1/...` -> `KSTLIB` -> `UNIVERSITY_CENTRAL`
- reader `1/...` -> `BOOKPOINT_5` -> `UNIVERSITY_CENTRAL`
- reader `1/...` -> `3` -> `COLLEGE_MAIN`
- reader `1/...` -> `2` -> `UNIVERSITY_TECHNOLOGICAL`
- reader `1/...` -> `1` -> `UNIVERSITY_ECONOMIC`

## 10) Exact Build Commands

From `backend/`:

```bash
npm run build:app-ready-schema
npm run build:app-location-model
```

## 11) Files Added / Updated

- `backend/scripts/legacy-reconstruction/app-location-model.sql`
- `backend/scripts/legacy-reconstruction/build-app-location-model.js`
- `backend/package.json`
- `docs/migration/app-location-model-phase.md`

## 12) Confirmation Existing Layers Remain Intact

Confirmed after build:

- `raw` unchanged
- `core` unchanged as source for backfill/mapping
- `review.quality_issues` unchanged
- `app` extended only

This pass made the app location-aware using a professional hierarchy before further search/admin/circulation work.
