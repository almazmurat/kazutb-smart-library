# Copilot Instructions - KazUTB Smart Library

## Identity And Source Of Truth
- This repository is the core KazUTB Smart Library platform, not a demo scaffold.
- Before any substantial task, read files in this order:
  1. AGENT_START_HERE.md
  2. project-context/00-project-truth.md
  3. project-context/01-current-stage.md
  4. project-context/06-current-focus.md
  5. project-context/03-api-contracts.md
  6. project-context/04-known-risks.md
  7. project-context/05-agent-working-rules.md
  8. project-context/02-active-roadmap.md

## Current Execution Priorities
- Active window focus:
  - WS1 Public Catalog Convergence
  - WS4 Runtime E2E Verification Path
- Prioritize backend, database, integration correctness, and operational confidence.
- Sequence preference: core logic and data integrity first, UI later.

## Scope Boundaries (Do Not Drift)
- Do not treat CRM as domain owner. CRM is an auth and integration client.
- Do not move login UX ownership away from library platform.
- Do not expand CRM reservation API scope beyond current v1 unless explicitly requested.
- Do not propose generic re-platforming or blank-project bootstrap changes.
- Do not add speculative tools or architecture layers without concrete reuse value.

## Internal Vs CRM-Facing Work
- Internal-only work: circulation, copy lifecycle, diagnostics, review workflows, data stewardship.
- CRM-facing work: only bounded integration contract under /api/integration/v1 and existing auth handshake behavior.
- When making API changes, explicitly state whether they are internal-only or CRM-facing.

## Delivery Guidance
- Prefer minimal safe changes over broad refactors.
- Keep docs aligned with implemented behavior, not aspirational behavior.
- If duplicate paths exist, prefer canonical DB-backed routes and flag transitional paths.
- Use Copilot CLI as the primary execution agent for repository analysis, code changes, commands, and repeatable tasks.
- Use chat as architecture/controller layer for next-step selection, priority checks, anti-drift guardrails, roadmap, and handoff quality.

## Verification Expectations
- Every substantial change should include at least one targeted verification path.
- Prefer running focused tests first, then broader suites when risk warrants.
- For integration changes, verify both request discipline and response envelope compatibility.
- Risky backend or data mutation changes must include targeted tests.

## Commit Discipline
- Keep commits atomic and purpose-specific.
- Commit messages should include the impacted scope (internal, integration, docs, workflow).
- Do not mix workflow/configuration changes with product behavior changes in one commit unless explicitly asked.

## Response Pattern For Agents
For substantial tasks, include:
1. Stage-aware framing
2. Domain ownership alignment (Library core vs CRM integration)
3. In-scope and out-of-scope boundaries
4. Verification method and residual risk
