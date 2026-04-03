# Internal Copy Management API v1 - Phase 2B

Status: implemented as internal-only create plus strictly limited patch slice.

Endpoints added in this phase:
- POST /api/v1/internal/copies
- PATCH /api/v1/internal/copies/{copyId}

Allowed create fields:
- document_id (required)
- branch_id (required)
- sigla_id (required and must belong to branch_id)
- inventory_number (optional)
- registered_at (optional)
- needs_review (optional)
- review_reason_codes (optional, only when needs_review=true)

Allowed patch fields:
- needs_review
- review_reason_codes

Audit behavior:
- every create writes internal_copy_created to app.circulation_audit_events
- every patch writes internal_copy_updated to app.circulation_audit_events

Explicitly out of scope:
- retire
- reactivate
- transfer between branches
- fund reassignment
- delete
- document relinking
- branch changes
- CRM-facing publication of copy mutations

Conservative implementation notes:
- copy identity is generated internally using UUID id/core_copy_id and next legacy_inv_id
- document legacy linkage is derived from the referenced document when available
- branch/location coherence is validated against app.branches and app.siglas
- patch support is intentionally narrower than the physical schema to avoid ownership/circulation drift