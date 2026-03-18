# Data Quality Workbench (MVP Scaffold Spec)

## Scope

First UI scaffold for migration quality triage. This is a review-oriented foundation, not a full remediation engine.

## MVP Capabilities

- Batch and stage selection UI.
- KPI summary cards (total, by severity, by status).
- Multi-filter panel (severity, issue class, source table, branch, status).
- Issue list table with institutional context.
- Review panel placeholders for decision and notes.

## Non-Goals

- No direct write-back to legacy source.
- No automatic bulk correction in UI.
- No full workflow orchestration backend in this phase.

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
