# Librarian Workflow: Data Quality Workbench

## Objective

Provide a consistent institutional workflow for reviewing and resolving migration data issues before import.

## Roles

- Librarian reviewer: validates bibliographic and branch semantics.
- Data engineer: maintains rules, transforms, and fix pipelines.
- Analyst/admin approver: approves promotion gates.

## Workflow

1. Select migration batch and stage (`raw`, `clean`, or `normalized`).
2. Filter issues by severity, class, branch, and source table.
3. Open issue details and inspect source context.
4. Apply one of review decisions:
   - `Approve fix`
   - `Request rule change`
   - `Accept as exception`
   - `Reject fix`
5. Add reviewer note with institutional rationale.
6. Re-run validation for impacted records.
7. Confirm issue status transition to `fixed` or approved exception.
8. Participate in gate decision for batch promotion.

## Review Rules

- `CRITICAL` and `HIGH` require explicit reviewer identity and comment.
- Exceptions are time-bound and must reference policy rationale.
- All branch ownership ambiguities must be resolved before promotion.
- PII-related fields require governance-aware handling.

## Suggested Queue Prioritization

1. `CRITICAL` referential and identity issues.
2. `HIGH` branch/status semantic issues.
3. `MEDIUM` normalization and vocabulary issues.
4. `LOW` cosmetic issues.

## Auditability

Each reviewed issue must keep:

- who reviewed it
- when it was reviewed
- what decision was taken
- why the decision was taken
- what changed (if any)

## Definition of Done for a Batch

- Blocking severities resolved.
- Exceptions documented and approved.
- Re-validation complete.
- Promotion recommendation recorded by responsible roles.
