# Delivery Roadmap — KazUTB Library Platform
> Created: 2026-04-21
> Status: Proposed — ratification pending on OPEN_QUESTIONS Q1–Q7 (see [[OPEN_QUESTIONS]])
> Source truth: [[PROJECT_CONTEXT]] §§ 20, 24, 26, 27, 30, 31

This roadmap sequences the work required to take the platform from its current build-out state (admin shell complete) to production readiness. It is grounded in the canonical page map (PROJECT_CONTEXT §30) and the hybrid shell architecture.

---

## Target architecture (authoritative)

| Shell | Prefix | Middleware | Layout | Audience |
|---|---|---|---|---|
| Public | `/` | none | standalone / `layouts.public` | guest + all |
| Member | `/dashboard/*` | `library.auth` | `layouts.member` (to build) | ordinary users |
| Librarian | `/librarian/*` | `library.auth` + `librarian.staff` | `layouts.librarian` (to build) | librarian + admin |
| Admin | `/admin/*` | `library.auth` + `admin.staff` | `layouts.admin` (implemented) | admin only |
| Repository | `/repository/*` | mixed (public metadata, auth for read) | repository shell | public + authenticated |

---

## Phase 0 — Architecture Normalization (immediate)

Goal: stop the role-routing defect and the `/internal/*` naming drift from compounding.

- [ ] Fix post-login redirect in `routes/web.php` — match role to `/admin` | `/librarian` (temp: `/internal/dashboard`) | `/account` (temp: `/dashboard`).
- [ ] Apply `library.auth` middleware to the `/internal/*` route group.
- [ ] Delete `docs/design-exports/athenaeum_digital/` and purge references (canonical violation per PROJECT_CONTEXT §31.2).
- [ ] Mark implemented admin exports as `archive-reference` in `docs/design-exports/canonical-design-map.md`.
- [ ] Add Playwright smoke test: role-based landing verification.

Exit: admins land on `/admin`, librarians on a staff surface, members on a member surface. No canonical-violating exports remain in the repo.

---

## Phase 1 — Librarian Shell Normalization

Goal: unify librarian workflows under the canonical `/librarian/*` namespace with a shared shell.

- [ ] Stitch: generate `Librarian Operations Shell` (Newsreader + Manrope, sidebar nav) sibling to `layouts.admin`.
- [ ] Create `resources/views/layouts/librarian.blade.php`.
- [ ] Create `EnsureLibrarianStaff` middleware (`librarian` or `admin`).
- [ ] Mount `/librarian` route group with `library.auth` + `librarian.staff`.
- [ ] Migrate pages: `/internal/dashboard` → `/librarian`; `/internal/circulation` → `/librarian/circulation`; `/internal/stewardship` → `/librarian/data-cleanup`; `/internal/review` → `/librarian/repository` (or merge); `/internal/ai-chat` → `/librarian/ai` (feature-flagged).
- [ ] Add 301 redirects from every `/internal/*` path to the new `/librarian/*` path.
- [ ] Update `copilot-instructions.md` + canonical-design-map references.

Exit: `/internal/*` returns 301 to `/librarian/*`; librarian surfaces share one shell.

---

## Phase 2 — Member Dashboard Decomposition

Goal: replace monolithic `/account` with canonical multi-route `/dashboard/*` (PROJECT_CONTEXT §30.2).

- [ ] Stitch: generate member dashboard landing + sub-pages (reservations, history, list, notifications, messages, contact).
- [ ] Create `resources/views/layouts/member.blade.php`.
- [ ] Mount `/dashboard/*` with `library.auth`.
- [ ] 301 `/account` → `/dashboard`.
- [ ] Build `/dashboard/contact` form posting to feedback intake that now surfaces in `/admin/feedback`.

Exit: members navigate a real multi-page dashboard; `/account` is legacy-redirect only.

---

## Phase 3 — Public Surface Uplift

Goal: bring all public pages to canonical visual and product standard.

Completed sub-phases:
- [x] **3.1** — Homepage (`welcome.blade.php`) — canonical KazUTB Smart Library brand, auth-aware navbar, `/dashboard` workspace routing.
- [x] **3.2** — About + Contacts (`about.blade.php`) — consolidated informational surface, real address/hours, Librarian-on-Duty block.
- [x] **3.3** — Public News module (`/news`, `/news/{slug}`) — reversed 301, trilingual seeded articles, index + detail views.
- [x] **3.3-stab** — Stabilization pass — brand regression + broken image assets corrected.

> **2026-04-22 reconciliation note:** The remaining public scope is now formally decomposed into five clusters.
> Do NOT treat this as a simple linear checklist of pages.
> Each cluster has its own readiness level, export coverage, and Stitch requirements.

---

### Phase 3 Cluster A — Export-backed Refinement (next implementation target)

Goal: bring already-existing public surfaces up to canonical design standard using available exports.

- [ ] **3-A.1** Resources refinement — `resources.blade.php` against `docs/design-exports/resources` export. ← **NEXT IMPLEMENTATION STEP**
- [ ] **3-A.2** Discover refinement — `discover.blade.php` against `docs/design-exports/Academic Discovery Hub` export.
- [ ] **3-A.3** Catalog refinement — `catalog.blade.php` against `docs/design-exports/catalog` export.
- [ ] **3-A.4** Book detail refinement — `book.blade.php` against `docs/design-exports/book_details` export.

Exit: all four surfaces match canonical export anchors with KazUTB institutional tone.

---

### Phase 3 Cluster B — Informational Institutional Surfaces

Goal: new standalone public informational pages that do not yet exist as runtime surfaces.
Stitch cycle required for: Leadership, Rules (no canonical export yet).
Location enhancement may fit within existing `/contacts` surface or as a dedicated view (decision pending).

- [ ] **3-B.1** Leadership page — standalone `/leadership` (library administration, team structure, institutional mandate).
- [ ] **3-B.2** Library Usage Rules page — standalone `/rules` (reader policy, usage rules, borrowing rules).
- [ ] **3-B.3** Location / map / wayfinding enhancement — explicit coverage of room-level fund locations: `1/200` (технологический фонд), `1/202` (фонд колледжа), `1/203` (экономический фонд библиотеки) + map component. Decide standalone `/location` vs enhanced `/contacts` block before implementing.
- [ ] **3-B.4** Collection/fund informational content — public reader-facing narrative for each collection area.

Exit: public IA has credible institutional depth layer beyond catalog/discovery.

---

### Phase 3 Cluster C — Events Module

Goal: distinct public events surface separate from news (ratified in [[DECISIONS]] 2026-04-22).
Stitch cycle required (no canonical export yet).

- [ ] **3-C.1** Events index — `/events` list surface with upcoming and past events.
- [ ] **3-C.2** Event detail — `/events/{slug}` individual event page.

Exit: events are discoverable independently from news; news–events boundary is clean.

---

### Phase 3 Cluster D — Homepage Enhancement

Goal: add the "Latest Arrivals / New Additions" block to homepage as a real product feature, not a decorative widget.

- [ ] **3-D.1** Latest arrivals block on homepage — backed by recent-ingest semantics from catalog (not static). Decide data source (catalog ingest chronology / curated librarian picks / hybrid) before implementing.

Exit: homepage surfaces freshness signal for recent additions.

---

### Phase 3 Cluster E — Knowledge-Navigation Enrichment

Goal: extend discover/catalog IA with faculty/department knowledge-map layer alongside primary UDC axis.
UDC remains primary; this is an additive secondary axis.

- [ ] **3-E.1** Faculty/department knowledge-map discovery layer — integrated into `/discover` or `/catalog` navigation (not a separate page).
- [ ] **3-E.2** Discovery/catalog IA enrichment around academic structure — browse by faculty/department current directions.

Exit: academic users have a faculty/discipline entry point alongside UDC subject navigation.

---

### Phase 3 cluster sequencing (ratified 2026-04-22)

Recommended order: **A → E (partial) → B → C → D**
- Cluster A first: lowest risk, export-backed, existing runtime surfaces.
- Cluster E partial integration alongside or after A: discover is in A, enrichment is lightweight additive.
- Cluster B next: needs Stitch for Leadership/Rules, can run in parallel once A is done.
- Cluster C after B: events module, requires Stitch, standalone build.
- Cluster D last or alongside A: latest arrivals needs stable catalog semantics.

Exit (Phase 3 complete): all five clusters shipped; public surfaces match canonical design map; no placeholder pages.

---

---

## Phase 4 — Scientific Repository Module (PROJECT_CONTEXT §20)

Goal: deliver the currently-in-scope repository subsystem.

- [ ] Stitch: repository browse, metadata detail, controlled read, librarian upload, admin approval queue.
- [ ] DB: `scientific_works`, `scientific_work_files`, status enum per §14.3.
- [ ] Services: repository read/write services modeled on `CirculationLoanReadService` / `ShortlistStorageService` patterns.
- [ ] Routes: `/repository/*`, `/librarian/repository/*`, `/admin/repository/*`.
- [ ] Controlled read wiring (no download, viewer-only per §27.3).

Exit: authors can submit, librarians can moderate, admins can approve, readers can view controlled.

---

## Phase 5 — Controlled Digital Viewer Completion

Goal: close the gap on controlled e-material governance.

- [ ] Librarian upload UI (cover + digital file + access level flag).
- [ ] Admin bulk tools for access-level flipping.
- [ ] Harden viewer per §27.3 (no download, no print, no right-click, session-bound streaming).
- [ ] Watermark hooks (optional, future).

Exit: full lifecycle for digital materials through librarian + admin surfaces.

---

## Phase 6 — Real Data Layer for Admin Shell

Goal: remove mock data from every `admin.*` page.

- [ ] `/admin/users` → real user read via CRM + local library roles.
- [ ] `/admin/logs` → real `AuditLogService` with centralized emission from mutating points per §26.
- [ ] `/admin/news` → CRUD backed by `news` table + publish workflow.
- [ ] `/admin/feedback` → CRUD backed by feedback intake pipeline.
- [ ] `/admin/reports` → real analytics aggregations from loans / holdings / users / repository.
- [ ] `/admin/settings` → persistence to `settings` table; gated mutations.

Exit: every admin surface operates on real data; no hard-coded demo values remain.

---

## Phase 7 — Notifications, Multilingual Pass, QA Expansion

Goal: product-grade cross-cutting concerns.

- [ ] In-app notifications + email (Laravel Queue + Redis).
- [ ] Systematic ru/kk/en translations across all shells.
- [ ] Playwright E2E coverage for every major user flow.
- [ ] PHPUnit coverage for every new service.

Exit: notifications flowing, all pages fully trilingual, test coverage green.

---

## Phase 8 — Production Hardening

Goal: ship.

- [ ] Security review (CSRF, rate limits, storage policies, session hardening).
- [ ] Observability (structured logs, error tracking, uptime).
- [ ] Backup + restore verified.
- [ ] Animation & motion polish per §17.3.
- [ ] Final canonical-design-map audit.

Exit: production deployment gate.

---

## Risks tracked against this roadmap

- CRM auth degradation blocks all authenticated flows — keep conservative fallback path.
- Rename churn from `/internal/*` and `/account` may break bookmarks — 301s mandatory, not optional.
- Scientific repository DB schema needs review before work begins (open question Q7).
- Audit-log emission depth must be decided before Phase 6 starts (how verbose, retention).

## Links
- [[PROJECT_CONTEXT]]
- [[CURRENT_STATE]]
- [[DECISIONS]]
- [[OPEN_QUESTIONS]]
- [[TASK_LOG]]
- [[GRAPH_INDEX]]
