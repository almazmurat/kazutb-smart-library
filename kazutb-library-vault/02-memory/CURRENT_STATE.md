# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-22 06:10:53 UTC
- Commit: f23721d
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/about.blade.php, routes/web.php
- Commit message: feat(phase-3.2): canonical about + contacts — consolidated KazUTB Smart Library informational surface

## Latest Git Automation
- Time: 2026-04-22 06:10:53 UTC
- Event: post-commit
- Branch: main
- Commit: f23721d
- Update: Git post-commit on main: feat(phase-3.2): canonical about + contacts — consolidated KazUTB Smart Library informational surface
- Detail: Changed files: docs/design-exports/canonical-design-map.md, kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/TASK_LOG.md, resources/views/about.blade.php, routes/web.php, tests/Feature/PublicAboutPageTest.php
- Semantic: UI/Blade view change
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phases 0, 1.1, 1.2, 1.4, **2a**, **2b**, a post-audit **stabilization pass** (2026-04-21), **3.1 — Homepage** (2026-04-22), **3.2 — About + Contacts consolidation** (2026-04-22), and **3.3 — Public News module** (2026-04-22) are complete. Phase 3.3 reverses the previous `/news → /` 301 redirect and restores `/news` as the canonical KazUTB Smart Library public news index (`news/index.blade.php`), plus a new `/news/{slug}` detail surface (`news/show.blade.php`), both extending `layouts.public`. Seeded article data lives in a single `$newsSeedProvider` closure in `routes/web.php` — deliberately DB-replaceable later. Trilingual ru/kk/en parity across chrome and article copy; view-scoped regression guards against "Athenaeum", "Curator Archive", and "KazUTB Digital Library" drift in both news views (layouts.public footer drift still out of scope). The transitional `/account` route is intentionally **kept in place**; reader `PostLoginRedirect` still targets `/account`, and the cut-over is scheduled for after Phase 3 completes. Next logical phase is **Phase 3.4 — Resources page refinement**.

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
- [ ] Phase 3.4: Resources page refinement — polish `resources.blade.php` for institutional tone.
- [ ] Phase 3.5: Catalog / book detail / discovery polish — reader-facing catalog, `/book/{isbn}`, `/discover`.
- [ ] Future backend phase: replace the inline `$newsSeedProvider` closure in `routes/web.php` with a DB-backed news source wired through the admin news CRUD (Phase 6).
- [ ] After Phase 3 completes: retire `/account` (flip reader `$postLoginDestination` default to `/dashboard`, then 301 `/account` → `/dashboard`).
- [ ] Separate out-of-scope item: shared `layouts.public` footer still contains legacy "KazTBU Digital Library" strings — rebrand pass to "KazUTB Smart Library" should be planned as a standalone small commit (not inside Phase 3.3).

## Next Session Starting Point
1. Re-read `kazutb-library-vault/PROJECT_CONTEXT.md`, this file (`CURRENT_STATE.md`), and `OPEN_QUESTIONS.md` first.
2. Confirm Phase 3.3 shipped on `main` and 103/103 canonical regression tests are green (new baseline includes PublicNewsIndexPage 7/7 + PublicNewsDetailPage 7/7).
3. Begin Phase 3.4 — Resources page refinement. Port `docs/design-exports/resources/` into `resources.blade.php` extending `layouts.public` with `activePage='resources'`. Keep the external-resource link-out policy: no direct file downloads; honor the institutional tone.
4. Do not touch the homepage, `/about`, `/contacts`, `/news`, the `/dashboard/*` family, the admin shell, or the librarian shell. Do not begin repository backend wiring (Phase 4/6).

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
