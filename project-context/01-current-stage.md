# 01 - Current Stage

Canonical product reference: `project-context/98-product-master-context.md`.

## Agreed Stage Assessment
- Whole platform: **advanced prototype, frontend demo-ready, transitioning to operational platform**.
- Library backend core: **early operational core** (catalog, circulation, copy management, review/stewardship APIs implemented).
- Public frontend: **leadership-demo quality** (12 routes, shared design system, teacher/discovery UX, auth-consistent navbar).
- CRM reservation integration slice: **pilot-ready**.

## What Is Strong Today
- Public site: 12 routes with shared design system, premium footer, teacher-facing UX, thematic discovery.
- Auth: server-side session consistency across all pages (Blade `session('library.user')`).
- Integration boundary discipline and request contract handling.
- CRM reservation read and mutate flows (approve/reject) with idempotency and audit behavior.
- Internal health/review diagnostics and operational visibility foundation.
- DB-backed catalog/detail with text search, language/year/availability filters, sort options, URL deep-linking.
- Internal circulation (checkout, return, renewal) with staff middleware.
- Copy management (CRUD, retirement, review workflow) with audit trail.
- Data stewardship APIs: triage, stewardship metrics, reason codes, document/copy/reader review queues.
- Account: summary, loans, reservations, loan renewal for authenticated readers.
- Auth hardening: rate limiting, info-leak prevention, failure logging, integration token allowlist.
- Test suite: 326 tests covering catalog, auth, circulation, integration, review, stewardship.

## What Is Not Yet Production-Grade at Platform Level
- Library-side admin/librarian product UI is not feature-complete (internal views exist but are thin).
- Data stewardship correction workflows are not fully operationalized in the UI.
- Reporting, analytics, and operational dashboards for librarians are underdeveloped.
- Digital materials handling (controlled viewer, access rules) is not implemented.
- Full-text search improvements (suggestions, autocomplete, typo correction) are future work.
- UDK/subject classification data does not exist in the database yet.
- Catalog filtering by subject/category depends on future UDK data ingestion.

## Interpretation Rule for Agents
The frontend demo layer is strong and leadership-ready.
The backend has real operational APIs but needs depth in admin UI, reporting, and data correction workflows.
Do not describe the whole platform as "production-ready" — use the assessment above.
