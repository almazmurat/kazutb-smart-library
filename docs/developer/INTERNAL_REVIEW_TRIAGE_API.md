# Internal Review Triage API

## Overview

Aggregated stewardship triage endpoints that provide cross-entity visibility into unresolved review work across copies, documents, and readers. These endpoints are **internal-only** and require staff authorization.

## Scope

- **In scope**: Aggregated counts, per-entity breakdown, top reason codes across all stewardship entity types, quality issues summary.
- **Out of scope**: Time-series trends, charts, detailed analytics, CRM-facing exposure, frontend rendering.

## Endpoints

### GET `/api/v1/internal/review/triage-summary`

Returns a single aggregated view of unresolved stewardship review work.

**Auth**: Requires `librarian` or `admin` role via session.

**Query parameters**:
| Parameter   | Type    | Default | Description                           |
|-------------|---------|---------|---------------------------------------|
| `top_limit` | integer | 5       | Max number of top reason codes (1–20) |

**Response structure**:
```json
{
  "data": {
    "totalUnresolved": 42,
    "totalEntities": 15000,
    "byEntity": {
      "copies": {
        "total": 10000,
        "needsReviewCount": 30,
        "resolvedCount": 9970
      },
      "documents": {
        "total": 3000,
        "needsReviewCount": 8,
        "resolvedCount": 2992
      },
      "readers": {
        "total": 2000,
        "needsReviewCount": 4,
        "resolvedCount": 1996
      }
    },
    "qualityIssues": {
      "total": 120,
      "openCount": 45,
      "criticalCount": 3,
      "highCount": 12
    },
    "topReasonCodes": [
      {
        "reasonCode": "MISSING_ISBN",
        "count": 15,
        "entities": ["copies", "documents"]
      }
    ]
  },
  "source": "internal_triage_aggregation"
}
```

### GET `/api/v1/internal/review/triage-reason-codes`

Returns aggregated top reason codes across all entity types, with optional per-entity breakdown.

**Auth**: Requires `librarian` or `admin` role via session.

**Query parameters**:
| Parameter            | Type    | Default | Description                                    |
|----------------------|---------|---------|------------------------------------------------|
| `top_limit`          | integer | 10      | Max number of top reason codes (1–50)          |
| `include_per_entity` | string  | false   | Set to `true`/`1`/`yes` for per-entity detail  |

**Response structure** (with `include_per_entity=true`):
```json
{
  "data": {
    "topReasonCodes": [
      {
        "reasonCode": "MISSING_ISBN",
        "count": 15,
        "entities": ["copies", "documents"]
      }
    ],
    "perEntity": {
      "copies": [
        { "reasonCode": "MISSING_ISBN", "count": 10 }
      ],
      "documents": [
        { "reasonCode": "MISSING_ISBN", "count": 5 }
      ],
      "readers": []
    }
  },
  "source": "internal_triage_aggregation"
}
```

## Data Sources

| Entity    | Table               | Review flag     | Reason codes column      |
|-----------|---------------------|-----------------|--------------------------|
| Copies    | `app.book_copies`   | `needs_review`  | `review_reason_codes`    |
| Documents | `app.documents`     | `needs_review`  | `review_reason_codes`    |
| Readers   | `app.readers`       | `needs_review`  | `review_reason_codes`    |
| Quality   | `review.quality_issues` | `status`    | `issue_code` / `severity` |

## Authorization

Both endpoints use the same `internal.circulation.staff` middleware as existing internal review endpoints. Only `librarian` and `admin` roles have access.

## Relationship to Existing Endpoints

These triage endpoints aggregate the same data exposed by:
- `GET /api/v1/internal/review/copies-summary`
- `GET /api/v1/internal/review/documents-summary`
- `GET /api/v1/internal/review/readers-summary`
- `GET /api/v1/review/issues-summary`

The triage endpoints do **not** replace per-entity endpoints. They provide a higher-level operational overview for cleanup management and future admin/librarian dashboards.

## Implementation

- Service: `App\Services\Library\InternalTriageService`
- Controller methods: `InternalReviewController::triageSummary`, `InternalReviewController::triageReasonCodes`
- Tests: `Tests\Feature\Api\InternalTriageTest`
