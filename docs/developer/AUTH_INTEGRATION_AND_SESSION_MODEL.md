# Auth Integration and Session Model — KazUTB Smart Library

**Status**: Phase B hardened (2026-04-06)  
**Scope**: Documents the real CRM-backed authentication flow, session handling, maturity classification, and remaining gaps.

---

## 1. Authentication flow (frozen in Phase A)

```
User → /login (library page)
     → enters login/password
     → JS POSTs to /api/login (library backend)
     → AuthController sends credentials to CRM API (POST http://10.0.1.47/api/login)
     → CRM validates against LDAP/AD
     → On success: CRM returns {token, user}
     → Library normalizes user, stores session: library.user, library.crm_token, library.authenticated_at
     → Session regenerated (fixation protection)
     → User redirected to /account
```

**Key invariants**:
- Login UX stays inside library (no CRM redirect)
- Library sends credentials server-side (never from browser to CRM)
- Bearer token from CRM stored in server-side session only — never returned to client
- Library normalizes roles: CRM raw role → reader/librarian/admin
- Unknown roles fall back to `reader`

---

## 2. Session structure

| Session key | Type | Content |
|---|---|---|
| `library.user` | `array` | Normalized user: id, name, email, login, ad_login, role |
| `library.crm_token` | `string` | CRM bearer token (for profile/logout calls) |
| `library.authenticated_at` | `string` | ISO 8601 timestamp of login |

Session driver: `database` (default). Session lifetime: 120 minutes.

---

## 3. Two authorization models (coexisting)

| Model | Used by | Auth source | Middleware |
|---|---|---|---|
| Session-based | Web routes, reader account, internal staff | `library.user` in session | `web` middleware + `EnsureInternalCirculationStaff` |
| Bearer + headers | CRM integration (`/api/integration/v1/*`) | Bearer token + 6 required headers | `EnsureIntegrationBoundary` |

---

## 4. Auth maturity classification (post-Phase B hardening)

| Concern | Classification | Notes |
|---|---|---|
| Login request/response path | **Implemented and credible** | CRM API call, token extraction, session storage, role normalization |
| Invalid credentials handling | **Implemented and credible** | Returns generic 401 message, no CRM details leaked |
| CRM network failure handling | **Implemented and credible** | Returns 503 with user-friendly message, logs error with full details |
| Token/session storage | **Implemented but fragile** | Token in session DB; SESSION_ENCRYPT not enabled by default |
| `/api/me` profile hydration | **Implemented and credible** | Strict is_array check, returns 401 for unauthenticated |
| Logout/invalidation | **Implemented and credible** | Session invalidated + CSRF token regenerated + optional CRM logout |
| Guest vs authenticated routing | **Implemented and credible** | Web routes require session; staff routes check role |
| Role normalization | **Implemented and credible** | Strict allowlist (reader/librarian/admin), safe fallback to reader |
| Reader vs staff behavior split | **Implemented and credible** | `EnsureInternalCirculationStaff` checks session role |
| Auth failure logging | **Implemented and credible** | Failed logins logged as warning, CRM unavailable as error |
| Login rate limiting | **Implemented and credible** | `throttle:login` — 5 attempts/min per login+IP (configurable) |
| Information disclosure prevention | **Implemented and credible** | No CRM response details, no exception messages in client responses |
| Integration token validation | **Partial** | Config-based allowlist available; must be enabled via `INTEGRATION_ALLOWED_TOKENS` env |
| CRM URL exposure | **Fixed** | Removed from login page HTML; URL only in server-side config |
| HTTPS for CRM communication | **Missing** | HTTP only; known risk documented in Phase A |
| Cryptographic token verification | **Missing** | Bearer tokens not cryptographically validated (signature/JWT) |
| Session encryption | **Not enabled by default** | `SESSION_ENCRYPT=false`; should be `true` in production |

---

## 5. Phase B hardening changes

### 5a. Login info-leak prevention
- **Before**: Failed login returned `{message, status, details: <CRM JSON>}`. CRM unavailable returned `{message, error: <exception message>}`.
- **After**: Failed login returns `{message: "Неверный логин или пароль."}` (401). CRM unavailable returns `{message: "Сервис авторизации временно недоступен..."}` (503). No internal details exposed.

### 5b. Login failure logging
- **Before**: Only successful logins were logged.
- **After**: Failed logins logged as `Log::warning('Library CRM login failed', {ip, login, crm_status})`. CRM unavailable logged as `Log::error('CRM auth service unavailable', {ip, login, error})`.

### 5c. Login rate limiting
- **Before**: No rate limiting on `/api/login`.
- **After**: `throttle:login` middleware applied. 5 attempts per minute per login+IP (configurable via `LOGIN_RATE_LIMIT` env). Returns 429 with user-friendly message.

### 5d. CRM URL removed from login page
- **Before**: `auth.blade.php` displayed `API авторизации: http://10.0.1.47/api/login` in page HTML.
- **After**: Removed. Internal CRM URL no longer exposed to users.

### 5e. Integration token allowlist
- **Before**: Any non-empty bearer token accepted by `EnsureIntegrationBoundary`.
- **After**: When `INTEGRATION_ALLOWED_TOKENS` env is set (comma-separated), only listed tokens are accepted. When empty, legacy behavior preserved (any token accepted). Invalid tokens logged with IP and token hash prefix.

---

## 6. Environment variables (auth-related)

| Variable | Default | Purpose |
|---|---|---|
| `EXTERNAL_AUTH_LOGIN_URL` | `http://10.0.1.47/api/login` | CRM login API endpoint |
| `EXTERNAL_AUTH_LOGOUT_URL` | (empty) | CRM logout API endpoint |
| `LOGIN_RATE_LIMIT` | `5` | Max login attempts per minute per login+IP |
| `INTEGRATION_ALLOWED_TOKENS` | (empty) | Comma-separated valid CRM integration tokens |
| `INTEGRATION_RATE_LIMIT` | `120` | Integration API requests per minute per client |
| `INTEGRATION_MUTATE_RATE_LIMIT` | `30` | Integration mutation requests per minute per client |
| `SESSION_ENCRYPT` | `false` | Enable session payload encryption |
| `SESSION_LIFETIME` | `120` | Session lifetime in minutes |

---

## 7. Remaining gaps and future work

| Gap | Severity | Blocked by |
|---|---|---|
| HTTPS for CRM communication | High | CRM infrastructure team must enable TLS |
| Cryptographic token verification (JWT/HMAC) | High | CRM must issue verifiable tokens |
| Session encryption in production | Medium | Ops team should set `SESSION_ENCRYPT=true` |
| Token expiration/refresh handling | Medium | CRM token lifecycle not yet defined |
| Operator role verification (CRM integration) | Medium | No CRM endpoint to validate operator roles |
| Auth event dashboard/observability | Low | Requires log aggregation infrastructure |

---

## 8. Test coverage

| Test file | Tests | What it covers |
|---|---|---|
| `AuthHardeningTest` | 10 | Info leak prevention, failure logging, rate limiting, token allowlist, CRM URL removal |
| `AuthSessionLifecycleTest` | 4 | Logout, session invalidation, staff middleware rejection |
| `AuthSessionMeTest` | 2+ | `/api/me` profile hydration |
| `IntegrationBoundarySkeletonTest` | 5 | Missing token, missing headers, invalid source, empty roles, success path |
| `IntegrationRateLimitTest` | 1+ | Rate limit headers present |

---

## 9. Security notes for operators

1. **Set `INTEGRATION_ALLOWED_TOKENS`** in production to restrict CRM integration access.
2. **Set `SESSION_ENCRYPT=true`** in production to protect session data at rest.
3. **Monitor `Library CRM login failed` warnings** for brute force detection.
4. **Monitor `CRM auth service unavailable` errors** for CRM outage detection.
5. **Push for HTTPS** on CRM communication when infrastructure allows.
