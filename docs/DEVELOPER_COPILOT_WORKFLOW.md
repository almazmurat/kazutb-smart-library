# Developer Workflow: Copilot + Repository Context

## Purpose
Use this flow to reduce context drift and keep development aligned with library-domain priorities.

## Start Every Task
1. Read AGENT_START_HERE.md.
2. Read required project-context files listed there.
3. Restate task scope as one of:
   - Internal-only platform work
   - CRM-facing integration work
   - Workflow/documentation-only work

## Decide Internal-Only Vs CRM-Facing
- Internal-only if it changes circulation, copy management, review, diagnostics, or stewardship behavior.
- CRM-facing only if it affects existing integration contract behavior under /api/integration/v1.
- If uncertain, default to internal-only and verify contract impact before touching integration endpoints.

## Verify Changes
- Workflow/config-only changes: verify file presence and script execution help text.
- Internal backend changes: run targeted internal suites first.
- Integration changes: run reservation/integration suite and check response envelope compatibility.
- Data stewardship changes: run stewardship-focused suite and confirm diagnostics signals.

## Avoid Context Drift
- Do not introduce product features outside active roadmap priorities.
- Do not expand CRM API surface during convergence window unless explicitly requested.
- Keep implementation claims consistent with current stage wording in project-context/01-current-stage.md.

## Daily Practical Loop
1. Read context files
2. Implement minimal change
3. Run focused verification script
4. Summarize scope, verification, and risk in commit/PR notes
