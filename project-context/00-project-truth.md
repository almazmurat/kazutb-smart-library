# 00 - Project Truth

Deep-dive reference: `project-context/99-master-project-context.md`.

## Product Identity
- Project: "Цифровая умная библиотека KazUTB".
- Type: evolving library platform with integration boundary for CRM.
- Current repo: live codebase, not a blank/new-project scaffold.

## Architecture Truth
- Backend: Laravel + PostgreSQL.
- Frontend/UI: Blade-driven pages for public and internal views.
- Data landscape includes:
  - library-side app schema/read layer,
  - legacy/integration-facing public reservation tables,
  - review/data-quality layer.

## Domain Ownership
- Library platform is the source of product direction and long-term domain ownership.
- CRM role is bounded:
  - identity/auth provider,
  - external/admin integration client,
  - reservation integration consumer.

## UX/Product Truth
- Reader UX stays in library platform.
- Internal library operations (dashboard/review/circulation workflows) are first-class platform concerns.
- Library-side admin/librarian workspace is strategic and must not be de-scoped.

## Integration Truth
- CRM reservation API v1 exists with boundary enforcement and mutate safety controls.
- Scope is intentionally constrained and currently frozen beyond existing v1 behavior.

## Delivery Truth
- Current need is platform convergence and runtime confidence, not rapid API surface expansion.
- Context/governance docs must reflect implemented reality, not generic templates.
