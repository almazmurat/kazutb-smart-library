# Internal Copy Management API v1 - Phase 2C

Status: implemented — internal-only copy retirement slice.

## Endpoints added in this phase

```
POST /api/v1/internal/copies/{copyId}/retire
```

Route is internal-only, protected by the `internal.circulation.staff` middleware.

## Request body

| Field | Type | Required | Notes |
|---|---|---|---|
| `reason_code` | string | YES | One of: `LOST`, `DAMAGED_BEYOND_REPAIR`, `WRITTEN_OFF`, `MISSING_AFTER_AUDIT`, `OTHER` |
| `note` | string | Conditional | Required when `reason_code=OTHER`. Optional (max 1000 chars) for all other codes. |
| `actor_user_id` | uuid | NO | Optional override for audit actor. Falls back to session user. |
| `request_id` | string | NO | Optional idempotency/tracing header-equivalent. |
| `correlation_id` | string | NO | Optional distributed tracing identifier. |

## Conflict rules (hard blocks)

| HTTP status | Error code | Condition |
|---|---|---|
| 400 | `invalid_copy_id` | Path `copyId` is not a valid UUID |
| 400 | `invalid_request_body` | `reason_code` missing or not in allowed set |
| 400 | `note_required_for_other` | `reason_code=OTHER` but `note` is null or blank |
| 404 | `copy_not_found` | Copy does not exist |
| 409 | `already_retired` | Copy `retired_at` is already non-null |
| 409 | `copy_on_loan` | Active loan exists for copy (status=active, returned_at IS NULL) |
| 409 | `active_reservation_conflict` | Copy-bound reservation in `PENDING` or `READY` status exists |

## Schema changes in this phase

Three nullable columns added to `app.book_copies`:

| Column | Type | Nullable | Meaning |
|---|---|---|---|
| `retired_at` | TIMESTAMPTZ | YES | Primary retirement truth signal. NULL = active. Non-NULL = retired. |
| `retirement_reason_code` | TEXT | YES | Controlled taxonomy value. Always set alongside `retired_at`. |
| `retirement_note` | TEXT | YES | Optional free-text annotation. Required at app layer when reason=OTHER. |

Migration file: `database/migrations/2026_04_02_100000_add_retirement_fields_to_book_copies.php`

Applied via: `ALTER TABLE app.book_copies ADD COLUMN IF NOT EXISTS ...` (metadata-only, no table rewrite).

## Read-model changes in this phase

Both read endpoints expose retirement fields.

### New lifecycle fields (in GET /copies/{id} and GET /documents/{id}/copies)

```json
"lifecycle": {
  "stateCode": 1,
  "needsReview": false,
  "reviewReasonCodes": [],
  "registeredAt": "2021-03-15T00:00:00.000Z",
  "retiredAt": null,
  "retirementReasonCode": null,
  "retirementNote": null
}
```

For a retired copy:
```json
"lifecycle": {
  "retiredAt": "2026-04-02T10:30:00.000Z",
  "retirementReasonCode": "LOST",
  "retirementNote": null
}
```

### Updated availabilityIndicator

Possible values (priority order):
1. `RETIRED` — copy has non-null `retired_at` (takes priority over loan state)
2. `ON_LOAN` — copy is not retired but has an active loan
3. `NO_ACTIVE_LOAN` — copy is not retired and has no active loan

## Audit behavior

Every successful retirement writes an `internal_copy_retired` event to `app.circulation_audit_events`:

- `action`: `internal_copy_retired`
- `entity_type`: `copy`
- `entity_id`: copy UUID
- `previous_state`: full copy read-model snapshot before retirement (availability was not RETIRED)
- `new_state`: full copy read-model snapshot after retirement (availability is RETIRED)
- `metadata.details.reason_code`: the retirement reason code
- `metadata.details.note_provided`: boolean
- `metadata.details.document_id`: parent document UUID
- `metadata.details.branch_id`: branch UUID

## Retirement is NOT published to CRM

This retirement is strictly internal. It does not:
- Post to `public."Reservation"` or any CRM table
- Call any external CRM API
- Affect reservation status
- Affect the public-facing catalog

## Scope explicitly out of this phase

- Reactivation / un-retire
- Transfer between branches
- Fund reassignment
- Delete / physical removal
- Copy-level archive (separate concept from physical retirement)
- CRM-facing publication of copy lifecycle changes
