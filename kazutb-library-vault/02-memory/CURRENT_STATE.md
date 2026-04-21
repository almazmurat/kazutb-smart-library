# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-21 12:36:43 UTC
- Commit: 8ec69ff
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/layouts/member.blade.php, resources/views/member/dashboard.blade.php, resources/views/member/list.blade.php, resources/views/member/reservations.blade.php, routes/web.php
- Commit message: feat(phase-2a): canonical member shell + /dashboard overview / reservations / list

## Latest Git Automation
- Time: 2026-04-21 12:36:43 UTC
- Event: post-commit
- Branch: main
- Commit: 8ec69ff
- Update: Git post-commit on main: feat(phase-2a): canonical member shell + /dashboard overview / reservations / list
- Detail: Changed files: app/Http/Middleware/EnsureMemberReader.php, bootstrap/app.php, docs/design-exports/canonical-design-map.md, kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/TASK_LOG.md, resources/views/layouts/member.blade.php, resources/views/member/dashboard.blade.php, resources/views/member/list.blade.php, resources/views/member/reservations.blade.php, routes/web.php, tests/Feature/MemberDashboardPageTest.php, tests/Feature/MemberListPageTest.php
- Semantic: UI/Blade view change
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phases 0, 1.1, 1.2, 1.4, and **2a** are complete, followed by a small **post-audit stabilization pass** (2026-04-21): member sign-out now works via a new `POST /logout` web route (form + CSRF, session cleared + redirect to `/login`); admin sidebar CTA relabelled "Post Announcement" and cross-shell shortcut relabelled "Librarian Console →"; librarian sidebar disabled items now use the member shell's `aria-disabled` + "soon" micro-label pattern; fake librarian notification dot removed. Admin/librarian JS-based logout (`fetch('/api/v1/logout')`) left unchanged. The canonical member-facing shell is live: `layouts/member.blade.php` is reused by three routes — `/dashboard` (name `member.dashboard`), `/dashboard/reservations` (name `member.reservations`), and `/dashboard/list` (name `member.list`). The `member.reader` middleware (backed by `EnsureMemberReader`) gates the `/dashboard/*` family to ordinary users (`role='reader'`); librarians and admins receive `403`. The transitional `/account` route is intentionally left untouched — reader `PostLoginRedirect` still lands on `/account`, and migration to `/dashboard` will happen only once feature parity is verified. Librarian surfaces continue as before. Next logical phase is Phase 2b — member notifications / contact / history surfaces.

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
