# Internal Document Review Flagging

## Overview

Adds a safe internal-only action to flag documents for stewardship review, completing the entry/resolution cycle for document data quality management.

Previously, documents could only be **resolved** (cleared from review). Now staff can also **flag** documents into review with explicit reason codes — supporting post-migration cleanup and ongoing data stewardship.

## Scope

- **In scope**: Flagging a single document with one or more reason codes, deduplication of codes, audit trail, predictable behavior for already-flagged documents.
- **Out of scope**: Bulk flagging, automated detection/scanning, generic document metadata editing, CRM-facing exposure, frontend UI.

## Endpoint

### POST `/api/v1/internal/review/documents/{documentId}/flag`

Flags a document for stewardship review.

**Auth**: Requires `librarian` or `admin` role via session (`internal.circulation.staff` middleware).

**Path parameters**:
| Parameter    | Type | Description                    |
|--------------|------|--------------------------------|
| `documentId` | UUID | Document to flag for review    |

**Request body (JSON)**:
| Field            | Type          | Required | Description                         |
|------------------|---------------|----------|-------------------------------------|
| `reason_codes`   | string[]      | Yes      | 1–10 reason codes (max 64 chars each) |
| `flag_note`      | string\|null  | No       | Optional note (max 1000 chars)      |
| `actor_user_id`  | UUID\|null    | No       | Actor override (admin only)         |
| `request_id`     | string\|null  | No       | Request tracking ID                 |
| `correlation_id` | string\|null  | No       | Correlation tracking ID             |

**Behavior**:
- Sets `needs_review = true` on the document
- Merges provided reason codes with any existing codes (no duplicates)
- Reason codes are normalized to uppercase
- If the document is already flagged, new codes are merged and the action succeeds
- Writes a `CirculationAuditEvent` with action `internal_document_review_flagged`

**Response (200)**:
```json
{
  "success": true,
  "data": {
    "documentIdentity": { "id": "...", "isbnRaw": "...", "isbnNormalized": "..." },
    "title": { "titleRaw": "...", "titleNormalized": "..." },
    "lifecycle": {
      "needsReview": true,
      "reviewReasonCodes": ["MISSING_ISBN", "TITLE_MISMATCH"],
      "createdAt": "..."
    },
    "updatedAt": "..."
  },
  "flagging": {
    "wasAlreadyFlagged": false,
    "addedReasonCodes": ["MISSING_ISBN", "TITLE_MISMATCH"],
    "mergedReasonCodes": ["MISSING_ISBN", "TITLE_MISMATCH"]
  },
  "source": "app.documents"
}
```

**Error responses**:
| Status | Error code            | Condition                              |
|--------|-----------------------|----------------------------------------|
| 400    | `invalid_document_id` | `documentId` is not a valid UUID       |
| 403    | `staff_authorization_required` | No staff session            |
| 403    | `insufficient_staff_role` | Non-admin actor override attempt   |
| 404    | `document_not_found`  | Document does not exist                |
| 422    | (validation)          | Missing or invalid `reason_codes`      |

## Audit

Every successful flag writes a `CirculationAuditEvent` with:
- `action`: `internal_document_review_flagged`
- `entity_type`: `document`
- `previous_state` / `new_state`: Full document record before/after
- `metadata.details`: flag_note, requested/added/merged reason codes, was_already_flagged

## Relationship to Existing Endpoints

This completes the document stewardship lifecycle:
1. **Flag** → `POST .../documents/{id}/flag` (entry path — this endpoint)
2. **Queue** → `GET .../review/documents` (view flagged documents)
3. **Summary** → `GET .../review/documents-summary` (aggregate stats)
4. **Resolve** → `POST .../documents/{id}/resolve` (resolution path)

## Implementation

- Service method: `InternalDocumentReviewWorkflowService::flagDocumentForReview()`
- Controller method: `InternalReviewController::flagDocument()`
- Tests: `Tests\Feature\Api\InternalDocumentFlagWorkflowTest` (9 tests)
