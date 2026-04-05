# MCP Adoption Plan (Phased, Safe, Reversible)

## Scope
This setup is **internal-only developer tooling**.
It does not change runtime behavior, API contracts, or CRM-facing surfaces.

## Decision Summary

| MCP area | Status now | Decision | Why |
|---|---|---|---|
| GitHub MCP server | Added (baseline) | Use built-in GitHub MCP available in Copilot CLI | Highest day-to-day value with no repo secrets or runtime impact |
| Repository/test/tooling MCP (Context7 docs) | Added as optional path | Keep optional and enable per-user only when needed | Improves Laravel/PHPUnit/Vite doc accuracy with low risk |
| Browser MCP | Postponed | Do not enable by default in repo | Requires browser/runtime provisioning and can introduce flaky/non-deterministic checks |
| Frontend UI generation MCP (21st Magic or equivalent) | Postponed | Do not enable by default in repo | Current execution focus is convergence/runtime confidence, not UI generation expansion |
| Additional repo/test/tooling MCPs (DB/API-contract/internal-docs MCPs) | Postponed | Re-evaluate when repeated operational need appears | Keep MCP surface small, explicit, and reversible |

## What Was Added In This Step
1. `scripts/dev/check-mcp-readiness.sh` (readiness checks only; no configuration side effects).
2. Composer command: `composer dev:mcp-check`.
3. This document updated to explicitly track what is added now vs postponed.

## Safe Baseline Usage
1. Run `composer dev:mcp-check`.
2. In interactive Copilot CLI, run `/mcp list`.
3. Keep GitHub MCP enabled as baseline.
4. Add optional Context7 only when documentation certainty is needed.

## Explicitly Postponed For Now
- Browser MCP auto-configuration.
- UI-generation MCP auto-configuration.
- Repo-local MCP config files with credentials/secrets.

These are intentionally deferred until a concrete task needs them and local user-level setup is justified.

## Reversibility
All changes are low-risk and easy to rollback:
- remove `scripts/dev/check-mcp-readiness.sh`,
- remove `dev:mcp-check` from `composer.json`,
- revert this document update.
