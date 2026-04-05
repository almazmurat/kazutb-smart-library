Start by reading these files first, before planning or code changes:

- `AGENT_START_HERE.md`
- `.github/copilot-instructions.md`
- `project-context/00-project-truth.md`
- `project-context/01-current-stage.md`
- `project-context/02-active-roadmap.md`
- `project-context/03-api-contracts.md`
- `project-context/04-known-risks.md`
- `project-context/05-agent-working-rules.md`
- `project-context/06-current-focus.md`
- `project-context/98-product-master-context.md`
- `docs/developer/REPO_NORMALIZATION_PLAN.md`
- `docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md`
- `docs/developer/AGENT_AUTOMATION_WORKFLOW.md`
- `README.md`

Task:

Execute one strict, narrow, high-value next step for the current execution window.

Required process:

1. Classify scope before implementation:
   - internal-only
   - CRM-facing
   - backend/db
   - frontend
   - stewardship
   - hardening
2. State in-scope and out-of-scope boundaries.
3. Prefer minimal safe changes over broad refactors.
4. Add focused verification for changed behavior.
5. Update docs only when behavior, boundaries, or operational workflow changed.

Critical guardrails:

- Repository files are execution truth.
- Obsidian/vault notes are mirror/synthesis only.
- Do not treat Obsidian as execution truth.
- Do not treat vault notes as canonical source of truth.
- Do not broaden CRM integration scope beyond current v1 boundary unless explicitly requested.
- Do not perform destructive deletions unless clearly safe and explicitly justified.
- If duplicate or stale artifacts are found, classify first; prefer freeze/archive over deletion.

If the task includes context/workflow cleanup:

1. Build classification map first (canonical / active / archive / transitional / delete-after-confirmation).
2. Keep runtime/product code untouched unless explicitly required.
3. Keep archive materials out of default startup context.
4. Keep startup path explicit:
   - `composer dev:context`
   - `composer dev:session-start`
   - execute requested prompt/task

Output format:

1. Executive summary
2. Scope classification
3. Changes made
4. Verification performed
5. Files updated
6. Remaining transitional items
7. Next best step
