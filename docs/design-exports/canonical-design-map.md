# Canonical Screen Map — KazUTB Digital Library Platform

This document defines the approved screen source for each page type and acts as the design source of truth for downstream implementation.

## Source projects

### Project A — Approved reference screens
Used for canonical already-approved public/member/internal screens.

### Project B — Clean missing screens project
Used only for the newly generated missing pages:
- Homepage
- Catalog
- Book Details
- Resources
- Admin Overview

---

## Canonical screens by page type

### Public / member-facing

| Surface | Page Type | Canonical Source | Target Route / Surface | Target View / Area | Status | Notes |
|---|---|---|---|---|---|---|
| Public | Homepage | Project B / Homepage (`docs/design-exports/Enhanced Homepage/`) | `/` | `welcome.blade.php` | implemented — archive-reference | Phase 3.1 — canonical KazUTB Smart Library homepage. All hero / bento / hours+news / stats / repository / guides sections ported from the export with trilingual ru/kk/en copy. Authenticated Member Workspace card routes to `/dashboard` (canonical member shell); guest link goes to `/login`. Scope refinement (post-3.3): add a dedicated "Latest Arrivals / New Additions" block in a future public pass. |
| Public | About | Project A / About the Institution | `/about`, `/contacts` | `about.blade.php` | implemented — archive-reference | Phase 3.2 — consolidated KazUTB Smart Library informational surface. Both `/about` and `/contacts` render the same view; `$activePage='about'` renders mission-first ordering, `$activePage='contacts'` renders contacts + Librarian-on-Duty first. Librarian-on-Duty block routes authenticated readers to `/dashboard` and guests to `/login`. Trilingual ru/kk/en parity. Single catalog CTA at the end. |
| Public | News Index | `docs/design-exports/news_index_canonical/` | `/news` | `news/index.blade.php` | implemented — Phase 3.f canonical-exact rebuild | Phase 3.f — canonical-exact rebuild per `news_index_canonical`. Old shell (news-intro/news-featured/news-grid sections + news-card CSS) replaced wholesale. 4-section layout: page header + featured lead story (horizontal card) + article grid (with bento events CTA) + load-more. Tri-lingual ru/kk/en chrome. Articles seeded via `$newsSeedProvider` in `routes/web.php`. |
| Public | News Detail | `docs/design-exports/news_detail_canonical/` | `/news/{slug}` | `news/show.blade.php` | **implemented — Phase 3.g canonical-exact rebuild** | Phase 3.g — canonical-exact rebuild per `news_detail_canonical`. Old shell (`.news-detail-grid` 8fr/4fr, `.news-back-link`, `.news-meta-badge`, text-only related list, data-section="news-detail") replaced wholesale. 2-column flex layout: article column (back link → header → hero → body block renderer → inline CTA → footer) + sidebar (author card → related horizontal thumbnail cards → newsletter dark gradient block). 9 data-section/data-test-id canonical markers. BEM `news-detail-canonical__*`. Tri-lingual ru/kk/en. Unknown slugs → 404. |
| Public | Events Calendar | TBD — new Stitch required | `/events` | `events/index.blade.php` (planned) | planned — **Cluster C** | Phase 3 Cluster C.1. Distinct module from news; no canonical export yet; requires Stitch cycle before implementation. |
| Public | Event Detail | TBD — new Stitch required | `/events/{slug}` | `events/show.blade.php` (planned) | planned — **Cluster C** | Phase 3 Cluster C.2. Event-specific detail page; not merged into news detail. |
| Public | Library Leadership | TBD — new Stitch required | `/leadership` | `leadership.blade.php` (planned) | planned — **Cluster B** | Phase 3 Cluster B.1. Standalone public informational page for library leadership. Requires Stitch cycle; no canonical export yet. |
| Public | Library Usage Rules | TBD — new Stitch required | `/rules` | `rules.blade.php` (planned) | planned — **Cluster B** | Phase 3 Cluster B.2. Standalone public informational page for reader policy and usage rules. Requires Stitch cycle. |
| Public | Location & Wayfinding | About/Contacts + map component (future canonical export TBD) | `/contacts` (enhanced) or `/location` (TBD) | `about.blade.php` enhanced or dedicated view (planned) | planned — **Cluster B** | Phase 3 Cluster B.3. Room-level fund locations: `1/200` (технологический фонд), `1/202` (фонд колледжа), `1/203` (экономический фонд библиотеки) + map. Standalone vs `/contacts` IA decision pending — see OPEN_QUESTIONS. |
| Public | Collection/Fund Information | TBD — content architecture | `/about` or section-level (TBD) | inline sections (planned) | planned — **Cluster B** | Phase 3 Cluster B.4. Reader-facing narrative for collection areas. |
| Public | Discover | Project A / Academic Discovery Hub | `/discover` | `discover.blade.php` | **implemented — Phase 3.e (2026-04-23)** | Canonical-led rebuild per `academic_discovery_hub_canonical`: hero + Faculties bento 2+1+1+2 + UDC Knowledge Pathways grid + "View Full UDC Tree" CTA. UDC-first axis preserved; faculty/dept as secondary axis with UDC chips on bento cards wiring to `/catalog?faculty=X&udc=Y`. v2 donor used for faculty UDC-chip pattern only. Tri-lingual ru/kk/en. |
| Public | Login | Project A / Secure Institutional Access | `/login` | `auth.blade.php` | implemented | Refined; preserve auth behavior |
| Public | Catalog | Project B / Catalog | `/catalog` | `catalog.blade.php` | pending — **Cluster A** | Phase 3 Cluster A.3. Existing surface; needs export-backed refinement. |
| Public / Member | Book Details | Project B / Book Details | book detail route / reader record page | `book.blade.php` or target detail view | pending — **Cluster A** | Phase 3 Cluster A.4. Existing surface; needs export-backed refinement. Legacy debt signals; highest-risk in Cluster A. |
| Public / Member | Resources | Project B / Resources | `/resources` | `resources.blade.php` | implemented — archive-reference | Phase 3 Cluster A.1 completed. Refined to display 8 curated external resources (IPR SMART featured, 7 in grid) from config/external_resources.php via ExternalResourceService. Dynamic category badges, access type indicators, external URLs. Tri-lingual (ru/kk/en). Support section with contact links. |
| Member | Shortlist | Project A / Research Workspace / Shortlist | `/shortlist` | `shortlist.blade.php` | pending | Preserve real shortlist behavior |
| Member | Member Dashboard | Project A / Member Dashboard | `/account` | `account.blade.php` | pending | Students and teachers share same experience |

### Internal / librarian-facing

| Surface | Page Type | Canonical Source | Target Route / Surface | Target View / Area | Status | Notes |
|---|---|---|---|---|---|---|
| Librarian | Librarian Overview | `docs/design-exports/librarian_overview/` | `/librarian` | `librarian/overview.blade.php` | implemented — archive-reference | Canonical librarian shell (`layouts.librarian`); Phase 1.1 |
| Librarian | Circulation Desk | `docs/design-exports/circulation_desk/` | `/librarian/circulation` | `librarian/circulation.blade.php` | implemented — archive-reference | Canonical librarian shell (`layouts.librarian`); Phase 1.2 |
| Librarian | Data Stewardship & Cleanup | `docs/design-exports/data_cleanup_stewardship/` | `/librarian/data-cleanup` | `librarian/data-cleanup.blade.php` | implemented — archive-reference | Canonical librarian shell (`layouts.librarian`); Phase 1.2 |
| Librarian | Scientific Works Moderation Queue | `docs/design-exports/scientific_works_moderation_queue/` | `/librarian/repository` | `librarian/repository.blade.php` | implemented — archive-reference | Canonical librarian shell; Phase 1.2 — merges with repository module in Phase 4 |
| Internal (transitional) | Librarian Operations Center | Project A / Librarian Operations Center | `/internal/dashboard` → 301 `/librarian` | n/a (redirect) | transitional — 301 redirect (Phase 1.4) | Canonical surface: `/librarian` |
| Internal (transitional) | Catalog Records Management | Project A / Catalog Records Management | future/internal records surface | staff records area | reference-only | Backend foundation first |
| Internal (transitional) | Librarian Record Editor | Project A / Librarian Record Editor | future/internal editor surface | staff editor area | reference-only | Backend foundation first |
| Internal (transitional) | Digital Asset & File Management | Project A / Digital Asset & File Management | future/internal asset surface | staff asset area | reference-only | Backend foundation first |
| Internal (transitional) | Book Cover & Media Management | Project A / Book Cover & Media Management | future/internal media surface | staff media area | reference-only | Backend foundation first |
| Internal (transitional) | Reader Access Clarification Hub | Project A / Reader Access Clarification Hub | internal reader clarification surface | internal reader support area | design-ready | Existing internal API support |
| Internal (transitional) | Circulation & Waitlist Manager | Project A / Circulation & Waitlist Manager | `/internal/circulation` → 301 `/librarian/circulation` | n/a (redirect) | transitional — 301 redirect (Phase 1.4) | Canonical surface: `/librarian/circulation` |
| Internal (transitional) | Data Stewardship (legacy) | internal operations tooling | `/internal/stewardship` → 301 `/librarian/data-cleanup` | n/a (redirect) | transitional — 301 redirect (Phase 1.4) | Canonical surface: `/librarian/data-cleanup` |
| Internal (transitional) | Metadata Quality & Curation Hub | Project A / Metadata Quality & Curation Hub | `/internal/review` | `internal-review.blade.php` | transitional — no canonical destination yet | Canonical target undecided between `/librarian/data-cleanup` (metadata anomalies) and `/librarian/repository` (scholarly moderation); retained functional pending Phase 4 review |
| Internal (transitional) | Staff AI Chat (experimental) | internal assistant prototype | `/internal/ai-chat` | `internal-ai-chat.blade.php` | transitional — no canonical destination yet | Experimental; no canonical `/librarian/*` surface in roadmap |

### Admin-facing

| Surface | Page Type | Canonical Source | Target Route / Surface | Target View / Area | Status | Notes |
|---|---|---|---|---|---|---|
| Admin | Admin Overview | Project B / Admin Overview | `/admin` | `admin/overview.blade.php` | implemented — archive-reference | Canonical admin shell (`layouts.admin`) |
| Admin | User & Role Management | `docs/design-exports/user_role_management/` | `/admin/users` | `admin/users.blade.php` | implemented — archive-reference | Mock data until Phase 6 (real data layer) |
| Admin | Governance & Logs | `docs/design-exports/governance_logs/` | `/admin/logs` | `admin/governance.blade.php` | implemented — archive-reference | Awaiting real `AuditLogService` |
| Admin | News Management | `docs/design-exports/news_management/` | `/admin/news` | `admin/news.blade.php` | implemented — archive-reference | CRUD wiring is Phase 6 |
| Admin | Feedback Inbox | `docs/design-exports/feedback_inbox/` | `/admin/feedback` | `admin/feedback.blade.php` | implemented — archive-reference | Intake pipeline wiring is Phase 6 |
| Admin | Reports & Analytics | `docs/design-exports/reports_analytics/` | `/admin/reports` | `admin/reports.blade.php` | implemented — archive-reference | Real aggregations pending Phase 6 |
| Admin | System Settings | `docs/design-exports/system_settings/` | `/admin/settings` | `admin/settings.blade.php` | implemented — archive-reference | Persistence pending Phase 6 |

> Implementation milestones for the admin shell are recorded in [[DELIVERY_ROADMAP]]. Exports marked "archive-reference" stay for historical traceability; the running Blade view is the source of truth.

### Public — auth (cross-reference)

| Surface | Page Type | Canonical Source | Target Route / Surface | Target View / Area | Status | Notes |
|---|---|---|---|---|---|---|
| Public | Login | Project A / Secure Institutional Access (`docs/design-exports/Secure Access/`) | `/login` | `auth.blade.php` | implemented — archive-reference | Behavior preserved; no active redesign |

### Member — canonical member shell (Phase 2a)

| Surface | Page Type | Canonical Source | Target Route / Surface | Target View / Area | Status | Notes |
|---|---|---|---|---|---|---|
| Member | Member Overview Dashboard | `docs/design-exports/member_overview_dashboard/` | `/dashboard` (name `member.dashboard`) | `layouts/member.blade.php` + `member/dashboard.blade.php` | implemented — archive-reference | Role-gated to `reader`; copy adapted to KazUTB; placeholder loan / research / shortlist data until backend wiring |
| Member | My Reservations | `docs/design-exports/my_reservations/` | `/dashboard/reservations` (name `member.reservations`) | `member/reservations.blade.php` | implemented — archive-reference | Canonical status vocabulary (pending · confirmed · ready_for_pickup · fulfilled · cancelled · expired); placeholder cards until reservation backend lands |
| Member | My Literature Shortlist | `docs/design-exports/my_literature_shortlist/` | `/dashboard/list` (name `member.list`) | `member/list.blade.php` | implemented — archive-reference | Personal shortlist shell; real data pending integration with `ShortlistStorageService` |
| Member | My Borrowing History | `docs/design-exports/my_borrowing_history/` | `/dashboard/history` (name `member.history`) | `member/history.blade.php` | implemented — archive-reference | Chronological academic-record view; placeholder items until circulation backend lands |
| Member | Notifications | `docs/design-exports/notifications/` | `/dashboard/notifications` (name `member.notifications`) | `member/notifications.blade.php` | implemented — archive-reference | Canonical event vocabulary (reservation.*, loan.due_soon/overdue, digital_access.granted, message.status_changed, system.announcement); placeholder timeline until notification backend lands |
| Member | Messages (Contact) | `docs/design-exports/contact_messages/` | `/dashboard/messages` (name `member.messages`) | `member/messages.blade.php` | implemented — archive-reference | Canonical category vocabulary (request · question · improvement · complaint · other) and status vocabulary (open · in_review · resolved · archived); composer is non-persisting placeholder until contact backend lands. Route named `messages` to match the in-shell nav label; the export folder is called `contact_messages` but the UI label is "Messages" |

> Transitional `/account` route remains in place with a visible transitional banner pointing readers to `/dashboard`. Post-login redirect still lands readers on `/account` for now; cut-over to `/dashboard` as the default reader landing target is the next member-side change.

### Removed

- `docs/design-exports/athenaeum_digital/` — **deleted 2026-04-21**. This direction was explicitly identified as non-canonical in [[PROJECT_CONTEXT]] §31.2 and must never be reintroduced.

---

## Role model reminder

### Guest
Unauthenticated public user.

### University user
Students and teachers are the SAME user role in this product.  
They share the SAME member-facing experience and functionality.

### Librarian
Internal operational user.

### Admin
Internal broader oversight user.

---

## Implementation rule

For downstream implementation:
- preserve the canonical design source for each page type
- do not mix alternate variants
- do not use non-canonical screens
- adapt exported HTML into Blade as literally as possible
- replace only unsupported/fake elements with project-safe equivalents
- keep real backend behavior intact