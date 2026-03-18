# Migration Readiness Plan

## Target Flow

`Legacy DB -> Raw -> Clean -> Normalized -> PostgreSQL`

This plan defines readiness gates and measurable exit criteria before production migration.

## Stage 0: Legacy Freeze Window Definition

### Objectives

- Define extraction windows and freeze rules for stable snapshotting.
- Document source versioning and ownership responsibilities.

### Exit Criteria

- Named data owner for source system.
- Export runbook approved.
- Snapshot naming and checksum rules approved.

## Stage 1: Raw Zone Readiness (`migration/raw`)

### Controls

- Immutable snapshot policy.
- SHA-256 checksums for every exported artifact.
- Export manifest with row counts and object list.

### Exit Criteria

- All artifacts generated and checksummed.
- Export manifest signed by operator and reviewer.
- Re-export reproducibility tested once.

## Stage 2: Clean Zone Readiness (`migration/clean`)

### Controls

- Encoding normalization to UTF-8.
- Duplicate detection policy documented.
- Required-field validation rules implemented.
- Data quality issue logging enabled.

### Exit Criteria

- Cleaning pipeline deterministic on same input.
- Quality report produced with severity counts.
- High-severity unresolved issue rate below threshold.

## Stage 3: Normalized Zone Readiness (`migration/normalized`)

### Controls

- Legacy-to-target mapping spec versioned.
- Reference dictionary normalization (languages, branches, categories).
- Entity-level lineage (`legacy_record_id`, source table) retained.
- Reject-log with actionable reason codes.

### Exit Criteria

- Mapping conformance report generated.
- Sample-based business validation by librarian and analyst completed.
- All critical mapping ambiguities resolved or explicitly deferred.

## Stage 4: PostgreSQL Import Readiness

### Controls

- Idempotent import strategy (upsert or dedup key logic).
- Transaction and rollback strategy documented.
- Batch-level import logs and metrics captured.
- Post-import reconciliation queries prepared.

### Exit Criteria

- Dry run import succeeds in non-production environment.
- Reconciliation shows expected count tolerances.
- Performance baseline and run duration recorded.

## Stage 5: Cutover Readiness

### Controls

- Access model and branch segmentation verified.
- High-severity data quality backlog triaged.
- Support and rollback procedures documented.

### Exit Criteria

- Go-live checklist approved by product, data, and operations owners.
- User acceptance for librarian workflows signed.
- Incident response contacts and ownership matrix active.

## Core KPIs

- `kpi_raw_checksum_coverage`: 100%
- `kpi_clean_required_field_pass_rate`: >= 99%
- `kpi_normalized_mapping_success_rate`: >= 98%
- `kpi_high_severity_issue_rate`: <= agreed threshold
- `kpi_import_reconciliation_delta`: within approved tolerance

## Decision Gates

- **Gate A**: Raw artifacts accepted.
- **Gate B**: Clean quality threshold met.
- **Gate C**: Mapping quality accepted.
- **Gate D**: Dry run import accepted.
- **Gate E**: Production cutover approved.

No downstream stage should start without passing the current gate.
