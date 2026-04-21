# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-21 07:51:51 UTC
- Commit: 9c97e4e
- Branch: main
- Change type: UI/Blade view change — ADMIN PANEL
- Files: resources/views/admin/reports.blade.php, routes/web.php
- Commit message: feat: implement reports & analytics admin page (/admin/reports)

## Latest Git Automation
- Time: 2026-04-21 08:19:52 UTC
- Event: post-checkout
- Branch: main
- Commit: 9c97e4e
- Update: Branch switch
- Detail: From: main To: main
- Semantic: No app-surface change detected
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phase 0 (Architecture Normalization) is complete. Role-based post-login redirect now routes admin → `/admin`, librarian → `/internal/dashboard` (interim), members → `/account` (interim); the `/internal/*` route group runs under `library.auth` middleware; the obsolete `athenaeum_digital` design export has been purged; canonical-design-map marks all seven admin surfaces as implemented archive-reference; `PostLoginRedirectTest` covers the redirect contract (4 passing). The next delivery focus is Phase 1 of [[DELIVERY_ROADMAP]] — the librarian shell Stitch cycle and migration of `/internal/*` to canonical `/librarian/*`.

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
- [ ] Phase 1.1: Stitch export of `Librarian Operations Shell` (sidebar nav mirroring `layouts.admin`)
- [ ] Phase 1.2: Create `resources/views/layouts/librarian.blade.php`
- [ ] Phase 1.3: Create `EnsureLibrarianStaff` middleware (accepts `librarian` or `admin`)
- [ ] Phase 1.4: Mount `/librarian/*` route group; migrate `/internal/*` pages with 301 redirects
- [ ] Phase 1.5: Update post-login redirect: librarian → `/librarian` (drop `/internal/dashboard` interim)

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
