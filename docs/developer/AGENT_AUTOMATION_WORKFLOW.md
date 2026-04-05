# Agent Automation Workflow

**Scope**: Internal execution workflow for Copilot CLI and developers.  
**Boundary**: Repo remains execution truth; vault is mirror/log layer.

## 1. Single obvious next-step entrypoint

Use:
- `prompts/next-step.md` as canonical execution prompt,
- `scripts/dev/show-startup-context.sh` for context visibility.

This creates one clear flow instead of ad-hoc startup behavior.

## 2. Session startup workflow

1. `composer dev:check`  
   Environment sanity.
2. `composer dev:context`  
   Print startup context and layer map.
3. `composer dev:session-start`  
   Structured checklist for repo-truth-first startup.
4. Execute next task via:
   - `Read @prompts/next-step.md and execute it.`

## 3. In-session guardrails

- Classify task before changes (internal-only / CRM-facing / backend-db / frontend / stewardship / hardening).
- Avoid scope drift beyond current `project-context/06-current-focus.md`.
- Prefer narrow, verifiable changes.
- Keep CRM integration scope frozen unless explicitly reopened.

## 4. Session closeout workflow

1. `composer dev:session-snapshot`
2. `composer dev:vault-sync`
3. `composer dev:vault-context-export`
4. `composer dev:vault-index-sync`
5. `composer dev:session-closeout`
6. Prompt closeout:
   - `Read @prompts/session-closeout.md and execute it.`

## 5. Drift and consistency checks

- `composer dev:context-drift` verifies canonical context and boundary signals.
- `composer dev:catalog-paths` keeps WS1 canonical route wiring visible.
- `composer dev:mcp-check` keeps MCP baseline status explicit.

## 6. Repo truth vs Obsidian automation boundary

### Repo-only authority
- startup truth
- stage/focus/roadmap
- API contracts/boundaries
- prompts/scripts used by agents

### Vault automation role
- mirror canonical files for graph/navigation
- store session logs/decisions/incidents
- maintain human context continuity

### Never automate
- writing canonical repo truth from vault notes
- replacing repo context with vault content

## 7. Practical command map

- Startup context: `composer dev:context`
- Startup checklist: `composer dev:session-start`
- Context drift check: `composer dev:context-drift`
- Vault structure check: `composer dev:vault-check`
- Vault daily sync: `composer dev:vault-sync`
- Vault context mirror: `composer dev:vault-context-export`
- Vault index refresh: `composer dev:vault-index-sync`
- Session closeout automation: `composer dev:session-closeout`
