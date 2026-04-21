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
| Public | Homepage | Project B / Homepage | `/` | `welcome.blade.php` or replacement homepage view | design-ready | New missing page generated in clean project |
| Public | About | Project A / About the Institution | `/about`, `/contacts` | `about.blade.php` | implemented | Reduced minimalist public informational page |
| Public | Discover | Project A / Academic Discovery Hub | `/discover` | `discover.blade.php` | pending | Keep UDC-first logic |
| Public | Login | Project A / Secure Institutional Access | `/login` | `auth.blade.php` | implemented | Refined; preserve auth behavior |
| Public | Catalog | Project B / Catalog | `/catalog` | `catalog.blade.php` | pending | Reader-facing catalog, not marketplace |
| Public / Member | Book Details | Project B / Book Details | book detail route / reader record page | `book.blade.php` or target detail view | pending | Academic record detail page |
| Public / Member | Resources | Project B / Resources | `/resources` | `resources.blade.php` | pending | External/licensed research resources |
| Member | Shortlist | Project A / Research Workspace / Shortlist | `/shortlist` | `shortlist.blade.php` | pending | Preserve real shortlist behavior |
| Member | Member Dashboard | Project A / Member Dashboard | `/account` | `account.blade.php` | pending | Students and teachers share same experience |

### Internal / librarian-facing

| Surface | Page Type | Canonical Source | Target Route / Surface | Target View / Area | Status | Notes |
|---|---|---|---|---|---|---|
| Internal | Librarian Operations Center | Project A / Librarian Operations Center | `/internal/dashboard` | `internal-dashboard.blade.php` | design-ready | Existing internal shell |
| Internal | Catalog Records Management | Project A / Catalog Records Management | future/internal records surface | staff records area | reference-only | Backend foundation first |
| Internal | Librarian Record Editor | Project A / Librarian Record Editor | future/internal editor surface | staff editor area | reference-only | Backend foundation first |
| Internal | Digital Asset & File Management | Project A / Digital Asset & File Management | future/internal asset surface | staff asset area | reference-only | Backend foundation first |
| Internal | Book Cover & Media Management | Project A / Book Cover & Media Management | future/internal media surface | staff media area | reference-only | Backend foundation first |
| Internal | Reader Access Clarification Hub | Project A / Reader Access Clarification Hub | internal reader clarification surface | internal reader support area | design-ready | Existing internal API support |
| Internal | Circulation & Waitlist Manager | Project A / Circulation & Waitlist Manager | `/internal/circulation` | `internal-circulation.blade.php` | design-ready | Waitlist portion may need lighter adaptation |
| Internal | Metadata Quality & Curation Hub | Project A / Metadata Quality & Curation Hub | `/internal/review` | `internal-review.blade.php` | design-ready | Existing review queues and summaries |

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