# Internal Data Stewardship Phase 3 - Reader Review

**Status:** Operational backend slice implemented
**Date:** 2026-04-03
**Scope:** Reader-level review workflow for internal staff (internal-only)

## Summary

This phase extends internal stewardship from copies/documents to readers so post-migration reader cleanup can be handled safely and explicitly.

Reader-level review signals already existed (`needs_review`, `review_reason_codes`) and were visible in diagnostics/account summary paths. This slice makes those signals operationally manageable through queue, summary, and explicit resolve actions.

## Internal Endpoints (Staff Only)

- `GET /api/v1/internal/review/readers`
  - Query: `reason_code` (optional), `page` (default 1), `limit` (default 20, max 100)
  - Returns paginated readers where `needs_review=true`
- `GET /api/v1/internal/review/readers-summary`
  - Query: `top_limit` (default 5, max 20)
  - Returns total/needs-review/resolved counts and top reason codes
- `POST /api/v1/internal/review/readers/{readerId}/resolve`
  - Body: `resolution_note` (optional), `actor_user_id` (admin-only override), `request_id`, `correlation_id`

## Resolution Behavior (Narrow and Safe)

Resolve action intentionally does only this:
- sets `needs_review=false`
- clears `review_reason_codes` to empty array
- updates `updated_at` when available

It does **not** edit reader profile fields (name, contacts, registration dates, etc.).

## Authorization and Policy

- Endpoints are under existing internal staff middleware (`internal.circulation.staff`)
- Same actor override policy as copy/document stewardship:
  - admin may override `actor_user_id`
  - non-admin override returns `403 insufficient_staff_role`

## Audit

Successful resolution writes `CirculationAuditEvent`:
- `action`: `internal_reader_review_resolved`
- `entity_type`: `reader`
- `entity_id`: resolved reader UUID
- `reader_id`: resolved reader UUID
- `previous_state` and `new_state`: before/after snapshots
- metadata includes `resolution_note` and resolved reason-code count

## Intentional Out Of Scope

- No CRM API changes
- No reader profile editing workflow
- No bulk resolve
- No frontend UI
- No automatic correction/AI actions
