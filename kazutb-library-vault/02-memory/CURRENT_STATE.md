# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-22 05:51:26 UTC
- Commit: 7d7e137
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/welcome.blade.php
- Commit message: feat(phase-3.1): canonical homepage — KazUTB Smart Library brand + /dashboard workspace routing

## Latest Git Automation
- Time: 2026-04-22 05:51:26 UTC
- Event: post-commit
- Branch: main
- Commit: 7d7e137
- Update: Git post-commit on main: feat(phase-3.1): canonical homepage — KazUTB Smart Library brand + /dashboard workspace routing
- Detail: Changed files: docs/design-exports/canonical-design-map.md, kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/TASK_LOG.md, resources/views/welcome.blade.php, tests/Feature/PublicHomepagePageTest.php
- Semantic: UI/Blade view change
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phases 0, 1.1, 1.2, 1.4, **2a**, **2b**, a post-audit **stabilization pass** (2026-04-21), **3.1 — Homepage** (2026-04-22), and **3.2 — About + Contacts consolidation** (2026-04-22) are complete. `/about` and `/contacts` now both render a single canonical KazUTB Smart Library informational surface (`about.blade.php`) with section ordering driven by `$activePage` — contacts-first on `/contacts`, mission-first on `/about`. A new `Librarian-on-Duty` block routes authenticated readers to `/dashboard` and guests to `/login`, reusing the same server-side `session('library.user')` Blade conditional pattern as the navbar. Brand unified to "KazUTB Smart Library" in all three locales (ru/kk/en); view-scoped regression asserts no legacy brand drift in `about.blade.php` (shared layout footer still carries legacy strings — out of scope here). The transitional `/account` route is intentionally **kept in place**; reader `PostLoginRedirect` still targets `/account`, and the cut-over is scheduled for after Phase 3 completes. Next logical phase is **Phase 3.3 — News surfaces** (`/news` currently 301 → `/`; decision needed on whether to restore a standalone public surface).

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
- [ ] Phase 3.3: News surfaces — `/news` is currently a 301 redirect to `/`; decide if news returns as a standalone public surface or stays consolidated. See OPEN_QUESTIONS.md (MED-priority).
- [ ] Phase 3.4: Resources page refinement — polish `resources.blade.php` for institutional tone.
- [ ] Phase 3.5: Catalog / book detail / discovery polish — reader-facing catalog, `/book/{isbn}`, `/discover`.
- [ ] After Phase 3 completes: retire `/account` (flip reader `$postLoginDestination` default to `/dashboard`, then 301 `/account` → `/dashboard`).
- [ ] Separate out-of-scope item: shared `layouts.public` footer still contains legacy "KazTBU Digital Library" strings — rebrand pass to "KazUTB Smart Library" should be planned as a standalone small commit (not inside Phase 3.3).

## Next Session Starting Point
1. Re-read `kazutb-library-vault/PROJECT_CONTEXT.md`, this file (`CURRENT_STATE.md`), and `OPEN_QUESTIONS.md` first.
2. Confirm Phase 3.2 shipped on `main` and 89/89 canonical regression tests are green (new baseline includes PublicAboutPage 8/8).
3. Begin Phase 3.3 — News surfaces. First answer the open question: should `/news` remain a 301 to `/` or become a standalone public surface? Choose scope before touching routes/views.
4. Do not touch the homepage, `/about`, `/contacts`, the `/dashboard/*` family, the admin shell, or the librarian shell. Do not begin repository backend wiring (Phase 4/6).

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
