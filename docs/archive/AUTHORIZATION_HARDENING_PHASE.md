# Authorization Hardening (Current Operational Surface)

Status: implemented for current backend operations (internal + integration).

## Effective role model (current)

### Internal session staff roles
- Staff middleware gate (`internal.circulation.staff`) allows only:
  - `librarian`
  - `admin`
- Other roles (or missing session) are rejected with:
  - HTTP `403`
  - `error=staff_authorization_required`

### Integration operator roles (`X-Operator-Roles`)
- Integration boundary requires `X-Operator-Roles` header.
- Header must contain at least one semantic role value (not just commas/spaces).
- Empty semantic role set is rejected with:
  - HTTP `400`
  - `error.error_code=invalid_request`
  - `error.reason_code=missing_operator_roles`

## Sensitive operation requirements

### Internal copy write operations
Endpoints:
- `POST /api/v1/internal/copies`
- `PATCH /api/v1/internal/copies/{copyId}`
- `POST /api/v1/internal/copies/{copyId}/retire`

Rules:
- Require internal staff session (`librarian` or `admin`).
- `actor_user_id` override is admin-only when overriding another user id.
- Non-admin override attempt is rejected with:
  - HTTP `403`
  - `error=insufficient_staff_role`

### Internal circulation write operations
Endpoints:
- `POST /api/v1/internal/circulation/checkouts`
- `POST /api/v1/internal/circulation/returns`

Rules:
- Require internal staff session (`librarian` or `admin`).
- `actor_user_id` override is admin-only when overriding another user id.
- Non-admin override attempt is rejected with:
  - HTTP `403`
  - `error=insufficient_staff_role`

### Integration reservation mutate operations
Endpoints:
- `POST /api/integration/v1/reservations/{id}/approve`
- `POST /api/integration/v1/reservations/{id}/reject`

Rules:
- Approve requires operator role: `reservations.approve`
- Reject requires operator role: `reservations.reject`
- Missing required operator role is rejected with:
  - HTTP `403`
  - `error.error_code=forbidden`
  - `error.reason_code=insufficient_operator_role`

## What remains coarse (next hardening candidates)
- Internal role model is still two-tier (`librarian`/`admin`) with no fine-grained permission matrix per domain command.
- Integration role semantics are currently enforced for reservation mutate endpoints; other integration write surfaces should be hardened similarly as they expand.
- Session and integration role taxonomies are intentionally separate and should be mapped explicitly in future governance work if cross-surface consistency becomes required.
