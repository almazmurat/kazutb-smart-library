# Integration Document Management v1 - Phase 1

Status: published for implementation in current phase.

Scope: document-level metadata management only.

Published endpoints under integration boundary:
- GET /api/integration/v1/documents
- GET /api/integration/v1/documents/{id}
- POST /api/integration/v1/documents
- PATCH /api/integration/v1/documents/{id}
- POST /api/integration/v1/documents/{id}/archive

## In scope
- Bibliographic/document metadata read.
- Metadata create and patch with explicit field whitelist.
- Non-destructive archive operation.
- Audit provenance for write operations.

## Allowed metadata fields (phase 1)
- title
- isbn
- publisher_id
- publication_year
- language
- description

## Not in scope
- Copy/item management.
- Hard delete.
- Bulk operations.
- CRM reservation API scope expansion.
- Any internal-only circulation or diagnostics endpoint exposure.

## Contract notes
- Integration boundary middleware and required headers apply.
- Error envelope follows integration style with error_code, reason_code, request_id, correlation_id, timestamp.
- Archive is non-destructive and schema-aware.
