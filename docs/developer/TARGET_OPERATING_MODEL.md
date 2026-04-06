# Target Operating Model — KazUTB Smart Library

**Status**: Frozen (Phase A deliverable)  
**Date frozen**: 2026-04-06  
**Scope**: Defines which system owns which responsibility. Binding on all future implementation and agent tasks.

---

## 1. Executive summary

The KazUTB Smart Library is a **library-domain-first platform**. It owns reader UX, library domain logic, data truth, and internal operational workflows. CRM acts as an **authentication provider and external operational client** that may build its own staff/admin panels by consuming library APIs. The library must never collapse into just a frontend shell.

---

## 2. Responsibility matrix

| Concern | Owner | Classification | Notes |
|---|---|---|---|
| Reader-facing UX (catalog, book detail, account) | **Library** | Mandatory | Library system is the primary reader touchpoint |
| Login UX (login page, form, error display) | **Library** | Mandatory | No redirect to CRM UI during login |
| Public catalog search/filter/sort | **Library** | Mandatory | Canonical: `/catalog` → `/api/v1/catalog-db` |
| Book detail pages | **Library** | Mandatory | Canonical: `/book/{isbn}` → `/api/v1/book-db/{isbn}` |
| Reader account (loans, reservations, profile) | **Library** | Mandatory | Session-based, reader identity from CRM auth |
| Library domain logic (circulation, reservation, stewardship) | **Library** | Mandatory | All business rules enforced library-side |
| Library data ownership (documents, copies, funds, readers) | **Library** | Mandatory | PostgreSQL `app.*` schema is canonical |
| Staff/admin/librarian UI in library | **Library** | Preferred | Exists at `/internal/*`, operational today |
| Staff/admin/librarian UI in CRM | **CRM** | Optional | CRM may build panels consuming library APIs |
| Authentication (LDAP/AD identity resolution) | **CRM** | Mandatory | Library sends credentials, CRM validates against AD |
| Authorization enforcement | **Library** | Mandatory | Library enforces roles/permissions on its own APIs |
| Role mapping (CRM user → library role) | **Library** | Mandatory | CRM returns raw user; library normalizes to reader/librarian/admin |
| Data stewardship and correction workflows | **Library** | Mandatory | Library-internal quality tooling |
| Reporting and analytics | **Library** | Mandatory | Must preserve reporting integrity from old system |
| Digital materials (controlled viewer, access control) | **Library** | Mandatory | Restricted access, no unrestricted download |
| External licensed resources (IPR SMART, etc.) | **Library** | Mandatory | Contract-aware access rules |
| Integration API surface for CRM | **Library** | Mandatory | Library exposes, CRM consumes; at `/api/integration/v1` |

---

## 3. Reader-facing UX — mandatory library-side

The reader-facing surface is **non-negotiable library-side**.

### What this means

- The public catalog, book detail, search, filters, and account pages live in the library system.
- Login/logout UX is rendered by the library.
- Reader interactions (reservation, loan visibility, renewal) happen through library UI backed by library APIs.
- CRM has no reader-facing UX role.

### Current implementation

| Surface | Route | Status |
|---|---|---|
| Public catalog | `GET /catalog` | Canonical, operational |
| Book detail | `GET /book/{isbn}` | Canonical, operational |
| Reader account | `GET /account` | Operational |
| Login page | `GET /login` | Operational |
| Reader viewer | `GET /book/{isbn}/read` | Transitional (canonical API primary, external fallback) |
| SPA shell | `GET /app/{any?}` | Transitional, not canonical for default flow |

---

## 4. Library domain logic — mandatory library-side

All library business rules execute inside the library system. CRM-side panels that trigger library operations do so **only through library APIs**, never by direct database access or embedded logic.

### Domains owned by library

| Domain | Current state |
|---|---|
| Catalog/document management | Early operational (DB-backed, canonical API) |
| Copy/fund/location | Early operational (internal staff APIs exist) |
| Circulation (checkout/return/renew) | Early operational (internal staff APIs, tested) |
| Reservation lifecycle | Pilot-ready (CRM integration v1 exists) |
| Data stewardship (review, enrichment, contacts) | Early operational (internal APIs, triage, bulk ops) |
| Reporting/analytics | Not yet mature (foundation exists) |
| Digital materials | Not yet implemented (controlled viewer model defined) |

---

## 5. Staff/admin/librarian UI strategy

### Frozen decision

- **Library-side staff UI is preferred**, not optional.
- Staff routes at `/internal/*` are operational today (dashboard, review, stewardship, circulation, AI chat).
- CRM **may** build parallel staff panels using library APIs — this is explicitly allowed.
- But the library **must not drop** its own staff UI capability in anticipation of CRM panels.
- Library-side staff UI is the operational baseline; CRM staff UI is an enhancement, not a replacement.

### Classification

| Staff UI surface | Classification |
|---|---|
| Library `/internal/dashboard` | Preferred — operational baseline |
| Library `/internal/review` | Preferred — operational baseline |
| Library `/internal/stewardship` | Preferred — operational baseline |
| Library `/internal/circulation` | Preferred — operational baseline |
| CRM-side staff panels via library APIs | Optional — enhancement, not replacement |

### What this means for implementation

- Continue building library-side internal staff workflows.
- When CRM builds parallel panels, library APIs must be the only data source.
- Library staff UI remains the fallback if CRM panels are unavailable.
- Do not de-scope library-side staff features to "force" CRM usage.

---

## 6. CRM role — bounded and explicit

CRM is a **neighboring university system**, not the owner of library domain truth.

### What CRM owns

| Responsibility | Scope |
|---|---|
| LDAP / Active Directory authentication | Identity verification for login |
| University administrative environment | Staff/admin workflows for university systems |
| CRM-side UI for CRM-specific workflows | Not library concern |

### What CRM does not own

| Not CRM's responsibility | Reason |
|---|---|
| Library domain logic | Library is the domain authority |
| Library data | Library PostgreSQL is canonical |
| Reader UX | Library system is the reader touchpoint |
| Login UX | Library renders login, sends credentials to CRM API |
| Library authorization rules | Library enforces its own permissions |
| Library reporting integrity | Library preserves reporting semantics |

### What CRM may build using library APIs

- Staff/admin/librarian panels consuming library operational APIs
- Reservation management (currently: list, detail, approve, reject)
- Document management (currently: list, detail, create, update, archive)
- Future: circulation, stewardship, reporting — when APIs are published

---

## 7. Data ownership — mandatory library-side

- Library database (PostgreSQL) is the **single source of truth** for library domain data.
- CRM must **never connect directly** to library database.
- All CRM data access goes through library APIs with authentication and authorization.
- Data stewardship (quality, correction, deduplication) is a library-internal concern.
- Reporting data must preserve library semantics (fund structure, campus/branch, college/university).

---

## 8. Transitional surfaces

These surfaces exist today but are not part of the target model:

| Surface | Current state | Target |
|---|---|---|
| `/api/v1/catalog-external` (proxy to 10.0.1.8) | Reader fallback only | Remove after canonical API confirmed in production |
| `/book/{isbn}/read` (reader route) | Transitional, canonical API primary | Stabilize or replace with controlled viewer |
| `/app/{any?}` SPA shell | Active but not canonical for default public flow | Evaluate for future UX strategy |
| Bridge routes (`/v1/bridge/*`) | Data diagnostic endpoints | Evaluate for deprecation or formalization |

---

## 9. What must never happen

1. CRM must never connect directly to library database.
2. Library must never collapse into "just a frontend for CRM".
3. Reader UX must never redirect to CRM UI for core library flows.
4. Library authorization must never be delegated entirely to CRM.
5. Data mutations through CRM must never bypass library API safety controls.
6. Library staff UI must never be deleted to force CRM-only operation.
7. Reporting integrity must never be silently broken by data model changes.

---

## 10. Classification legend

| Classification | Meaning |
|---|---|
| **Mandatory** | Non-negotiable. Must be implemented and maintained. |
| **Preferred** | Default choice. May have alternatives, but this is the baseline. |
| **Optional** | Allowed but not required. Enhancement, not core. |
| **Transitional** | Exists today, has a defined migration path, will be resolved. |
| **Undecided but bounded** | Decision deferred, but scope of possible outcomes is constrained. |
