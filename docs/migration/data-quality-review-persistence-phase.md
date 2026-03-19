# Data Quality Review Persistence Phase

## Purpose

This phase adds human-in-the-loop reviewer persistence to the Data Quality Workbench while keeping migration safety constraints intact.

It introduces a dedicated staging review layer inside the new Smart Library application database.

## Safety Boundaries

- No write-back to legacy MARC SQL source data.
- No live SQL Server runtime dependency.
- No production import execution in this phase.
- No normalization engine execution in this phase.

Reviewer actions update only staging review entities in the new system.

## Persistence Model

New staging entities:

- `DataQualityIssueReview`
- `DataQualityIssueReviewNote`

Review status enum:

- `OPEN`
- `IN_REVIEW`
- `NEEDS_METADATA_COMPLETION`
- `DUPLICATE_CANDIDATE`
- `ESCALATED`
- `REVIEWED`

Stored reviewer metadata includes:

- deterministic issue key (`issueId`)
- source reference (`sourceTable`, `sourceRecordKey`)
- issue severity/class snapshot
- review status
- assignment (`assignedToUserId`)
- latest note and note history
- reviewer identity (`lastReviewedByUserId`)
- timestamps

## API Surface

Base path: `/api/v1/migration/data-quality`

Read:

- `GET /summary`
- `GET /issues`
- `GET /issues/:id`

Reviewer actions:

- `PATCH /issues/:id/review`
- `POST /issues/:id/notes`
- `PATCH /issues/:id/assign`

## Merge Strategy

Detected issues are still generated deterministically from committed artifacts.

The API merges each detected issue with persisted review state by deterministic `issueId`.

This keeps detection reproducible while enabling durable reviewer decisions.

## Access Rules

- `ADMIN`: full read/write.
- `ANALYST`: read-only visibility.
- `LIBRARIAN`: read/write within branch scope where branch ownership is known.

Fallback for unknown branch ownership:

- issue remains visible to librarians to avoid orphaned review work.
- this behavior is explicit and should be refined once branch mapping is fully normalized.

- `STUDENT`, `TEACHER`, `GUEST`: denied via role guards.

## Audit Events

Reviewer actions are written to audit log using:

- `DATA_QUALITY_REVIEW_STATUS_CHANGED`
- `DATA_QUALITY_NOTE_ADDED`
- `DATA_QUALITY_ISSUE_ASSIGNED`

## Still Postponed

- Actual correction workflows on legacy source records
- Normalization engine execution and automated remediation
- Production catalog import and cutover
- Full duplicate resolution workflow
