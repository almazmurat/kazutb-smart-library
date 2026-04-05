# Developer Copilot Workflow

## Prompt layer map (two layers, different surfaces)

### Layer 1 — VS Code Copilot Chat prompts
Location: `.github/prompts/*.prompt.md`
Invoked by: VS Code Copilot Chat slash commands
Purpose: task-step templates used inside VS Code while editing code
Files: `backend-feature-step`, `hardening-step`, `crm-handoff-step`, `verification-step`

### Layer 2 — CLI / terminal prompts
Location: `prompts/*.md`
Invoked by: Copilot CLI sessions, manual terminal use, or `@prompts/file.md` references in chat
Purpose: longer workflow audits, session management, multi-step analysis
Files: `backend-step`, `hardening-step`, `crm-handoff`, `verification-step`, `next-step`, `session-closeout`, `repo-cleanup-and-architecture-audit`, `repo-normalization-audit`

**Rule**: The two layers are not duplicates. They serve different interaction surfaces.
Do not merge them. Update each separately when workflows change.

## Canonical workflow reference
See: `docs/developer/AI_WORKFLOW.md`

## Agent startup order
See: `AGENT_START_HERE.md`
