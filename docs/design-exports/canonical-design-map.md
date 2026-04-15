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
| Admin | Admin Overview | Project B / Admin Overview | future admin overview surface | admin overview page | pending | Must remain sibling to librarian family |

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