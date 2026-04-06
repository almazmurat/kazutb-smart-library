# 05 - Agent Working Rules

Canonical product reference: `project-context/98-product-master-context.md`.

## Scope Rules
- This repository is a living product codebase with 12 public routes, internal staff views, and operational APIs.
- Do not run generic blank-project bootstrap workflows.
- Do not overwrite project truth with template assumptions.

## Priority Rules
- Prioritize backend operational depth over frontend expansion.
- Frontend demo layer is mature — do not add pages unless explicitly requested.
- Keep CRM reservation API scope frozen unless user explicitly reopens scope.
- Protect reader UX in library platform.
- Preserve library-side admin/librarian direction in planning decisions.

## Architecture Rules
- Auth: server-side `session('library.user')` Blade conditionals. Do not use JS-based auth detection.
- Middleware: `library.auth` for reader routes, `internal.circulation.staff` for staff routes, `EnsureIntegrationBoundary` for CRM integration.
- Request attributes: use `$request->attributes->get('authenticated_reader')` in reader controllers (set by middleware).
- Catalog: canonical routes are `/api/v1/catalog-db` and `/api/v1/book-db/{isbn}`. Legacy routes deleted.
- CSS: `shell.css` is the shared design system. Use CSS variables, not hardcoded colors. Use utility classes (`.section-head-centered`, `.eyebrow--{color}`, `.heading-xl`, `.text-body`, `.cta-section`).
- Pages: layout-inheriting pages use `@extends('layouts.public')`. Standalone pages (welcome, catalog, book, account, auth) have own `<html>`.
- Docker: build-based deployment, no bind mount. Files must be `docker cp`'d individually. Reset FPM opcache via temp PHP + curl after deploy.

## Change Safety Rules
- Default to minimal changes and verify impact.
- Do not modify runtime/business code when user asks for context/planning-only tasks.
- Keep docs accurate to implemented behavior; avoid speculative claims.
- Test with PHPUnit after backend changes (baseline: 326 tests, 314 pass, 10 pre-existing failures, 2 skipped).

## Prompting Rules for Future Tasks
Every substantial task response should include:
1. stage-aware framing,
2. domain ownership alignment (Library core vs CRM integration),
3. explicit in-scope and out-of-scope boundaries,
4. risks and verification method.

## GSD/Copilot Integration Rules (Soft)
- Use GSD workflows as orchestration support, not as architecture authority.
- If GSD guidance conflicts with project truth here, project truth wins.
- Always read `AGENT_START_HERE.md` first in new sessions.
