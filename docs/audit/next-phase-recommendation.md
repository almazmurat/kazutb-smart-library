# Recommended Next-Phase Order (Post-Audit, 2026-03-19)

This order is adjusted to current reality, not legacy roadmap sequencing.

## Phase A — Product Credibility Hardening (Immediate)

Scope:
- Implement search end-to-end (API + frontend search page, at least basic ranking/filtering).
- Replace admin/catalog-management placeholders with minimum operational CRUD flows.
- Keep migration messaging accurate in UI labels and docs.

Why first:
- These are the fastest blockers to remove for believable demos and internal trust.

## Phase B — Frontend Quality and UX Hardening

Scope:
- Add frontend test stack (Vitest + React Testing Library).
- Cover critical user flows: catalog browse/details, reservation create/cancel, circulation issue/return, data-quality reviewer actions.
- Improve UX feedback quality (error states, optimistic handling, actionable validation messaging).

Why second:
- Current lack of frontend tests is a major regression risk multiplier for all future phases.

## Phase C — Admin and Governance Depth

Scope:
- Full admin user management UI wired to existing backend endpoints.
- Role/scope/branch assignment workflows.
- Initial settings management flow (currently scaffold backend).

Why third:
- Internal pilot requires credible governance and role administration, not only librarian operations.

## Phase D — Migration Engine Foundation (Executable, Non-Production)

Scope:
- Implement deterministic ETL runner for raw->clean->normalized stages.
- Add cross-table data quality checks and duplicate-detection foundations.
- Add dry-run import with reconciliation metrics, without production cutover.

Why fourth:
- Data-quality review persistence exists, but import readiness is still the largest technical gap.

## Phase E — Production-Oriented Hardening

Scope:
- Real LDAP integration and token/session lifecycle hardening.
- E2E smoke tests and CI enforcement.
- Operational runbooks, observability, incident ownership and rollback playbooks.

Why fifth:
- Production readiness should follow after product credibility and migration execution foundations are proven.

## Optional Parallel Stream

If team capacity allows, run this in parallel from Phase B onward:
- Digital files/viewer domain MVP (secure upload + view-only) as a constrained pilot capability.

## Recommended Short-Term Milestone Definition

A practical next milestone after Phases A+B:

- "Demo-ready MVP surface for core library operations"
  - real search
  - non-placeholder admin/catalog-management basics
  - tested critical frontend flows
  - unchanged migration safety boundaries explicitly communicated
