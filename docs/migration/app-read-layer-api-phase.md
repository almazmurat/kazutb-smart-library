# App Read Layer API Wiring

Date: 2026-03-20  
Status: implemented and runtime-validated  
Mode: backend integration only; database read layer reused as-is

## Goal

Wire NestJS/public and librarian-facing APIs to the existing app read layer without rebuilding ingestion or changing the underlying schema again.

## Backend Strategy

Prisma schema models do not cover the new `app` views and materialized view, so this pass uses safe parameterized SQL via `PrismaService.$queryRaw`.

Implementation structure:

- shared provider module: `src/modules/read-layer`
- repository: SQL access to `app.catalog_search_mv`, `app.document_detail_v`, `app.document_availability_by_location_v`, `app.location_inventory_summary_v`, `app.catalog_filter_facets_v`, `app.review_queue_v`
- service: response shaping and pagination
- controllers: existing `search` module for public catalog routes, existing `migration` module for librarian review routes

## New / Wired Routes

Public catalog routes:

- `GET /api/v1/search`
  - alias for catalog search
  - reads from `app.catalog_search_mv`

- `GET /api/v1/catalog`
  - catalog search + pagination
  - reads from `app.catalog_search_mv`

- `GET /api/v1/catalog/facets`
  - UI facets for language, campus, service point, availability
  - reads from `app.catalog_filter_facets_v`

- `GET /api/v1/catalog/locations/summary`
  - overall location inventory summary
  - reads from `app.location_inventory_summary_v`

- `GET /api/v1/catalog/:documentId`
  - public document detail
  - reads from `app.document_detail_v`

- `GET /api/v1/catalog/:documentId/availability`
  - per-document availability by location
  - reads from `app.document_availability_by_location_v`

Librarian review routes:

- `GET /api/v1/migration/app-review/issues`
  - paginated review queue
  - reads from `app.review_queue_v`

- `GET /api/v1/migration/app-review/issues/:flagId`
  - issue detail context
  - reads from `app.review_queue_v`
  - enriches with `app.document_detail_v` and `app.document_availability_by_location_v` when a linked document exists

## Query Support

Catalog search supports:

- `q`
- `title`
- `author`
- `isbn`
- `language`
- `institutionUnitCode`
- `campusCode`
- `servicePointCode`
- `availability=all|available|unavailable`
- `minCopies`
- `page`
- `limit`

Review queue supports:

- `entityType`
- `issueCode`
- `severity`
- `campusCode`
- `servicePointCode`
- `page`
- `limit`

Location summary and per-document availability support:

- `institutionUnitCode`
- `campusCode`
- `servicePointCode`

## Validation Commands

From `backend/`:

```bash
npm run build
npm test -- --runInBand
npm run start
```

Additional read-layer build command remains:

```bash
npm run build:app-read-layer
```

## Validation Results

Validated in this pass:

- backend build passed
- backend tests passed: 9 suites, 39 tests
- `npm run start` works after correcting the compiled entrypoint path
- public HTTP smoke tests passed for:
  - `/api/v1/catalog`
  - `/api/v1/catalog/:documentId`
  - `/api/v1/catalog/:documentId/availability`
  - `/api/v1/catalog/facets`
  - `/api/v1/catalog/locations/summary`
- shared read-layer service smoke tests passed for:
  - review queue pagination
  - review issue detail context

## Notes

- Existing legacy/raw/core/review structures are still not exposed to the frontend.
- Existing Prisma catalog models were left intact to avoid unnecessary breakage.
- The backend now has a clean SQL-backed contract for first product screens while the rest of the app can migrate incrementally.
