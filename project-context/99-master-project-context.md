# 99 - Master Project Context (Long-Form)

This document stores the full long-form context for deep planning and architecture alignment.

Do not use this file alone for day-to-day execution.
Daily execution must start from:
- `AGENT_START_HERE.md`
- `project-context/00-project-truth.md`
- `project-context/01-current-stage.md`
- `project-context/06-current-focus.md`

## 1. Project Identity and Intent
- Project: "Цифровая умная библиотека KazUTB".
- Core intent: build a modern, operational, extensible library platform, not just a visual catalog refresh.
- This is a live, evolving codebase with implemented slices and known convergence debt.

The system is intended to become a real institutional platform that supports:
- public catalog/search and book detail UX,
- reader account and reservation interactions,
- library-side operational workflows,
- admin/librarian tools,
- data quality stewardship,
- reporting and analytics,
- digital and licensed resource directions.

## 2. Why This Program Exists
The prior ecosystem handled baseline library operations but constrained growth in:
- product UX quality,
- architecture clarity,
- data quality governance,
- operational transparency,
- scalable integrations and reporting.

The current program exists to move from fragmented legacy behavior to a coherent operational platform.

## 3. System Boundaries and Domain Ownership
### Library Platform (core domain owner)
- Owns product trajectory and domain model evolution.
- Owns reader-facing UX, catalog behavior, circulation logic, and internal operational interfaces.

### CRM (bounded role)
- Acts as auth provider.
- Acts as external/admin integration client.
- Consumes integration APIs where contractually allowed.

### Non-negotiable framing
- Library platform is the primary system.
- CRM is not the product center.
- Planning and messaging must avoid CRM-only drift.

## 4. Current Architecture Snapshot
- Backend stack in this repo: Laravel + PostgreSQL.
- UI surfaces include public and internal Blade views.
- Data landscape includes:
  - library-side app schema/read-layer entities/views,
  - legacy/integration-facing reservation tables,
  - review/data-quality layer.

Implemented and visible slices include:
- catalog and book detail read paths,
- account summary and identity matching behavior,
- internal circulation read/write APIs,
- internal health/review diagnostics,
- CRM-facing reservation integration boundary and v1 reservation API.

## 5. Product Surfaces by Audience
### Public/Reader
- Search, browse, and detail card flows are present.
- Reader account and reservation-related journeys are present but still under convergence hardening.

### Librarian/Admin (library-side)
- Direction is explicitly preserved as strategic.
- Operational surfaces exist but are not yet a full mature workspace.
- This gap is one of the top platform priorities.

## 6. Stage Assessment (Agreed)
Use this exact three-level framing:
- Whole platform: **advanced prototype transitioning to operational platform**.
- Library backend core: **early operational core**.
- CRM reservation integration slice: **pilot-ready**.

Interpretation:
- It is valid to call the reservation integration slice pilot-ready.
- It is not valid to label the whole platform as early production yet.

## 7. What Is Strong Today
- Integration boundary discipline and request envelope conventions.
- Reservation integration v1 read and mutate flows (approve/reject), including safety constraints.
- Internal diagnostics foundation for health and review visibility.
- DB-backed catalog/detail foundation already present.

## 8. Primary Gaps and Constraints
### A. Public path convergence gap
- Transitional/demo/proxy path history creates execution ambiguity.
- Need one canonical production path per critical public flow.

### B. Library-side operational product gap
- Admin/librarian workflows are not yet complete operationally.
- Must be treated as top-tier platform gap, not optional later polish.

### C. Data stewardship gap
- Monitoring exists, but correction/provenance governance is still maturing.
- Post-migration quality work requires explicit stewardship workflows.

### D. Runtime confidence gap
- Feature/contract confidence exists in slices, but runtime E2E assurance is uneven.
- Critical-path runtime verification needs systematic ownership.

### E. External dependency/auth risk
- Auth path depends on external CRM availability.
- Requires resilience and clearer runtime verification expectations.

## 9. Integration Contract Boundary
Current integration namespace: `/api/integration/v1`.

Current intended scope:
- reservation list,
- reservation detail,
- reservation approve,
- reservation reject,
- technical boundary ping.

Scope governance:
- Expansion beyond existing v1 is frozen in current convergence window.
- No new integration mutation endpoints unless explicitly reopened by product governance.

## 10. Active 2-Week Execution Map (Current Window)
### Week 1 priority workstreams
- WS1: Public Catalog Convergence.
- WS4: Runtime E2E Verification Path.

### Immediate next (after baseline)
- library-side operational workflows,
- data stewardship foundation.

### Follow-up layers
- auth/security hardening,
- reporting and resource layers,
- deferred advanced AI-assisted capabilities.

## 11. Runtime E2E Critical Paths (Canonical Set)
The runtime verification matrix should always include:
1. catalog search,
2. book detail,
3. account identity,
4. reservation list/detail,
5. reservation approve/reject,
6. internal circulation checkout/return.

For each path, keep:
- entrypoint,
- dependency chain,
- confidence level,
- likely failure points,
- minimum runtime verification method.

## 12. Data and Stewardship Context
The platform is in a post-migration quality phase, not in a "data solved" phase.

Operationally important principles:
- keep metadata integrity and traceability,
- preserve reportability and domain semantics,
- support human-in-the-loop correction,
- prepare for AI-assisted correction where appropriate,
- avoid opaque data changes without provenance.

## 13. Reporting, Digital, and Licensed Resource Direction
These areas are strategic but not yet fully implemented as mature platform layers.
They should remain visible in roadmap and stage discussions:
- reporting and analytics layer,
- digital materials controls,
- licensed/external resources integration behavior.

## 14. Agent Governance and Prompting Guidance
### Daily mode
- Read concise operational files first.
- Use this master file only for deep tasks.

### Decision discipline
Every substantial recommendation should state:
- stage-aware framing,
- domain ownership alignment,
- in-scope vs out-of-scope boundaries,
- explicit risks and verification method.

### Safety constraints
- Do not run generic new-project bootstrap patterns here.
- Do not replace project truth with tooling templates.
- Do not change runtime/business code for context-only tasks.

## 15. Working Definition of Success (Near-Term)
Near-term success is not "more API surface".
Near-term success is:
- convergence to canonical platform paths,
- improved runtime confidence on critical journeys,
- preserved library-side product direction,
- reduced strategy drift toward CRM-only framing.
