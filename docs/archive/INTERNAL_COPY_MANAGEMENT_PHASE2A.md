# Internal Copy Management API v1 - Phase 2A

Status: implemented as internal-only read slice.

Scope in this phase:
- GET /api/v1/internal/copies/{copyId}
- GET /api/v1/internal/documents/{documentId}/copies

This phase is intentionally limited to inventory visibility and document-to-copy navigation.

## Explicitly out of scope
- Copy create
- Copy patch/update
- Copy retire/deactivate
- Copy transfer between branches
- Copy delete (hard or soft)
- Any CRM-facing publication for copy mutation

## Safety intent
- Internal-only endpoints behind staff session middleware
- Read-only operational payload
- Strict UUID validation and predictable not found behavior
- No lifecycle or circulation state mutation
