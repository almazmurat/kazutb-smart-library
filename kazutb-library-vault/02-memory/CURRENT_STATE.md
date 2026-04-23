# Current State — KazUTB Library Platform
> Last updated: 2026-04-24

## Last changed (Visual QA correction pass round 2)
- Time: 2026-04-24 UTC
- Commit: 647eab3
- Branch: main
- Change type: Visual QA correction pass (CSS + HTML structural fix)
- Files: resources/views/contacts.blade.php, resources/views/leadership.blade.php, resources/views/news/show.blade.php, resources/views/events/show.blade.php, resources/views/rules.blade.php
- Summary: Second-stage visual hardening pass across canonical public layer. Key correctness fix: contacts.blade.php hero cross-column baseline alignment defect resolved (hero moved from standalone full-width section into left grid column as .contacts-canonical__hero-inline, col-left-wrap flex wrapper added, align-items:start on 1024px grid breakpoint). Leadership: header padding-top 48→72px, mandate-meta padding strengthened + box-shadow, CTA card padding strengthened + box-shadow. news/show.blade.php sidebar and events/show.blade.php aside: position:sticky top:96px added at 1024px breakpoint. rules.blade.php: header padding-top 48→64px. Priority 2/3 pages (discover, welcome, about, resources, news/index, events/index) inspected and left unchanged as already structurally solid. All 209 public tests pass (974 assertions). No pre-existing failures introduced.

## Last changed (Phase 3.h — homepage canonical-exact rebuild)
- Time: 2026-04-24 UTC
- Branch: main
- Change type: UI/Blade view change + test rewrite
- Files: resources/views/welcome.blade.php, tests/Feature/PublicHomepagePageTest.php, tests/Feature/PublicShellTest.php, resources/views/layouts/public.blade.php, tests/e2e/public-smoke.spec.ts
- Summary: Canonical-exact rebuild of `/` per `homepage_canonical`. Old 8-section Enhanced Homepage shell replaced with 3-section canonical layout: Hero → Bento Collections → Scholarly Services. Tri-lingual ru/kk/en $chrome data. Backward-compat markers for ConsolidationTest preserved. Stale layout span `data-homepage-stitch-reset` removed from public.blade.php. Tests: PublicHomepagePageTest 23/23 pass (89 assertions). PublicShellTest 7/7 pass (56 assertions).

## Last changed (Cluster F.2 — /news detail canonical-exact rebuild)
- Time: 2026-04-23 UTC
- Branch: main
- Change type: UI/Blade view change + test rewrite
- Files: resources/views/news/show.blade.php, tests/Feature/PublicNewsDetailPageTest.php, tests/Feature/Api/ConsolidationTest.php
- Summary: Canonical-exact rebuild of `/news/{slug}` detail per `docs/design-exports/news_detail_canonical`. Old shell (`.news-detail-grid` CSS grid 8fr/4fr, `.news-back-link`, `.news-meta-badge`, text-only related list, no author card, no newsletter, data-section="news-detail") fully replaced. New layout: 2-column flex (article col 2:1 ratio at 1024px+) with 9 data-section/data-test-id canonical markers. Article column: back link → header (meta + h1 + subtitle) → hero figure → body block renderer (lead/h2/list/p types) → inline CTA → footer (tags + share). Sidebar: author card (institutional, Material icon, editorial bio) → related updates (horizontal thumbnail cards excluding current article) → newsletter (dark gradient, drafts icon). BEM prefix `news-detail-canonical__*`. Tri-lingual ru/kk/en chrome. Seeded data from 3-article `$newsSeedProvider` preserved verbatim. Fixed stale ConsolidationTest `test_news_redirects_to_homepage` → `test_news_renders_as_canonical_page` (asserting 200 + canonical marker instead of 301 redirect to `/`).
- Verification: PublicNewsDetailPageTest 44/44 pass (54 assertions). Public suite 843 total; 66 pre-existing failures (ConsolidationTest /about/resources/catalog from prior rebuilds, SpaCatalogWiringTest, AccountRenewal/Reservations, ExternalResourcePageTest) — all pre-existing, 0 new failures from this change.

## Last changed (Cluster F — /news index canonical-exact rebuild)
- Time: 2026-04-23 UTC
- Branch: main
- Change type: UI/Blade view change + test rewrite
- Files: resources/views/news/index.blade.php, tests/Feature/PublicNewsIndexPageTest.php
- Summary: Canonical-exact rebuild of `/news` index per `docs/design-exports/news_index_canonical`. Old 266-line shell (data-section="news-intro/news-featured/news-grid", CSS classes: news-intro, news-featured-grid, news-grid, news-card, news-card-link) fully replaced. New layout matches canonical: (1) page header — eyebrow "Institutional Updates" + Newsreader display "Library Dispatch" + Manrope lead (max-w 720px); (2) featured lead story — full-width horizontal card (image 60% / copy 40% on md+) wrapping `<a>` to `/news/{slug}?lang=en`; (3) grid bar with h3 "Recent Articles" + 3 UI-only filter buttons (All Topics / Events / Research) with data-test-id hooks; (4) 3-col article grid with regular cards + bento highlight card (span-2, dark navy bg, CTA to `/events`); (5) Load More button. Tri-lingual chrome: eyebrow/heading/lead/filter labels/load-more. Real seeded articles wired from `$newsSeedProvider` (3 articles via `routes/web.php` closure). Featured article (`global-symposium-archival-integrity`) to featured lead; two non-featured articles to grid cards; bento is static events CTA. Scoped CSS in `@section('head')` using `.news-canonical__*` BEM prefix. 5 data-section markers + 7 data-test-id anchors added. Old shell markers verified absent in tests.
- Verification: PublicNewsIndexPageTest 22/22 pass (92 assertions). Public regression: 68/69 pass (1 pre-existing ExternalResourcePageTest failure from /about rebuild — not caused by news changes).


- Time: 2026-04-23 UTC
- Branch: main
- Change type: UI/Blade view change + test rewrite
- Files: resources/views/discover.blade.php, tests/Feature/DiscoverPageTest.php
- Summary: Canonical-led rebuild of `/discover` per `docs/design-exports/academic_discovery_hub_canonical`. Retired the full legacy brochure shell (hero-actions orbit, visual-chip constellation, quote card, disciplines section, institutional pathways section, research workflow section, institutional metadata section, bridge section — ~1200 lines of dead template code removed). New layout strictly follows the canonical export: (1) single-column hero with eyebrow + display + lead + filter chips row, (2) Faculties bento grid 2+1+1+2 with accent wash on span-2 cards, (3) UDC Knowledge Pathways four-card grid + "View Full UDC Tree" CTA. Preserved discovery truth: UDC-first axis (hero lead explicitly names UDC, dedicated UDC section with four primary pathways 0/50–60/61–69/8), faculty/department as secondary axis only (each bento card carries a UDC chip and links to `/catalog?faculty=X&udc=Y`). Tri-lingual ru/kk/en chrome. All internal wiring points to real `/catalog` query strings. `DiscoverPageTest` extended with canonical-layout guards (hero single-column shape, bento 2+1+1+2 span positions, accent wash count, legacy-shell id/class retirement).
- Verification: 14-class public suite 165/165 pass (954 assertions, 16.83s). DiscoverPageTest 14/14 pass (121 assertions).

## Previously changed (Cluster D — /resources canonical rebuild)
- Time: 2026-04-22 22:51:25 UTC
- Commit: 6fb3607
- Branch: main
- Change type: UI/Blade view change + test rewrite
- Files: resources/views/resources.blade.php, tests/Feature/ResourcesPageTest.php
- Commit message: feat(phase-3.d): public /resources canonical-exact rebuild per institutional_resources_canonical

## Previously changed (cleanup)
- Time: 2026-04-22 22:33:36 UTC
- Commit: 1992f03
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/about.blade.php
- Commit message: chore(phase-3.cleanup): post-Cluster-C public-layer consolidation

## Previously changed (C.2)
- Time: 2026-04-22 22:12:23 UTC
- Commit: ed1ea9d
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/events/show.blade.php, routes/web.php
- Commit message: feat(phase-3.c.2): public /events/{slug} detail per event_detail_canonical

## Previously changed (C.1)
- Time: 2026-04-22 22:02:16 UTC
- Commit: 41c8755
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/events/index.blade.php, routes/web.php
- Commit message: feat(phase-3.c.1): public /events index per events_index_canonical

## Previously changed (B.6)
- Time: 2026-04-22 13:45:46 UTC
- Commit: 50a44d7
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/contacts.blade.php, routes/web.php
- Commit message: feat(phase-3.b.6): canonical-exact /contacts rebuild per contacts_canonical

## Previously changed (B.5)
- Time: 2026-04-22 13:06:44 UTC
- Commit: 3f3cf8b
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/about.blade.php
- Commit message: feat(phase-3.b.5): canonical-exact /about rebuild per about_library_canonical

## Previously changed
- Time: 2026-04-22 12:47:16 UTC
- Commit: f5775b6
- Branch: main
- Change type: UI/Blade view change
- Files: resources/views/about.blade.php
- Commit message: feat(phase-3.b.4): embed collection profile + institutional directory into /about

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
- [x] **Cluster F: /news index canonical-exact rebuild** — ✅ COMPLETED (2026-04-23). Rebuilt `news/index.blade.php` per `docs/design-exports/news_index_canonical`. Old shell (data-section="news-intro/news-featured/news-grid", .news-card, .news-featured-grid, .news-grid classes) replaced wholesale. 4-section canonical layout: page header → featured lead story (horizontal card with image 60%/copy 40%) → grid bar with filter hooks → 3-col article grid + bento events CTA. Tri-lingual chrome. Real seeded articles from `$newsSeedProvider` closure. `PublicNewsIndexPageTest` rewritten 8→22 tests (92 assertions), all pass. Pre-existing `ExternalResourcePageTest` failure is from `/about` rebuild, unrelated.
- [ ] **Cluster F.2: /news detail canonical-exact rebuild** — next
- [ ] **Homepage canonicalization** — after news detail
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
