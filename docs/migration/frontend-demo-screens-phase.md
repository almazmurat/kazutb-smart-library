# Frontend Demo Screens on App Read Layer

Date: 2026-03-20  
Status: implemented and build-validated

## Scope

This pass delivers the first demo-ready React screens on top of the new app read-layer APIs:

- public catalog search page
- search results page
- book detail page with location availability
- librarian review/issues page

No database redesign or ingestion rerun was performed.

## Routes

- `/search`:
  - search entry page with query + language + campus + availability controls
  - submits to `/catalog` with URL query params

- `/catalog`:
  - catalog results list using `GET /api/v1/catalog`
  - filters: q, title, author, isbn, campusCode, servicePointCode, language, availability
  - pagination controls

- `/books/:id`:
  - detail page using `GET /api/v1/catalog/:documentId`
  - availability block using `GET /api/v1/catalog/:documentId/availability`

- `/librarian`:
  - librarian review queue using `GET /api/v1/migration/app-review/issues`
  - detail drawer block using `GET /api/v1/migration/app-review/issues/:flagId`
  - filters: severity, issueCode, entityType, campusCode, servicePointCode

## Frontend Integration Points

Shared API client and types are wired in:

- `frontend/src/features/catalog/api/public-catalog-api.ts`

Shared hooks:

- `frontend/src/features/catalog/hooks/use-public-catalog.ts`

Main screen components:

- `frontend/src/features/search/pages/search-page.tsx`
- `frontend/src/features/catalog/pages/catalog-page.tsx`
- `frontend/src/features/book/pages/book-details-page.tsx`
- `frontend/src/features/librarian/pages/librarian-queue-page.tsx`

## Demo User Flows

1. Public search flow

- Open `/search`
- Enter query and optional filters
- Submit to `/catalog`
- Open a result card to `/books/:id`

2. Detail and availability flow

- On `/books/:id`, review metadata summary
- Review copy totals and location-level availability rows by campus/service point

3. Librarian review flow

- Open `/librarian`
- Filter by severity/issue/entity/location
- Open issue detail by flag ID context panel

## Validation

From `frontend/`:

```bash
npm run build
```

Result: passed.

`npm run lint` currently fails because the project has no ESLint configuration file committed.

## Notes

- The UI intentionally consumes only app-facing endpoints; no raw/core schema objects are used by frontend pages.
- `frontend/src/shared/auth/auth-store.ts` is set to a demo librarian role so protected librarian screens can be shown before JWT login wiring.
