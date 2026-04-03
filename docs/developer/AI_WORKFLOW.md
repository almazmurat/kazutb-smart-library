# AI Development Workflow

For large Copilot CLI tasks:
1. put the task in prompts/<name>.md
2. run:
   Read @prompts/<name>.md and execute it.
   Read @AGENT_START_HERE.md and relevant @project-context files first.

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

## Session-End Ritual
After each substantial session:
1. Run `composer dev:session-snapshot`.
2. Run `composer dev:vault-sync`.
3. Run: `Read @prompts/session-closeout.md and execute it.`
4. Ensure at least one vault artifact is updated:
   - `09-daily-notes/YYYY-MM-DD.md`
   - `06-decisions/*.md`
   - `07-bugs-and-incidents/*.md`
   - `08-workstreams/current-workstreams.md`

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


## Optional extended knowledge base

An external Obsidian vault may be used for:
- architecture decisions
- incident notes
- workstream history
- daily summaries
- deeper domain explanations

**The vault is supplementary.**
Repository files remain the operational source of truth for Copilot execution.

## Prompt Layer Roles
- `prompts/`: day-to-day execution prompts used directly in Copilot CLI runs.
- `.github/prompts/`: reusable repository-level template prompts for shared team workflows.
- Keep active task execution centered on `prompts/` to reduce ambiguity.