# DATA_CLEANUP_PANEL
> Derived from [[PROJECT_CONTEXT]] §24

## Purpose
This panel exists because the inherited MARC-SQL migration left anomalies, empty fields, duplicates, and malformed metadata that must be corrected operationally.

## Capabilities
- filter records by anomaly type
- inline edit faulty records
- run bulk correction tools with confirmation
- merge suspected duplicates
- view validation rules and change history

## Critical rules
No data loss, full audit trail, rollback support for critical fields, and preservation of library meaning are mandatory.

## Links
- [[PROJECT_CONTEXT]]
- [[DATA_MODEL]]
- [[AUDIT_LOG]]
