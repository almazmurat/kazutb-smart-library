# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-22 07:01:16 UTC
- Commit: 4fa272f
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/layouts/public.blade.php, resources/views/partials/footer.blade.php, routes/web.php
- Commit message: fix(phase-3.3): stabilize public news brand and image assets

## Latest Git Automation
- Time: 2026-04-22 07:01:16 UTC
- Event: post-commit
- Branch: main
- Commit: 4fa272f
- Update: Git post-commit on main: fix(phase-3.3): stabilize public news brand and image assets
- Detail: Changed files: lang/en/ui.php, lang/kk/ui.php, lang/ru/ui.php, resources/views/layouts/public.blade.php, resources/views/partials/footer.blade.php, routes/web.php, tests/Feature/PublicNewsDetailPageTest.php, tests/Feature/PublicNewsIndexPageTest.php
- Semantic: UI/Blade view change
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
Phases 0, 1.1, 1.2, 1.4, **2a**, **2b**, a post-audit **stabilization pass** (2026-04-21), **3.1 — Homepage** (2026-04-22), **3.2 — About + Contacts consolidation** (2026-04-22), and **3.3 — Public News module** (2026-04-22) are complete. Phase 3.3 reverses the previous `/news → /` 301 redirect and restores `/news` as the canonical KazUTB Smart Library public news index (`news/index.blade.php`), plus a new `/news/{slug}` detail surface (`news/show.blade.php`), both extending `layouts.public`. Seeded article data lives in a single `$newsSeedProvider` closure in `routes/web.php` — deliberately DB-replaceable later. Trilingual ru/kk/en parity across chrome and article copy; public brand and local-image stabilization pass shipped in commit `4fa272f`. The transitional `/account` route is intentionally **kept in place**; reader `PostLoginRedirect` still targets `/account`, and the cut-over is scheduled for after Phase 3 completes.

Post-3.3 product-context sync (2026-04-22) expanded planned public scope with: homepage latest-arrivals requirement, standalone leadership and library-rules surfaces, explicit location/wayfinding truth (`1/200`, `1/202`, `1/203`) + map requirement, knowledge-map discovery enhancement (faculty/department current directions), distinct events/calendar module, and preserved collection/fund narrative categories.

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
- [ ] Resolve public route decomposition for newly ratified informational/discovery surfaces: `/leadership`, `/rules`, `/events`, and enhanced location/map presentation strategy.
- [ ] Phase 3.4: Resources page refinement — polish `resources.blade.php` for institutional tone.
- [ ] Phase 3.5: Catalog / book detail / discovery polish — reader-facing catalog, `/book/{isbn}`, `/discover`.
- [ ] Future backend phase: replace the inline `$newsSeedProvider` closure in `routes/web.php` with a DB-backed news source wired through the admin news CRUD (Phase 6).
- [ ] After Phase 3 completes: retire `/account` (flip reader `$postLoginDestination` default to `/dashboard`, then 301 `/account` → `/dashboard`).

## Next Session Starting Point
1. Re-read `kazutb-library-vault/PROJECT_CONTEXT.md`, this file (`CURRENT_STATE.md`), and `OPEN_QUESTIONS.md` first.
2. Confirm Phase 3.3 + stabilization shipped on `main` (`3f244fe`, `4fa272f`) and canonical public regression remains green.
3. Apply the updated public-scope context before any new implementation: latest arrivals, leadership, rules, events, map/location truth, and knowledge-map discovery requirements.
4. Only after route decomposition decisions are clear, begin the next implementation slice (currently expected: Phase 3.4 resources refinement).
5. Do not begin repository backend wiring (Phase 4/6) while public-surface scope decomposition is unresolved.

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
