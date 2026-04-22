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
- Time: 2026-04-21 13:37:21 UTC
- Event: post-commit
- Branch: main
- Commit: 9191d40
- Update: Git post-commit on main: chore(vault): session close — refresh CURRENT_STATE to reflect Phase 2b + Phase 3 next-session pointer
- Detail: Changed files: kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/TASK_LOG.md
- Semantic: No app-surface change detected
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phases 0, 1.1, 1.2, 1.4, **2a**, **2b**, plus a small post-audit **stabilization pass** (2026-04-21), and **3.1 — Homepage** (2026-04-22) are complete. The public homepage (`welcome.blade.php`) is now the canonical KazUTB Smart Library port of the Project B Enhanced Homepage export: all sections (hero + search, 3-card bento, operational hours + news, Pulse of Innovation stats card, Institutional Repository asymmetric, guides + direct contact) are live with trilingual ru/kk/en copy; brand is unified to "KazUTB Smart Library" across all locales; the authenticated Member Workspace card routes to `/dashboard` (canonical member shell), not the transitional `/account`. Guest vs. authenticated navbar state is preserved via the existing server-side `session('library.user')` Blade conditional in `partials/navbar.blade.php`. The transitional `/account` route is intentionally **kept in place** with its inline banner; reader `PostLoginRedirect` still targets `/account`, and the cut-over is scheduled for after Phase 3 completes. Next logical phase is **Phase 3.2 — About + Contacts consolidation**.

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
- [ ] Phase 3.2: About + Contacts consolidation — `/about` and `/contacts` both currently render `about.blade.php`; verify consolidation is still desired or split them against their design exports.
- [ ] Phase 3.3: News surfaces — `/news` is currently a 301 redirect to `/`; decide if news returns as a standalone public surface or stays consolidated.
- [ ] Phase 3.4: Resources page refinement — polish `resources.blade.php` for institutional tone.
- [ ] Phase 3.5: Catalog / book detail / discovery polish — reader-facing catalog, `/book/{isbn}`, `/discover`.
- [ ] After Phase 3 completes: retire `/account` (flip reader `$postLoginDestination` default to `/dashboard`, then 301 `/account` → `/dashboard`).

## Next Session Starting Point
1. Re-read `kazutb-library-vault/PROJECT_CONTEXT.md`, this file (`CURRENT_STATE.md`), and `OPEN_QUESTIONS.md` first.
2. Confirm Phase 3.1 shipped on `main` and 81/81 canonical regression tests are green.
3. Begin Phase 3.2 — About + Contacts consolidation. Inspect current `/about` + `/contacts` (both render `about.blade.php` today) against their design exports; decide keep-consolidated vs. split.
4. Do not touch the `/dashboard/*` family, the admin shell, the librarian shell, or the homepage. Do not begin repository backend wiring (that is Phase 4/6).

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
