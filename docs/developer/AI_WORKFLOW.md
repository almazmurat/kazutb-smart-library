# AI Development Workflow

## Operating Roles
- Copilot CLI: primary execution agent (repo analysis, code edits, commands, repeatable tasks).
- Chat: architecture and course-control layer (priorities, anti-drift, next strong step, handoff).

## Before Starting Any Task
1. Read AGENT_START_HERE.md.
2. Read relevant files in project-context/ in the required order.
3. Classify task scope:
   - internal-only
   - CRM-facing
   - backend/db
   - frontend
   - stewardship
   - hardening

## Default Execution Rule
Prefer this order:
1. narrow backend/database step
2. targeted tests
3. docs updates if behavior/scope changed
4. atomic commit

## Do Not Do These By Default
- Do not expand CRM API surface casually.
- Do not jump to frontend when backend/core scope is active.
- Do not add generic CRUD without domain justification.
- Do not guess DB semantics when they can be verified from schema/tests/runtime.

## Verification
Use real repository verification paths:
- targeted phpunit/artisan test runs
- containerized runs where applicable
- route or endpoint checks where needed
- runtime smoke checks for critical paths

## Daily Practical Loop
1. Open repo at root in VS Code.
2. Start Copilot CLI in repo root.
3. Start each task with: read AGENT_START_HERE.md and project-context first.
4. Implement minimal safe step.
5. Run focused script under scripts/dev/.
6. Summarize verification and residual risks in commit/PR notes.

## VS Code Practical Setup
- Keep workspace rooted at repository root.
- Keep these files pinned for fast context:
   - AGENT_START_HERE.md
   - project-context/00-project-truth.md
   - project-context/01-current-stage.md
   - .github/copilot-instructions.md
   - docs/developer/AI_WORKFLOW.md

## Environment Sanity Check
- Run `composer dev:check` before beginning significant work.
- If Context7 is needed and `npx ctx7 --help` fails, upgrade Node to 20+ and retry.
