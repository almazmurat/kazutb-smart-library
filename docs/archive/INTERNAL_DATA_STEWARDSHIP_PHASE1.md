# Internal Data Stewardship Workflow - Phase 1

Status: implemented, internal-only backend slice.

## Goal

Provide a practical first operational workflow for manual data cleanup by staff,
using existing stewardship signals on copies (`needs_review`, `review_reason_codes`).

## Scope (this phase)

Entity coverage:
- `app.book_copies` only

Implemented capabilities:
- internal review queue endpoint for copies requiring review
- internal review summary endpoint for copy stewardship counts/reasons
- narrow review resolution action to clear review flags on a copy

Auth scope:
- internal-only staff middleware (`internal.circulation.staff`)
- same actor override policy as other internal write operations:
  - only `admin` may override `actor_user_id` to a different user id

## Endpoints

- `GET /api/v1/internal/review/copies`
- `GET /api/v1/internal/review/copies-summary`
- `POST /api/v1/internal/review/copies/{copyId}/resolve`

### Queue endpoint

Returns only copies where `needs_review=true`.

Supported filters:
- `reason_code` (optional)
- `page` (optional, default 1)
- `limit` (optional, default 20, max 100)

### Summary endpoint

Returns copy-level stewardship aggregates:
- totalCopies
- needsReviewCount
- resolvedCount
- topReasonCodes

### Resolve endpoint

Narrow explicit action:
- hard requirement: copy must exist and currently have `needs_review=true`
- action: set `needs_review=false`, clear `review_reason_codes`
- writes audit event `internal_copy_review_resolved` into `app.circulation_audit_events`

Error behavior:
- `400 invalid_copy_id` for invalid UUID
- `404 copy_not_found`
- `409 review_not_required` if copy is already not marked for review
- `403 insufficient_staff_role` for non-admin actor override

## Intentionally not included yet

- document-level or reader-level review resolution workflow
- bulk review operations
- automated correction pipelines
- CRM publication of stewardship endpoints
- frontend stewardship UI

This keeps the first slice operational, auditable, and safe while staying narrow.
