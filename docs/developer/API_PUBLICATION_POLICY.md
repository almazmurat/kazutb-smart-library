# API Publication Policy — KazUTB Smart Library

**Status**: Frozen (Phase A deliverable)  
**Date frozen**: 2026-04-06  
**Scope**: Defines which APIs are public, internal-only, or CRM-facing; governance rules for adding, changing, or deprecating APIs; frozen scope areas.

---

## 1. API surface classification

All library API routes fall into exactly one of four tiers:

| Tier | Audience | Auth model | Governance |
|---|---|---|---|
| **Public** | Anyone (no auth) | None | Conservative — must be stable |
| **Reader-facing** | Authenticated readers (session) | Session-based | Moderate — reader-visible contract |
| **Internal staff** | Librarians/admins (session) | Session + role check | Internal — may evolve faster |
| **CRM integration** | CRM system (bearer + headers) | Bearer + header contract | Strict — frozen scope, explicit versioning |

---

## 2. Current route inventory by tier

### Tier 1: Public (no auth)

| Route | Method | Controller | Purpose | Status |
|---|---|---|---|---|
| `/api/v1/catalog-db` | GET | CatalogController@dbIndex | Catalog search/filter | Canonical |
| `/api/v1/book-db/{isbn}` | GET | BookController@dbShow | Book detail by ISBN | Canonical |
| `/api/v1/landing/stats` | GET | LandingController@stats | Public landing page stats | Operational |
| `/api/v1/landing/arrivals` | GET | LandingController@arrivals | New arrivals | Operational |
| `/api/v1/landing/popular` | GET | LandingController@popular | Popular items | Operational |
| `/api/v1/catalog-external` | GET | CatalogController@proxy | External proxy (fallback only) | Transitional — remove when canonical confirmed |

### Tier 2: Reader-facing (session auth)

| Route | Method | Controller | Purpose | Status |
|---|---|---|---|---|
| `/api/login` | POST | AuthController@login | Login (credentials to CRM) | Canonical |
| `/api/v1/me` | GET | AuthController@me | Current user profile | Canonical |
| `/api/v1/logout` | POST | AuthController@logout | Logout | Canonical |
| `/api/v1/account/loans` | GET | AccountController@loans | Reader loan history | Operational |
| `/api/v1/account/reservations` | GET/POST | AccountController@reservations | Reader reservations | Operational |
| `/api/v1/account/renew` | POST | AccountController@renew | Account renewal | Operational |
| `/api/v1/account/profile` | GET | AccountController@profile | Reader profile | Operational |

### Tier 3: Internal staff (session + librarian/admin role)

| Route prefix | Controller | Purpose | Status |
|---|---|---|---|
| `/api/v1/internal/dashboard` | InternalDashboard | Staff dashboard | Operational |
| `/api/v1/internal/review` | InternalReview | Data review workflows | Operational |
| `/api/v1/internal/stewardship` | InternalStewardship | Data stewardship | Operational |
| `/api/v1/internal/triage` | InternalTriage | Record triage | Operational |
| `/api/v1/internal/circulation` | InternalCirculation | Checkout/return/renew | Operational |
| `/api/v1/internal/readers` | InternalReaders | Reader management | Operational |
| `/api/v1/internal/ai-chat` | InternalAiChat | AI assistant | Operational |
| `/api/v1/internal/bulk-operations` | BulkOperations | Bulk data operations | Operational |

### Tier 4: CRM integration (bearer + header contract)

| Route | Method | Purpose | Status |
|---|---|---|---|
| `/api/integration/v1/reservations` | GET | List reservations | Frozen |
| `/api/integration/v1/reservations/{id}` | GET | Reservation detail | Frozen |
| `/api/integration/v1/reservations/{id}/approve` | POST | Approve reservation | Frozen |
| `/api/integration/v1/reservations/{id}/reject` | POST | Reject reservation | Frozen |
| `/api/integration/v1/documents` | GET | List documents | Frozen |
| `/api/integration/v1/documents/{id}` | GET | Document detail | Frozen |
| `/api/integration/v1/documents` | POST | Create document | Frozen |
| `/api/integration/v1/documents/{id}` | PUT | Update document | Frozen |
| `/api/integration/v1/documents/{id}/archive` | POST | Archive document | Frozen |
| `/api/integration/v1/_boundary/ping` | GET | Infrastructure check | Frozen |

### Diagnostic / Bridge routes (unclassified)

| Route prefix | Purpose | Status |
|---|---|---|
| `/api/v1/bridge/*` | Data diagnostic endpoints | Transitional — evaluate for deprecation or formalization |

---

## 3. Governance rules

### Rule 1: No silent API removal

Any route that is currently operational must not be silently removed. Before removal:
1. Mark route as `deprecated` with target removal date.
2. Verify no runtime consumers exist.
3. Document in this file.
4. Remove only in a subsequent release.

### Rule 2: CRM integration scope is frozen

The CRM integration surface (`/api/integration/v1/*`) is **frozen**. Changes require:
1. Explicit decision documented in this file.
2. Version negotiation (new endpoints use `/api/integration/v2/` or similar).
3. Old endpoints must remain backward-compatible until explicitly deprecated.
4. CRM team must be notified of any contract change.

### Rule 3: Internal staff APIs may evolve

Internal staff routes (`/api/v1/internal/*`) serve library-side staff UI only. They:
- May add new endpoints freely.
- May change response shapes with notice (update docs).
- Should not break existing staff UI functionality without coordinated changes.
- Do not require CRM-side coordination.

### Rule 4: Public APIs require stability

Public routes (Tier 1) are consumed by unauthenticated users. They:
- Must be backward-compatible within a major version.
- New fields may be added but existing fields must not be removed.
- Response envelope shape (`{data: ..., success: true/false}`) must not change.

### Rule 5: Reader-facing APIs require session stability

Reader routes (Tier 2) depend on session auth. They:
- Must maintain consistent behavior for logged-in readers.
- May evolve but should not break reader account flows.
- Login/logout contracts are frozen (same rules as CRM integration).

---

## 4. Versioning strategy

### Current state

| Namespace | Version | Status |
|---|---|---|
| `/api/v1/*` | v1 | Active — all reader, public, internal routes |
| `/api/integration/v1/*` | v1 | Frozen — CRM integration |
| `/api/login`, `/api/v1/me`, etc. | v1 | Active — auth routes |

### Rules

- All new API versions must be explicitly namespaced (e.g., `/api/v2/...` or `/api/integration/v2/...`).
- Old versions remain active until explicitly deprecated and removed per Rule 1.
- No version auto-upgrade or transparent migration.

---

## 5. Frozen scope — what must not change without re-freeze

| Area | What is frozen |
|---|---|
| CRM integration contract | All routes, methods, headers, response envelopes at `/api/integration/v1/*` |
| Authentication flow | Login endpoint, session structure, CRM API call pattern |
| Public catalog canonical routes | `/api/v1/catalog-db`, `/api/v1/book-db/{isbn}` |
| Response envelope | `{data: ..., success: true/false, message: ...}` pattern |
| Integration header contract | `X-Request-Id`, `X-Correlation-Id`, `X-Source-System`, `X-Operator-Id`, `X-Operator-Roles` |

---

## 6. Deprecation and removal schedule

| Route | Reason | Target |
|---|---|---|
| `/api/v1/catalog-external` | Transitional fallback; canonical API should replace | Remove after production verification of canonical path |
| `/api/v1/bridge/*` | Diagnostic endpoints; evaluate utility | Decide: formalize or deprecate by Phase C |

---

## 7. Future publication criteria (Phase B+)

Before any new CRM-facing API is published:

1. **Contract defined** — OpenAPI spec or equivalent documentation for route, method, request/response schema.
2. **Auth enforced** — `EnsureIntegrationBoundary` middleware applied.
3. **Tests exist** — Feature tests covering success, auth failure, validation failure.
4. **Rate limited** — `throttle:integration` or `throttle:integration-mutate` applied.
5. **Audit logging** — Mutations must be logged with operator context.
6. **Reviewed and documented** — Entry added to this file before deployment.

No CRM-facing API route should be casually exposed without meeting all six criteria.
