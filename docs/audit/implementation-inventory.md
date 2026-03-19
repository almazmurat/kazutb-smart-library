# Implementation Inventory Audit (2026-03-19)

## Method

This inventory is evidence-based from repository code, docs, and executable checks on this branch:

- backend tests: 9/9 suites passing (39 tests)
- backend build: passing
- frontend build: passing

No classification below is inferred from roadmap intent alone.

## Status Scale

- IMPLEMENTED
- MOSTLY IMPLEMENTED
- PARTIALLY IMPLEMENTED
- SCAFFOLDED ONLY
- NOT IMPLEMENTED

## Repository-Wide Inventory

| Area | Status | Evidence | Reality Note |
| --- | --- | --- | --- |
| Foundation / project structure | MOSTLY IMPLEMENTED | `backend/`, `frontend/`, `docs/`, `migration/`, `infrastructure/`; runnable apps; moduleized Nest app in `backend/src/app.module.ts` | Structure is strong, but several domain modules are still shallow.
| Auth / roles / access control | MOSTLY IMPLEMENTED | Global JWT + role guards in `backend/src/app.module.ts`; `backend/src/modules/auth/auth.controller.ts`; role checks across controllers | LDAP is still dev-mock only (`backend/src/modules/auth/auth.service.ts`), no logout/token invalidation flow.
| Public catalog | IMPLEMENTED | Public endpoints `GET /api/v1/public/books|:id|filters` in `backend/src/modules/books/public-books.controller.ts`; functional pages in `frontend/src/features/catalog/pages/catalog-page.tsx` and `frontend/src/features/book/pages/book-details-page.tsx` | Public browsing and details are real and connected.
| Search | SCAFFOLDED ONLY | `backend/src/modules/search/search.service.ts` returns `[]`; `frontend/src/features/search/pages/search-page.tsx` redirects to `/catalog` | Search route exists but no search behavior implemented.
| Book details | IMPLEMENTED | Details page loads real public book details and reservation action in `frontend/src/features/book/pages/book-details-page.tsx` | Operational for metadata and reservation initiation.
| Reservations | IMPLEMENTED | Full reservation logic in `backend/src/modules/reservations/reservations.service.ts`; queue and cabinet pages in `frontend/src/features/librarian/pages/librarian-queue-page.tsx` and `frontend/src/features/cabinet/pages/cabinet-reservations-page.tsx` | Role scope and status transitions are present.
| Circulation | IMPLEMENTED | Issue/return/list logic in `backend/src/modules/circulation/circulation.service.ts`; circulation desk page in `frontend/src/features/circulation/pages/circulation-page.tsx` | Includes branch restrictions and overdue handling.
| Analytics | IMPLEMENTED | KPI/popularity/activity in `backend/src/modules/analytics/analytics.service.ts`; dashboard page in `frontend/src/features/analytics/pages/analytics-page.tsx` | Table/card analytics implemented; no advanced visualization.
| Reports | IMPLEMENTED | Year/month and branch summary in `backend/src/modules/reports/reports.service.ts`; UI in `frontend/src/features/reports/pages/reports-page.tsx` | Functional reporting summary, but no export or scheduling depth.
| Admin / catalog management | PARTIALLY IMPLEMENTED | Backend admin/user/catalog endpoints exist (`backend/src/modules/users/users.controller.ts`, `backend/src/modules/books/books.controller.ts`) | Frontend management pages are placeholders with scaffold note in `frontend/src/features/catalog-management/pages/*.tsx` and `frontend/src/features/admin/pages/admin-page.tsx`.
| Multilingual support | MOSTLY IMPLEMENTED | Dictionary and locales in `frontend/src/shared/i18n/dictionary.ts`, `frontend/src/shared/i18n/locales/*.ts` | Core UI is localized; completeness/consistency of every new string still requires continuous review.
| Styling / design system | PARTIALLY IMPLEMENTED | Shared utility classes and theme tokens in `frontend/src/app/styles.css`; consistent shell in `frontend/src/app/shell.tsx` | Coherent baseline exists, but many internal operational pages still feel generic/table-centric.
| Legacy DB discovery artifacts | IMPLEMENTED | Artifact set in `docs/legacy-db-discovery/` (CSV/SQL/notes), including row counts/FKs/sample view | Discovery data is committed and reusable, but includes only sampled/derived artifacts, not live source integration.
| Migration readiness docs | PARTIALLY IMPLEMENTED | Plans/specs in `docs/migration/*.md` and discovery docs | Documentation quality is good, but some statements are stale/contradictory (e.g., legacy `.bak` handling note vs present file).
| Data Quality Workbench | PARTIALLY IMPLEMENTED | Backend review API and persistence in `backend/src/modules/migration/migration.controller.ts` + `migration.service.ts`; UI in `frontend/src/features/data-quality/pages/data-quality-workbench-page.tsx` | Real and useful for triage, but detection is artifact-derived only; no import/remediation engine.
| Backend API coverage | PARTIALLY IMPLEMENTED | 17 controllers present under `backend/src/modules/**/*controller.ts` | Coverage breadth is high, but depth uneven: search/files/settings/migration list are shallow.
| Testing coverage | PARTIALLY IMPLEMENTED | Backend Jest specs in 9 files (`backend/src/**/*.spec.ts`) | No frontend unit tests and no E2E tests found.
| Documentation quality | PARTIALLY IMPLEMENTED | Rich architecture and migration docs in `docs/` | Some docs are outdated against implemented state (notably `docs/roadmap.md` and parts of `docs/api/README.md`).
| Operational readiness | PARTIALLY IMPLEMENTED | Security/config plumbing in `backend/src/main.ts` (helmet, CORS, validation, Swagger dev) | Runtime hardening baseline exists, but deployment/runbook/monitoring/CI evidence is limited in repo.

## Cross-Cutting Reality Checks

1. Surface area is broad, but maturity is uneven.
2. Core circulation/reservation/catalog-read paths are real and integrated.
3. Search, files, settings, and admin/catalog-management UI are not production-depth.
4. Migration workbench is currently a staged review layer on deterministic artifact parsing, not a live migration pipeline.
5. Backend test confidence is moderate for covered modules, but frontend regression risk is high due to zero test harness.

## Key Doc-to-Code Mismatches

1. `docs/roadmap.md` still describes "Phase 0 (CURRENT)" despite substantial implemented runtime features.
2. `docs/api/README.md` documents search/autocomplete/similar endpoints and file capabilities not present in code.
3. `docs/legacy-db-discovery/README.md` says `.bak` is excluded, while `docs/legacy-db-discovery/marc_full.bak` currently exists in the repository state.
