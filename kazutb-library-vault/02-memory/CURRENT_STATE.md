# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-21 10:53:07 UTC
- Commit: 9a6e8b0
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/layouts/librarian.blade.php, resources/views/librarian/overview.blade.php, routes/web.php
- Commit message: feat(phase-1.1): librarian shell + /librarian overview page

## Latest Git Automation
- Time: 2026-04-21 10:53:07 UTC
- Event: post-commit
- Branch: main
- Commit: 9a6e8b0
- Update: Git post-commit on main: feat(phase-1.1): librarian shell + /librarian overview page
- Detail: Changed files: app/Http/Middleware/EnsureLibrarianStaff.php, bootstrap/app.php, docs/design-exports/canonical-design-map.md, docs/design-exports/circulation_desk/code.html, docs/design-exports/circulation_desk/screen.png, docs/design-exports/data_cleanup_stewardship/code.html, docs/design-exports/data_cleanup_stewardship/screen.png, docs/design-exports/librarian_overview/code.html, docs/design-exports/librarian_overview/screen.png, docs/design-exports/scientific_works_moderation_queue/code.html, docs/design-exports/scientific_works_moderation_queue/screen.png, kazutb-library-vault/02-memory/CURRENT_STATE.md
- Semantic: UI/Blade view change
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phase 0 and Phase 1.1 are complete. **Phase 1.2 is complete** — the three remaining librarian screens are now ported to canonical `/librarian/*` routes: `/librarian/circulation` (Circulation Desk), `/librarian/data-cleanup` (Data Stewardship & Cleanup), and `/librarian/repository` (Scientific Works Moderation Queue). All four screens extend `layouts.librarian`; sidebar links now target canonical librarian routes. The `/internal/*` staff pages remain functional for transitional compatibility (no 301 redirects yet — that is Phase 1.4). The other three librarian exports have been flipped from design-ready to implemented — archive-reference in the canonical design map. Admins continue to land on `/admin`; members continue to land on `/account` (interim).

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
