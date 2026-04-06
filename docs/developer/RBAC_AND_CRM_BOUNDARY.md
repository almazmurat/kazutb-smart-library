# RBAC and CRM Boundary — KazUTB Smart Library

**Status**: Frozen (Phase A deliverable)  
**Date frozen**: 2026-04-06  
**Scope**: Defines identity source, authorization enforcement, role mapping, and the strict boundary between CRM and library systems.

---

## 1. Identity and authentication authority

### Frozen model

| Concern | Authority | Implementation |
|---|---|---|
| Identity source (who is this user?) | **CRM** via LDAP/AD | Library sends credentials to CRM API; CRM validates against Active Directory |
| Authentication endpoint | **CRM** API | `POST http://10.0.1.47/api/login` |
| Login UX (form, errors, flow) | **Library** | Library renders `/login`, user never sees CRM UI |
| Session management | **Library** | Library stores session with `library.user`, `library.crm_token`, `library.authenticated_at` |
| Token lifecycle | **Library** | Library stores CRM bearer token in session; handles expiration and re-login |
| Logout | **Library** (with CRM notification) | Library invalidates session, optionally notifies CRM logout endpoint |
| Profile hydration | **Library** | Library extracts user fields from CRM response and normalizes into library session |

### Login flow (frozen)

```
1. User opens library login page (/login)
2. User enters login/password
3. Library POSTs credentials to CRM API (http://10.0.1.47/api/login)
4. CRM performs LDAP/AD validation
5. CRM returns token + user data
6. Library normalizes user, stores session
7. User continues inside library system
```

**No redirect to CRM UI. No CRM-hosted login page. Library-owned login UX.**

---

## 2. Authorization enforcement authority

### Frozen model

| Concern | Authority | Implementation |
|---|---|---|
| Role normalization | **Library** | CRM returns raw role; library normalizes to `reader`, `librarian`, `admin` |
| Web route protection | **Library** | Session-based role check in middleware and closures |
| API route protection (internal) | **Library** | `EnsureInternalCirculationStaff` middleware checks session for librarian/admin |
| API route protection (CRM-facing) | **Library** | `EnsureIntegrationBoundary` middleware validates bearer token + CRM headers |
| Mutation permission (CRM operations) | **Library** | CRM must pass `X-Operator-Roles` header; library validates operator role per action |
| Resource-level authorization | **Library** | Library decides what data a given role can access or mutate |

### Key principle

**CRM provides identity. Library enforces authorization.**

Even if CRM builds its own staff panels, the library API still independently checks:
- Is the request authenticated?
- Does the operator have the required role?
- Is the action permitted for this resource?

CRM cannot bypass library-side authorization by any means.

---

## 3. Role mapping

### Current role model

| CRM role value | Library normalized role | Capabilities |
|---|---|---|
| (default / unknown) | `reader` | Public catalog, account, reservations, loans |
| `reader` | `reader` | Same as above |
| `librarian` | `librarian` | Reader capabilities + internal staff routes + circulation + stewardship |
| `admin` | `admin` | Librarian capabilities + system administration |

### Role mapping implementation

```php
// AuthController normalizes CRM response:
$role = match(strtolower($crmUser['role'] ?? '')) {
    'admin' => 'admin',
    'librarian' => 'librarian',
    default => 'reader',
};
```

### Future considerations (undecided but bounded)

| Topic | Status | Constraint |
|---|---|---|
| Student vs teacher role distinction | Undecided | Both map to `reader` today; may differentiate for access privileges later |
| Fine-grained permission model | Undecided | Current 3-role model is sufficient for operational scope; may add granular permissions if needed |
| CRM operator role granularity | Partially implemented | `X-Operator-Roles` header already supports `reservations.approve`, `reservations.reject`, etc. |

---

## 4. CRM integration boundary — strict rules

### What CRM owns

| CRM responsibility | Scope |
|---|---|
| LDAP/AD authentication | Validate user credentials against Active Directory |
| Bearer token issuance | Return authentication token on successful login |
| User profile data | Return user identity fields (name, email, department, ad_login, role) |
| CRM-side UI | Build its own staff/admin panels (not library concern) |

### What CRM must never do

| Prohibition | Reason |
|---|---|
| Connect directly to library PostgreSQL database | Violates data ownership boundary |
| Bypass library API authentication/authorization | Library must independently verify every request |
| Assume library-side authorization state based on CRM-side state | Library is the sole authorization enforcer |
| Modify library data without going through library APIs | All mutations must be auditable through library |
| Embed library domain logic in CRM code | Library is the domain authority |

### What library exposes to CRM

| API namespace | Purpose | Auth model |
|---|---|---|
| `/api/integration/v1/reservations` | Reservation list, detail, approve, reject | Bearer token + integration headers |
| `/api/integration/v1/documents` | Document list, detail, create, update, archive | Bearer token + integration headers |
| `/api/integration/v1/_boundary/ping` | Infrastructure verification | Bearer token + integration headers |

### Integration request contract (frozen)

Every CRM request to library integration APIs must include:

| Header | Required | Purpose |
|---|---|---|
| `Authorization: Bearer <token>` | Yes | Authentication |
| `X-Request-Id` | Yes | Request tracing |
| `X-Correlation-Id` | Yes | Cross-system correlation |
| `X-Source-System` | Yes (must be `crm`) | Source identification |
| `X-Operator-Id` | Yes | Operator identity |
| `X-Operator-Roles` | Yes | Comma-separated operator roles |
| `X-Operator-Org-Context` | Yes | Organizational context |

Library echoes `X-Request-Id` and `X-Correlation-Id` on responses. Library adds `X-API-Version: v1` and `X-API-Scope: frozen`.

---

## 5. Two authentication models (coexisting)

The library uses two distinct auth models for different API surfaces:

### Model 1: Session-based (reader/account/internal staff)

| Aspect | Detail |
|---|---|
| Used by | Web routes, reader account, internal staff routes |
| Auth source | Laravel session containing `library.user` |
| How session is created | Login via CRM API, stored by AuthController |
| Middleware | Laravel session + `EnsureInternalCirculationStaff` for staff routes |
| Token type | Session cookie |
| Logout | Session invalidation + optional CRM notification |

### Model 2: Bearer token + headers (CRM integration)

| Aspect | Detail |
|---|---|
| Used by | `/api/integration/v1/*` routes |
| Auth source | Bearer token in `Authorization` header |
| How token is verified | `EnsureIntegrationBoundary` middleware validates token presence + required headers |
| Middleware | `EnsureIntegrationBoundary` + throttle + logging |
| Additional context | `X-Operator-Roles` for action-level permission checks |
| Logout | N/A (stateless per-request) |

These models are intentionally separate. Session-based auth is for library UI users. Bearer-based auth is for CRM system-to-system integration.

---

## 6. Security considerations (current state)

| Concern | Status | Risk |
|---|---|---|
| CRM auth over HTTP (not HTTPS) | Known risk | Credentials and tokens transmitted without TLS on internal network |
| Bearer token validation | Header presence only | No cryptographic verification of CRM-issued tokens |
| Session fixation protection | Handled by Laravel | Session regenerated on login/logout |
| CSRF protection | `PreventRequestForgery` middleware | Active on web routes |
| Rate limiting (integration) | `throttle:integration` / `throttle:integration-mutate` | Applied to CRM-facing routes |

### Phase B hardening targets (not implemented yet)

- HTTPS for CRM communication
- Stronger token validation
- Auth event logging/observability
- Token expiration handling
- Re-authentication flow on stale session

---

## 7. What must never change without explicit re-freeze

1. Library is the authorization enforcer — never delegated to CRM.
2. Login UX stays in library — never redirected to CRM.
3. CRM integration goes through library APIs only — never direct DB.
4. Integration headers contract (`X-Request-Id`, etc.) — never weakened.
5. Role normalization happens library-side — never blindly trusted from CRM.
