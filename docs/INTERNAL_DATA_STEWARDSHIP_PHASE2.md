# Internal Data Stewardship Phase 2 - Document Review

**Status:** Operational backend slice implemented
**Date:** 2026-04-02
**Scope:** Document-level review workflow for internal staff

## Summary

This phase extends the operational data stewardship layer from copy-focused to document-level, enabling internal staff to manage document metadata quality through a safe, explicit, and audited workflow.

Documents in the migrated database accumulate review flags due to:
- Imported/merged bibliographic records
- Dirty/incomplete metadata (missing ISBN, author, title normalization failures)
- Archived/deprecated records that need human review
- Bulk migration anomalies requiring reconciliation

Like the copy stewardship slice, this implementation provides:
- A paginated review queue
- Summary statistics and top reasons for failure
- Explicit resolution action (clear review flags only)
- Audit trail via `CirculationAuditEvent`
- Authorization enforcement (staff-only, admin actor override policy)

## What Is Implemented

### Internal Endpoints for Staff

**Document Review Queue**
- `GET /api/v1/internal/review/documents`
- Query parameters: `reason_code` (optional filter), `page` (default 1), `limit` (default 20, max 100)
- Returns documents where `needs_review=true`, with optional reason code filtering
- Response: paginated list with document identity, title, ISBN, and lifecycle metadata

**Document Review Summary**
- `GET /api/v1/internal/review/documents-summary`
- Query parameters: `top_limit` (default 5, max 20)
- Returns aggregate statistics:
  - Total documents in system
  - Documents needing review count
  - Documents with review resolved count
  - Top reason codes (via PostgreSQL UNNEST + GROUP BY)

**Document Review Resolution**
- `POST /api/v1/internal/review/documents/{documentId}/resolve`
- Request body:
  - `resolution_note` (optional, max 1000 chars): human narrative of why review was resolved
  - `actor_user_id` (optional): UUID; admin-only override to attribute action to different staff member
  - `request_id`, `correlation_id` (optional): for request tracing
- Success response: document with cleared review flags in new_state
- Error responses:
  - 400: invalid document UUID
  - 403: actor override without admin role, or missing staff session
  - 404: document not found
  - 409: document not marked for review

### Service Layer

**InternalDocumentReviewWorkflowService**
- Location: `app/Services/Library/InternalDocumentReviewWorkflowService.php`
- Public methods:
  - `listDocumentReviewQueue(?string $reasonCode, int $page, int $limit)` - Paginated queue with filtering
  - `documentReviewSummary(int $topReasonCodesLimit)` - Aggregated statistics
  - `resolveDocumentReview(string $documentId, ?string $resolutionNote, array $context)` - Explicit resolution action
- Database transaction: All mutations use row-level locking and atomic transactions
- Audit event: Creates `CirculationAuditEvent` with action `internal_document_review_resolved`

### Authorization

- **Middleware:** All endpoints protected by `internal.circulation.staff` middleware
- **Session Context:** Requires valid session with `internal_staff_user` attribute
- **Actor Override:** Only admin staff (role=admin) may specify different `actor_user_id`
- **Non-admin staff:** actor_user_id defaults to session user; override attempt returns 403

### Data Mutation

**Resolution Behavior**
- Clears `needs_review` boolean to false
- Clears `review_reason_codes` array to empty
- Updates `updated_at` timestamp
- Does NOT modify any bibliographic fields (title, ISBN, authors, etc.)
- Does NOT batch-process; only single-document resolution per request

### Audit Trail

**CirculationAuditEvent Entry**
- Action: `internal_document_review_resolved`
- Entity type: `document`
- Previous state: full document snapshot before resolution
- New state: full document snapshot after resolution
- Context: actor_user_id, actor_type (staff_operator), request_id, correlation_id
- Metadata: resolution_note_provided flag, reason_codes_count (before resolution)

## What Is NOT Included (Intentional Scope Limits)

- **Bulk operations:** No batch resolve, no automatic bulk processing
- **Metadata editing:** Resolution clears review signals only; does not edit ISBN, title, authors
- **Document merge/deduplication:** No document-level consolidation here (future phase if needed)
- **Reader-level review (phase note):** Not included in this phase 2 slice; implemented later in `docs/INTERNAL_DATA_STEWARDSHIP_PHASE3_READERS.md`
- **Frontend UI:** No staff interface; API-only for now
- **AI-assisted correction:** No automatic corrections; human review only
- **CRM scope expansion:** No integration API surface changes

## Database Schema

**Required Columns on app.documents**
- `id` (UUID primary key)
- `needs_review` (boolean, default false)
- `review_reason_codes` (text[], default '{}')
- `title_raw` (text)
- `title_normalized` (text)
- `isbn_raw` (text)
- `isbn_normalized` (text)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Audit Chain**
- `app.circulation_audit_events` - Single schema for both copy and document resolution events
- Column: `entity_type` distinguishes between 'copy' and 'document'

## Testing

**Coverage:**
- Queue lists documents with needs_review=true
- Queue excludes documents with needs_review=false
- Queue filters by reason_code using PostgreSQL ANY operator
- Summary counts total, needs_review, and resolved documents
- Summary aggregates top reason codes via UNNEST + GROUP BY
- Resolve success: clears flags, updates timestamp, creates audit event
- Resolve failure: invalid UUID returns 400
- Resolve failure: not_marked_for_review returns 409
- Resolve failure: staffless request returns 403
- Authorization: all endpoints require staff session

**Test File:** `tests/Feature/Api/InternalDocumentReviewWorkflowTest.php`

## Operational Pattern (How Staff Use It)

1. **Check Queue:** Staff calls `GET /api/v1/internal/review/documents` to see pending documents
2. **Check Summary:** Staff calls `GET /api/v1/internal/review/documents-summary` to understand scope/priorities
3. **Filter by Issue:** Staff calls `GET /api/v1/internal/review/documents?reason_code=METADATA_INCOMPLETE` to focus
4. **Research/Correct:** Staff manually investigates each document via book detail or CRM, applies corrections externally
5. **Mark Resolved:** Staff calls `POST /api/v1/internal/review/documents/{id}/resolve` with optional note
6. **Audit Trail:** Each resolution recorded with actor, timestamp, note, and before/after snapshots

## Integration Boundary

- **CRM-facing API:** No changes; integration boundary remains frozen at v1
- **Internal-only:** All document review endpoints are internal staff namespace (`/api/v1/internal/review/*`)
- **Reader UX:** No exposed review signals in public catalog; quality metadata for internal diagnostics only

## Next Steps (Phase 3+)

**Likely candidates:**
1. Document-level bulk operations (batch resolve by reason code)
2. Reader-level stewardship slice (similar queue/summary/resolve for reader quality)
3. Consolidated data quality dashboard combining copy/document/reader signals
4. AI-assisted correction recommendations (separate from manual review flow)
5. Stewardship metrics and KPIs for operational dashboarding

**Not planned for stewardship:**
- Frontend editor for document metadata
- CRM API expansion
- Automatic corrections without audit trail
- Reader UX integration with review signals

## Consistency with Copy Stewardship (Phase 1)

| Aspect | Copies (Phase 1) | Documents (Phase 2) |
|--------|-----------------|-------------------|
| Queue endpoint | `/api/v1/internal/review/copies` | `/api/v1/internal/review/documents` |
| Summary endpoint | `/api/v1/internal/review/copies-summary` | `/api/v1/internal/review/documents-summary` |
| Resolve endpoint | `/api/v1/internal/review/copies/{id}/resolve` | `/api/v1/internal/review/documents/{id}/resolve` |
| Authorization | staff.circulation.internal | staff.circulation.internal |
| Actor override | admin-only | admin-only |
| Audit action | `internal_copy_review_resolved` | `internal_document_review_resolved` |
| Reason codes | PostgreSQL UNNEST filtering | PostgreSQL UNNEST filtering |
| Resolution scope | Clear needs_review + review_reason_codes | Clear needs_review + review_reason_codes |
| No metadata edit | True | True |
| Bulk operations | Not implemented | Not implemented |

## Files Changed

- `app/Services/Library/InternalDocumentReviewWorkflowService.php` (new)
- `app/Services/Library/InternalDocumentReviewException.php` (new)
- `app/Http/Controllers/Api/InternalReviewController.php` (extended)
- `routes/api.php` (3 new routes added)
- `tests/Feature/Api/InternalDocumentReviewWorkflowTest.php` (new)
- `docs/INTERNAL_DATA_STEWARDSHIP_PHASE2.md` (this file)
