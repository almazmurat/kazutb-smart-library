# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-21 13:30:04 UTC
- Commit: 069b71f
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/account.blade.php, resources/views/layouts/member.blade.php, resources/views/member/history.blade.php, resources/views/member/messages.blade.php, resources/views/member/notifications.blade.php, routes/web.php
- Commit message: feat(phase-2b): complete canonical member module — history/notifications/messages + /account transitional banner

## Latest Git Automation
- Time: 2026-04-21 13:30:04 UTC
- Event: post-commit
- Branch: main
- Commit: 069b71f
- Update: Git post-commit on main: feat(phase-2b): complete canonical member module — history/notifications/messages + /account transitional banner
- Detail: Changed files: docs/design-exports/canonical-design-map.md, docs/design-exports/contact_messages/code.html, docs/design-exports/contact_messages/screen.png, docs/design-exports/my_borrowing_history/code.html, docs/design-exports/my_borrowing_history/screen.png, docs/design-exports/notifications/code.html, docs/design-exports/notifications/screen.png, kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/TASK_LOG.md, resources/views/account.blade.php, resources/views/layouts/member.blade.php, resources/views/member/history.blade.php
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
- Nothing currently in flight. The shell-normalization arc (Phases 0 → 2b) is closed.
- Next phase (not yet started): **Phase 3 — public-facing surface completion and redesign** (homepage, about + contacts, news surfaces, resources refinement, catalog / book detail / discovery polish).

## Active Blockers
- None blocking Phase 3 kickoff.
- Standing constraints (unchanged): admin pages still render hard-coded demo data until Phase 6 backend wiring; scientific repository module (Phase 4) has no routes/views/schema yet; `/account` is kept as transitional with an inline banner and is scheduled for retirement after Phase 3 (flip `$postLoginDestination` → `/dashboard`, then 301 `/account` → `/dashboard`).

## Immediate Next Actions
- [ ] Phase 3 kickoff — inspect public-facing design exports under `docs/design-exports/` (Homepage, Catalog, Book Details, Resources are listed as Project B "clean missing screens" in canonical-design-map.md; About/Discover/Login are already marked implemented).
- [ ] Phase 3.1: Homepage redesign — port the Project B Homepage export to `welcome.blade.php` in KazUTB tone; preserve guest navbar behaviour and existing auth-aware Blade conditional.
- [ ] Phase 3.2: About + Contacts consolidation — the `/about` and `/contacts` routes both currently render `about.blade.php`; verify consolidation is still desired or split them.
- [ ] Phase 3.3: News surfaces — `/news` is currently a 301 redirect to `/`; decide if news returns as a standalone public surface or stays consolidated.
- [ ] Phase 3.4: Resources page refinement — polish `resources.blade.php` for institutional tone.
- [ ] Phase 3.5: Catalog / book detail / discovery polish — reader-facing catalog, `/book/{isbn}`, `/discover`.
- [ ] After Phase 3 completes: retire `/account` (flip reader `$postLoginDestination` default to `/dashboard`, then 301 `/account` → `/dashboard`).

## Next Session Starting Point
1. Re-read `kazutb-library-vault/PROJECT_CONTEXT.md`, this file (`CURRENT_STATE.md`), and `OPEN_QUESTIONS.md` first.
2. Confirm Phase 2b shipped: commit `069b71f` on `main` (pushed to `origin/main`); 74/74 tests green.
3. Begin Phase 3.1 — Homepage redesign. Inspect `docs/design-exports/Enhanced Homepage/` (or the canonical Project B homepage export, whichever is the current source of truth per `canonical-design-map.md`) before touching `welcome.blade.php`.
4. Do not touch the `/dashboard/*` family, the admin shell, or the librarian shell. Do not begin repository backend wiring (that is Phase 4/6).

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
