# 00 - Project Truth

Canonical product reference: `project-context/98-product-master-context.md`.

## Product Identity
- Project: "Цифровая умная библиотека KazUTB".
- Type: evolving library platform with integration boundary for CRM.
- Current repo: live codebase with 12 public routes, internal staff views, and operational API surface.

## Architecture Truth
- Backend: Laravel 13 + PHP 8.3 + PostgreSQL.
- Frontend/UI: Blade-driven pages for public and internal views, shared design system (shell.css).
- Public pages: standalone (welcome, catalog, book, account, auth) and layout-inheriting (services, resources, about, contacts, news, for-teachers, discover).
- Internal views: dashboard, review, stewardship, circulation, AI chat.
- Data landscape includes:
  - library-side app schema/read layer (documents, copies, authors, publishers, readers, loans),
  - legacy/integration-facing public reservation tables,
  - review/data-quality layer (quality issues, triage, stewardship metrics).

## Domain Ownership
- Library platform is the source of product direction and long-term domain ownership.
- CRM role is bounded:
  - identity/auth provider (LDAP/AD proxy),
  - external/admin integration client,
  - reservation integration consumer.

## UX/Product Truth
- Reader UX stays in library platform.
- Internal library operations (dashboard/review/circulation/stewardship workflows) are first-class platform concerns.
- Library-side admin/librarian workspace is strategic and must not be de-scoped.
- Public frontend is leadership-demo-quality with teacher-facing and thematic discovery layers.

## Integration Truth
- CRM reservation API v1 exists with boundary enforcement and mutate safety controls.
- Scope is intentionally constrained and currently frozen beyond existing v1 behavior.

## Delivery Truth
- Frontend demo layer is mature — focus now shifts to backend operational depth.
- Context/governance docs must reflect implemented reality, not generic templates.
- Auth is server-side session-based (`session('library.user')`), consistent across all pages.
