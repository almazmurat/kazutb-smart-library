# Gap Report (Prioritized, 2026-03-19)

## 1) Critical Gaps Before Leadership Demo

1. Remove or hide obvious placeholders from main narrative routes.
- Impact: leadership confidence loss when clicking admin/catalog-management pages.
- Evidence: placeholder scaffold notes in `frontend/src/features/admin/pages/admin-page.tsx` and `frontend/src/features/catalog-management/pages/*.tsx`.

2. Implement real search experience.
- Impact: core digital library expectation is unmet.
- Evidence: `backend/src/modules/search/search.service.ts` returns empty; `frontend/src/features/search/pages/search-page.tsx` redirects.

3. Tighten demo storyline around migration reality.
- Impact: risk of overstating migration readiness.
- Evidence: migration workbench is artifact-derived triage, not import execution.

4. Resolve doc inconsistencies shown in demo materials.
- Impact: stakeholder trust risk.
- Evidence: `docs/roadmap.md` and `docs/api/README.md` contain state mismatches.

## 2) Critical Gaps Before Internal Pilot

1. Admin UI depth for user/role/scope operations.
- Needed: user list, role update, branch/scope assignment, deactivation controls.
- Evidence: backend has endpoints; frontend admin is scaffold.

2. Catalog-management UI depth.
- Needed: real CRUD forms/validation for books, authors, categories, copies.
- Evidence: backend exists; frontend pages are placeholders.

3. Files/digital-material MVP capability.
- Needed: at least basic upload/secured view path for internal pilot scope.
- Evidence: `files.service` list returns empty.

4. Frontend quality safety net.
- Needed: Vitest + RTL baseline for critical pages and hooks.
- Evidence: no frontend test scripts/files.

## 3) Critical Gaps Before Real Migration/Import

1. Executable ETL pipeline with deterministic runs.
- Needed: raw->clean->normalized->import orchestration with reproducible outputs.

2. Broader quality detection and referential checks.
- Needed: cross-table rules (`DOC`/`INV`/`BOOKSTATES` etc.), duplicate identity strategy, branch ownership normalization.

3. Import dry-run + reconciliation framework.
- Needed: measurable count deltas, reject logs, rollback plan in executable workflows.

4. Governance hardening for migration decisions.
- Needed: explicit approval gates, exception handling policy, accountable signoff workflow.

## 4) Critical Gaps Before Production Readiness

1. Production auth integration.
- Needed: real LDAP/AD flow, refresh-token lifecycle controls, session invalidation.

2. End-to-end testing and CI quality gates.
- Needed: backend+frontend CI checks, smoke E2E, release confidence thresholds.

3. Operational maturity.
- Needed: deployment runbooks, monitoring/alerting traces, rollback operations, incident ownership mapping.

4. Security and compliance depth.
- Needed: formal threat review for file handling, PII, audit retention, access boundaries.

## Highest-Risk Weak Areas

1. Search absence in a library product surface.
2. Placeholder-heavy admin/catalog-management routes.
3. Migration execution gap despite visible migration/workbench UI.
4. Frontend untested runtime behavior.
5. Documentation drift that can mislead planning and demos.
