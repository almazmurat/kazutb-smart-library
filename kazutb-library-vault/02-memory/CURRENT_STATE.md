# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-21 13:14:21 UTC
- Commit: 33bc9e6
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/layouts/admin.blade.php, resources/views/layouts/librarian.blade.php, routes/web.php
- Commit message: fix(stabilization): member logout + admin/librarian shell polish

## Latest Git Automation
- Time: 2026-04-21 13:14:21 UTC
- Event: post-commit
- Branch: main
- Commit: 33bc9e6
- Update: Git post-commit on main: fix(stabilization): member logout + admin/librarian shell polish
- Detail: Changed files: kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/TASK_LOG.md, resources/views/layouts/admin.blade.php, resources/views/layouts/librarian.blade.php, routes/web.php, tests/Feature/MemberLogoutTest.php
- Semantic: UI/Blade view change
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phases 0, 1.1, 1.2, 1.4, **2a**, and **2b** are complete, plus a small post-audit **stabilization pass** (2026-04-21). The canonical member-facing shell (`layouts/member.blade.php`) is now wired to **all six** member routes — `/dashboard` (`member.dashboard`), `/dashboard/reservations` (`member.reservations`), `/dashboard/list` (`member.list`), `/dashboard/history` (`member.history`), `/dashboard/notifications` (`member.notifications`), `/dashboard/messages` (`member.messages`) — all gated by `library.auth + member.reader` (ordinary `role='reader'` only; librarian/admin → 403). The sidebar nav no longer shows any "soon" placeholders. Naming choice: the shell label is "Messages" and the export folder is `contact_messages`; implementation follows the shell wording, so the canonical route is `member.messages` at `/dashboard/messages`. The transitional `/account` route is intentionally **kept in place** with a new inline transitional banner pointing readers to `/dashboard`; reader `PostLoginRedirect` still targets `/account`, and a cut-over to `/dashboard` as the default reader landing is the recommended next member-side change (not done yet to avoid disruption). All six member views use representative placeholder data and disclose this in-page until the circulation / shortlist / notification / contact backends are wired up. Next logical phase is Phase 3 — public-facing redesign (homepage, about/contacts, news, resources, catalog / book detail refinement).

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
