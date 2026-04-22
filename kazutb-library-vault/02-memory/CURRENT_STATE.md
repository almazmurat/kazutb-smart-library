# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-22 12:36:42 UTC
- Commit: d376c3b
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/about.blade.php
- Commit message: feat(phase-3.b.3): embed location / fund rooms / visit notes into /contacts

## Latest Git Automation
- Time: 2026-04-22 12:36:42 UTC
- Event: post-commit
- Branch: main
- Commit: d376c3b
- Update: Git post-commit on main: feat(phase-3.b.3): embed location / fund rooms / visit notes into /contacts
- Detail: Changed files: kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/TASK_LOG.md, resources/views/about.blade.php, tests/Feature/ContactsLocationFundRoomsTest.php
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
- [x] Public master-plan reconciliation performed (2026-04-22) — public Phase 3 decomposed into five clusters (A–E). See [[DELIVERY_ROADMAP]] Phase 3 for full cluster detail.
- [x] **Cluster A.1: Resources refinement** — ✅ COMPLETED (2026-04-22). Route now injects ExternalResourceService data. Resources.blade.php displays 8 curated external resources (IPR SMART featured, 7 in grid) with dynamic category badges, access types, and external links. Tri-lingual support maintained. Tests updated. Canonical design map marked as implemented.
- [x] **Resources page selective enrichment** — ✅ COMPLETED (2026-04-22). Added tri-lingual "Tailored Pathways" section (For Students / Faculty & Teachers / Researchers) with role-based cards, descriptions, and CTAs. Added "Institutional Subscriptions" label before resource grid. Preserved all existing sections (hero, filter bar, grid, support). CSS: new pathway card styles with gradients, hover effects, responsive grid. HTML: 3 pathway anchors with data-test-id markers, emojis, role-contextual copy. Tests: ResourcesPageTest upgraded 8→11 tests; all pass. Regression: 38 public-page tests pass. Commit ff5c39a.
- [x] **Phase 3-D.1: Homepage Latest Arrivals enrichment** — ✅ COMPLETED (2026-04-22). Welcome.blade.php updated with new "Latest Arrivals / Recent Additions" section placed between stats and repository blocks. Tri-lingual copy (ru/kk/en) + 3 seeded items. Section includes editorial cards with year/material type/author/title/collection metadata, and links to catalog search. 10 new tests added to PublicHomepagePageTest.php verifying: guest/auth access, locale variants, metadata display, placement order. Git committed.
- [x] **Cluster A.4: Book detail selective refinement** — ✅ COMPLETED (2026-04-22). `book.blade.php` refined in-place without route/service changes: clearer bibliographic hierarchy, structured metadata panel, and explicit availability summary block while preserving locale behavior, back-to-catalog flow, shortlist/reservation/digital-actions wiring, and existing API contracts. Tests updated in `BookPageTest.php`; Book and Reader convergence suites pass.
- [x] **Cluster B.4: About — embedded collection profile + institutional directory** — ✅ COMPLETED (2026-04-22). Scope kept tightly inside the existing `/about` variant of `resources/views/about.blade.php` per Cluster B Content Contract v1 §3 §5 §7 §8 §9 — no new route, no new standalone page, no backend change. Two new `data-section` markers added only on the About variant (inserted after the existing `librarian-on-duty` block, before the shared `catalog-cta`): `about-collection-profile` (intro + 4 reader-facing public coverage areas — `technology`, `economy`, `humanities`, `college` — each with icon token, title, short discovery-oriented description, NOT internal stock-accounting language) and `about-institutional-directory` (3 rows linking to `/rules`, `/leadership`, `/contacts`; each row has title, short descriptive line, and a destination CTA routed through `$routeWithLang(...)` so `?lang=kk|en` is preserved). Trilingual ru/kk/en from day one — no silent fallback. Contacts variant is deliberately untouched — neither new marker renders on `/contacts`. Primary navbar unchanged (stays flat at 5 items per contract §8). Scoped CSS added under a new `/* Cluster B.4 */` comment at the tail of `<style>` (`about-collection-grid` 2-column desktop → 1-column <960px, `about-directory-list` stacked rows with hover-lift). New `tests/Feature/AboutCollectionDirectoryTest.php` — 10 tests / 48 assertions — all pass. Regression: `PublicAboutPageTest` 8/8, `ContactsLocationFundRoomsTest` 11/11 — all green. **Cluster B (B.1 Leadership + B.2 Rules + B.3 Contacts embed + B.4 About embed) — closed.**
- [x] **Cluster B.3: Contacts — embedded location / fund rooms / visit notes** — ✅ COMPLETED (2026-04-22). Scope kept tightly inside the existing `/contacts` variant of `resources/views/about.blade.php` per Cluster B Content Contract v1 §3 §6 — no new route, no standalone location page, no backend change, no external map provider. Three new `data-section` markers added only on the Contacts variant: `contacts-location` (branch card + address + entrance + floor note + static v1 map placeholder + "Get directions" CTA to google.com/maps/search with URL-encoded address, opens in new tab with `rel="noopener noreferrer"`), `contacts-fund-rooms` (frozen v1 room set only — `1/200` технологический фонд, `1/202` фонд колледжа, `1/203` экономический фонд библиотеки; each card has `room`/`fund_label`/`branch`/`floor`/`short_description`/`access_note`), and `contacts-visit-notes` (ID, quiet zones, accessibility + CTA to `/rules` with `?lang` preserved). Trilingual ru/kk/en from day one. Existing Contacts chrome kept intact (hero `contacts-summary` aside, `librarian-on-duty` auth-aware block, real address/phone/email, Opening hours, `catalog-cta`). About variant is deliberately untouched. New `tests/Feature/ContactsLocationFundRoomsTest.php` — 11 tests / 59 assertions — all pass. Regression: `PublicAboutPageTest` 9/9, `LeadershipPageTest` 14/14, `RulesPageTest` 15/15 — all green.
- [x] **Cluster B.2: Public `/rules` page** — ✅ COMPLETED (2026-04-22). New standalone public surface at `/rules` per frozen Cluster B Content Contract v1. Route + `$rulesSeedProvider` closure in `routes/web.php` (DB-replaceable seed pattern mirroring `$leadershipSeedProvider`). View `resources/views/rules.blade.php` extends `layouts.public` and implements the frozen section tree (`rules-header`, `rules-toc`, `rules-general` `#general`, `rules-borrowing` `#borrowing`, `rules-digital-access` `#digital`, `rules-conduct` `#conduct`, `rules-penalties` `#penalties`, `rules-footer-meta`). Anchor IDs preserved as public contract. Sticky TOC on md+ degrading to a jump list on mobile; full rules content readable on first load (no accordions). Trilingual ru/kk/en with a bilingual title pattern in the header. Header carries `effective_date` (2026-04-01) and `last_reviewed_at` (2026-04-22) both rendered as `<time>` elements. Borrowing section is audience-grouped (Undergraduate / Master's & doctoral / Faculty & research). Digital access section carries the no-download + SSO-only + no-credential-sharing policy. Penalties section adds a numbered access-suspension ladder (Reminder → Temporary hold → Escalation) and a right-of-appeal block routing to `/leadership` + `/contacts`. Footer-meta CTA targets `/contacts` and `/leadership` with `?lang` preservation. Footer minimally extended with a single "Правила библиотеки / Кітапхана ережелері / Library Rules" link added after Leadership in the Navigation column; primary navbar unchanged (stays flat at 5 items per contract §8). New `tests/Feature/RulesPageTest.php` — 15 tests / 89 assertions — all pass.
- [x] **Cluster B.1: Public `/leadership` page** — ✅ COMPLETED (2026-04-22). New standalone public surface at `/leadership` per frozen Cluster B Content Contract v1. Route + `$leadershipSeedProvider` closure in `routes/web.php` (DB-replaceable seed pattern mirroring `$newsSeedProvider`). View `resources/views/leadership.blade.php` extends `layouts.public` and implements the 4 frozen sections (`leadership-header`, `leadership-mandate`, `leadership-directory`, `leadership-support-cta`) with role-first trilingual content and no invented personal names (v1 ships 3 role slots: director, digital-collections, reader-services; portraits null → initial-letter fallback; no external CDN URLs). Support CTA points to `/contacts` with `?lang` preservation. Footer minimally extended with a single "Руководство / Басшылық / Leadership" link in the Navigation column; primary navbar unchanged (stays flat at 5 items per contract §8). New `tests/Feature/LeadershipPageTest.php` — 14 tests / 64 assertions — all pass.
- [ ] Cluster A.2 and A.5: Discover / Shortlist refinement (next)
- [ ] Cluster C: Events module `/events`, `/events/{slug}` (needs Stitch — no canonical export yet).
- [ ] Cluster D: Latest arrivals block on homepage (decide data source first — see OPEN_QUESTIONS).
- [ ] Cluster E: Faculty/department knowledge-map layer in discover/catalog (additive, alongside Cluster A).
- [ ] Future backend phase: replace `$newsSeedProvider` closure with DB-backed news source (Phase 6).
- [ ] After Phase 3 completes: retire `/account` → flip `$postLoginDestination` reader default to `/dashboard`, then 301.

## Next Session Starting Point
1. Re-read `kazutb-library-vault/PROJECT_CONTEXT.md`, this file (`CURRENT_STATE.md`), and `OPEN_QUESTIONS.md` first.
2. Confirm Phase 3.3 + stabilization + public-reconciliation shipped on `main`.
3. **Public phase is now cluster-decomposed — do NOT revert to a linear single-page sequence.**
4. Begin Cluster A.1: Resources refinement. Use `docs/design-exports/resources` as the canonical export anchor.
5. Do not begin repository backend wiring (Phase 4/6) while public-cluster execution is in progress.

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
