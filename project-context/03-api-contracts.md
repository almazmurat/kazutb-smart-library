# 03 - API Contracts (Operational Snapshot)

Deep-dive reference: `project-context/99-master-project-context.md`.

## Public/Library Surface
- Public catalog/search and book detail exist in DB-backed form.
- Account summary exists with session + reader identity matching metadata.
- Internal circulation read/write endpoints exist for staff-scoped operations.
- Internal ops endpoints exist for health and review issue monitoring.

## Integration Surface (CRM-facing)
- Boundary namespace: `/api/integration/v1`.
- Current contract scope:
  - reservations list,
  - reservation detail,
  - reservation approve,
  - reservation reject,
  - technical boundary ping.
- Request discipline includes required integration headers and consistent request/correlation context.

## Contract Governance Rules
- Preserve backward-compatible response envelopes unless explicitly planned.
- Treat current integration v1 as frozen for scope expansion.
- Prefer canonical library-side paths; avoid proliferating parallel endpoint behavior.

## Convergence Note
Where duplicate/transitional paths exist, agents must prefer canonical DB-backed routes and flag deviations as convergence debt.
