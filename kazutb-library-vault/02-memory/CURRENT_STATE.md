# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-21 11:16:16 UTC
- Commit: 74e3531
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/layouts/librarian.blade.php, resources/views/librarian/circulation.blade.php, resources/views/librarian/data-cleanup.blade.php, resources/views/librarian/repository.blade.php, routes/web.php
- Commit message: feat(phase-1.2): canonical librarian circulation / data-cleanup / repository

## Latest Git Automation
- Time: 2026-04-21 11:16:16 UTC
- Event: post-commit
- Branch: main
- Commit: 74e3531
- Update: Git post-commit on main: feat(phase-1.2): canonical librarian circulation / data-cleanup / repository
- Detail: Changed files: docs/design-exports/canonical-design-map.md, kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/TASK_LOG.md, resources/views/layouts/librarian.blade.php, resources/views/librarian/circulation.blade.php, resources/views/librarian/data-cleanup.blade.php, resources/views/librarian/repository.blade.php, routes/web.php, tests/Feature/LibrarianCirculationPageTest.php, tests/Feature/LibrarianDataCleanupPageTest.php, tests/Feature/LibrarianRepositoryPageTest.php
- Semantic: UI/Blade view change
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phases 0, 1.1, 1.2, and **1.4** are complete. All four canonical `/librarian/*` screens are live (`/librarian`, `/librarian/circulation`, `/librarian/data-cleanup`, `/librarian/repository`) and extend `layouts.librarian`. The transitional `/internal/*` layer has been compressed: `/internal/dashboard`, `/internal/circulation`, and `/internal/stewardship` now return `301` permanent redirects to their canonical `/librarian/*` counterparts. `/internal/review` (Quality Issues Overview) and `/internal/ai-chat` (experimental staff AI assistant) remain functional under `library.auth` because neither has a confirmed canonical `/librarian/*` destination in the roadmap. Active UI (admin sidebar, librarian overview snapshot CTAs, librarian circulation "View All") now points exclusively at `/librarian/*`. Admins continue to land on `/admin`; members continue to land on `/account` (interim). Next logical phase is Phase 2 — Member Dashboard Decomposition (`/account` → canonical `/dashboard/*`).

## What Is Done
- Canonical product truth is consolidated in [[PROJECT_CONTEXT]]
- Library-centered Laravel and PostgreSQL architecture is established
- Public homepage, secure access login, and member dashboard are already implemented as anchor surfaces
- CRM-backed authentication remains library-hosted and conservative
- Public catalog, availability, shortlist direction, and internal stewardship foundations are in place
- Admin shell fully implemented: overview, users, logs, news, feedback, reports, settings (commit 9c97e4e)
- Comprehensive architecture audit completed (2026-04-21); hybrid shell model ratified per [[DECISIONS]]
- [[DELIVERY_ROADMAP]] created — nine phases from Phase 0 normalization to Phase 8 production hardening
- Phase 0 executed: role-based login redirect, `/internal/*` under `library.auth`, `athenaeum_digital` purged, canonical-design-map updated, `PostLoginRedirectTest` added (4 tests, 8 assertions passing; AdminOverviewPageTest 10/10 still green)
- The vault itself has been rebuilt into a clean canonical structure

## What Is In Progress
- Phase 1 planning: Stitch export of canonical `Librarian Operations Shell`, `layouts.librarian`, new `librarian.staff` middleware, migration of `/internal/*` → `/librarian/*` with 301 redirects
- Member dashboard decomposition from single `/account` page to canonical `/dashboard/*` multi-route (Phase 2)

## Active Blockers
- `/internal/*` still lives outside canonical `/librarian/*` namespace; rename is Phase 1
- Admin pages render hard-coded demo data; real data wiring is Phase 6
- Scientific repository module has no routes, views, or schema yet (Phase 4)
- Legacy data quality remains uneven after migration from MARC-SQL
- CRM mirroring boundaries must stay conservative so the library remains the operational owner

## Immediate Next Actions
- [ ] Phase 1.4a: Port `docs/design-exports/circulation_desk/` → `resources/views/librarian/circulation.blade.php` + mount `/librarian/circulation`
- [ ] Phase 1.4b: Port `docs/design-exports/data_cleanup_stewardship/` → `resources/views/librarian/data-cleanup.blade.php` + mount `/librarian/data-cleanup`
- [ ] Phase 1.4c: Port `docs/design-exports/scientific_works_moderation_queue/` → `resources/views/librarian/repository.blade.php` + mount `/librarian/repository` (or merge into Phase 4)
- [ ] Phase 1.4d: Add 301 redirects from every `/internal/*` path to the new `/librarian/*` path; update sidebar hrefs in `layouts.librarian` accordingly
- [ ] Phase 1.5: Remove `/internal/*` interim code paths once 301s are in place and no callers remain

## Known Technical Debt
- Post-migration metadata correction and duplicate cleanup remain ongoing
- Controlled viewer protections are architected but still need deeper product implementation
- Analytics and export depth need richer operational dashboards
- Contract-aware management of licensed external resources is future-ready but not yet complete


## Links
- [[PROJECT_CONTEXT]]
- [[DECISIONS]]
- [[OPEN_QUESTIONS]]
- [[TASK_LOG]]
- [[GRAPH_INDEX]]
