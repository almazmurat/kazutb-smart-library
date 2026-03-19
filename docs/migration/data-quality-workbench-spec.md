# Data Quality Workbench (MVP Scaffold Spec)

## Scope

First UI scaffold for migration quality triage. This is a review-oriented foundation, not a full remediation engine.

Current phase status: the workbench is now connected to a read-only backend API that derives issues from committed legacy artifacts.

Current extension status: reviewer decisions are now persisted in the Smart Library staging database (review status, notes, assignment) without mutating legacy source records.

## MVP Capabilities

- Batch and stage selection UI.
- KPI summary cards (total, by severity, by status).
- Multi-filter panel (severity, issue class, source table, branch, status).
- Issue list table with institutional context.
- Read-only issue detail panel (source key, field, detection rule, summary).
- Persisted reviewer actions: status change, note creation, assignment.
- Audit events for reviewer actions.

## Implemented Detection Rules (Code)

- `missing_title`
- `missing_author`
- `missing_publication_year`
- `missing_language_code`
- `malformed_isbn`
- `incomplete_publication_metadata`
- `suspiciously_sparse_record`

All rules are currently computed from `docs/legacy-db-discovery/dbo_DOC_VIEW.csv` and artifact metadata.

## Planned Rules (Future)

- cross-table referential checks (`DOC` / `INV` / `BOOKSTATES`)
- duplicate identity collision checks
- branch ownership consistency checks
- policy/governance gate checks before import

## Non-Goals

- No direct write-back to legacy source.
- No automatic bulk correction in UI.
- No correction persistence or approval state writes in this phase.
- No full workflow orchestration backend in this phase.

## Safety and Separation

- Detection layer: deterministic, artifact-driven.
- Review layer: persisted in new app DB (`DataQualityIssueReview`, `DataQualityIssueReviewNote`).
- Legacy source remains read-only.

## Suggested Data Contract (Future)

```ts
export interface DataQualityIssue {
  id: string;
  batchId: string;
  stage: "raw" | "clean" | "normalized";
  severity: "CRITICAL" | "HIGH" | "MEDIUM" | "LOW";
  issueClass:
    | "IDENTITY"
    | "REFERENTIAL"
    | "SEMANTIC"
    | "FORMAT"
    | "GOVERNANCE"
    | "DERIVED";
  sourceTable: string;
  sourceRecordKey: string;
  branch?: string;
  fieldName?: string;
  status: "new" | "in_review" | "approved" | "rejected" | "fixed";
  autoFixable: boolean;
  detectedAt: string;
  reviewer?: string;
}
```

## UX Notes

- Must remain formal and institutional.
- Must support `kk`, `ru`, `en` labels.
- Must prioritize clear severity signaling and reviewer accountability.
- Must keep action affordances explicit and audit-friendly.
