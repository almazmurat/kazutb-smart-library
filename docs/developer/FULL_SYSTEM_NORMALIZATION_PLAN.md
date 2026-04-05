# Full System Normalization Plan

**Scope**: Internal developer/agent workflow normalization only.  
**Boundary**: This plan does not change runtime business behavior or CRM-facing contracts.

## 1. Executive summary

The repository already has a strong truth core (`AGENT_START_HERE.md`, `project-context/*`, `.github/copilot-instructions.md`) and an archive boundary (`docs/archive/`). The main normalization gap is operational coherence: there is not yet one fully explicit full-system operating model that ties startup context, prompt layers, script automation, and vault mirroring into one strict workflow.

This first wave keeps changes non-destructive and reversible. It adds missing automation helpers and codifies the final target model while preserving repo truth as canonical.

## 2. Final target operating model

### A. Execution truth layer in repo (canonical)

These files remain the execution source of truth:

- `AGENT_START_HERE.md`
- `project-context/00-project-truth.md`
- `project-context/01-current-stage.md`
- `project-context/02-active-roadmap.md`
- `project-context/03-api-contracts.md`
- `project-context/04-known-risks.md`
- `project-context/05-agent-working-rules.md`
- `project-context/06-current-focus.md`
- `project-context/98-product-master-context.md`
- `.github/copilot-instructions.md`

### B. Operational tooling layer

- **Prompts (canonical CLI workflows)**: `prompts/*.md`
- **IDE adapters (subordinate)**: `.github/prompts/*.prompt.md`
- **File-scoped coding rules**: `.github/instructions/*.instructions.md`
- **Automation scripts**: `scripts/dev/*.sh`
- **Single startup entrypoint**: `prompts/next-step.md` + `scripts/dev/show-startup-context.sh`

### C. Reference/archive layer

- **Active reference docs**: `docs/`, `docs/developer/`
- **Historical artifacts only**: `docs/archive/` (never default startup input)
- **Archive rule**: move, do not delete, unless explicitly confirmed safe

### D. Obsidian knowledge layer (non-canonical)

- Obsidian is a mirror/synthesis workspace.
- It may store work logs, decisions, investigations, and mirrored repo context.
- It must not replace repo truth for execution decisions.

## 3. Repo truth vs Obsidian boundary

### Canonical in repo only

- startup routing and rules
- stage/focus/roadmap truth
- API boundary truth
- canonical prompts and automation scripts

### Allowed in Obsidian

- session notes, daily logs, incident notes
- decisions with links back to repo files
- mirrored context snapshots (read-only mirrors)

### Forbidden as execution truth in Obsidian

- authoritative stage/focus/contract/rules replacements
- conflicting versions of startup instructions
- independent API boundary definitions

**Conflict rule**: if repo and vault differ, repo wins and vault must be updated.

## 4. Full repository classification

| Layer | Paths | Classification | Default startup? | Action |
|---|---|---|---|---|
| Truth core | `AGENT_START_HERE.md`, `project-context/*`, `.github/copilot-instructions.md` | canonical | yes | keep |
| Prompt core | `prompts/*.md` | canonical operational tooling | on demand | keep |
| IDE prompt adapters | `.github/prompts/*.prompt.md` | active but subordinate | no | keep |
| File-type instructions | `.github/instructions/*.instructions.md` | canonical scoped rules | auto-injected | keep |
| Dev scripts | `scripts/dev/*.sh` | canonical operational scripts | via composer | keep |
| Runtime code | `app/`, `routes/`, `resources/`, `database/`, `tests/`, `config/` | product/runtime truth | task-specific | keep |
| Active docs | `docs/`, `docs/developer/` | reference/support | task-specific | keep/targeted merge |
| Historical docs | `docs/archive/*` | historical archive | no | keep archived |
| External vault | `/home/admlibrary/knowledge/kazutb-library-vault` | mirror/synthesis | no | keep out of default startup |

## 5. Keep/merge/archive/delete-after-confirmation/ignore plan

### Keep
- all truth core files above
- `prompts/*.md`
- `.github/prompts/*.prompt.md`
- `.github/instructions/*.instructions.md`
- `scripts/dev/*.sh`
- active docs that describe implemented behavior

### Merge
- ✅ Merged `docs/DEVELOPER_COPILOT_WORKFLOW.md` into `docs/developer/AI_WORKFLOW.md` — old file is now a deprecation pointer
- ✅ Converted `.github/prompts/*.prompt.md` to thin adapters referencing canonical `prompts/*.md`

### Archive
- continue placing superseded phase docs into `docs/archive/`
- do not restore archived docs into default startup
- ✅ Archived superseded prompts: `repo-cleanup-and-architecture-audit.md`, `repo-normalization-audit.md`, `full-system-normalization-and-automation.md`, `full-cleanup-and-automation-wave.md`
- ✅ Archived `PHASE1_READINESS_CHECK.ps1` (Windows-only, historical)

### Delete only after confirmation
- no deletions in this wave
- candidate future deletion: `docs/DEVELOPER_COPILOT_WORKFLOW.md` deprecation stub when references are fully cleared

### Ignore by default startup
- `docs/archive/*`
- vault contents
- long audit prompts not needed for current task

## 6. Automation plan (safe first wave)

### Added in this wave

1. `scripts/dev/check-context-drift.sh`  
   Verifies canonical context files/scripts exist and key boundary lines are present.

2. `scripts/dev/export-context-to-vault.sh`  
   Mirrors canonical repo context files into vault mirror path without mutating repo truth.

3. `scripts/dev/sync-vault-index.sh`  
   Rebuilds a vault index note pointing to mirrored canonical files.

### Existing scripts retained

- `scripts/dev/show-startup-context.sh`
- `scripts/dev/session-start-checklist.sh`
- `scripts/dev/session-closeout.sh`
- `scripts/dev/session-snapshot.sh`
- `scripts/dev/vault-sync.sh`
- `scripts/dev/check-vault.sh`

### Workflow entrypoint

Start each new session with:
1. `composer dev:context`
2. `composer dev:session-start`
3. prompt execution via `prompts/next-step.md`

## 7. Concrete files created/updated

### Created
- `docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md`
- `docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md`
- `docs/developer/AGENT_AUTOMATION_WORKFLOW.md`
- `scripts/dev/check-context-drift.sh`
- `scripts/dev/export-context-to-vault.sh`
- `scripts/dev/sync-vault-index.sh`

### Updated
- `prompts/next-step.md` (normalized universal startup workflow)
- `scripts/dev/session-start-checklist.sh` (repo-first startup checklist)
- `scripts/dev/session-closeout.sh` (adds vault mirror/index sync in closeout flow)
- `composer.json` (new `dev:*` commands for normalization helpers)

## 8. Safe deletions requiring confirmation

None performed in this wave. The following are deprecated stubs that may be removed after full reference clearance:
- `docs/DEVELOPER_COPILOT_WORKFLOW.md` (now a deprecation pointer to `docs/developer/AI_WORKFLOW.md`)

## 9. Next best step

Run one full end-to-end session with the normalized flow (`dev:context` → `dev:session-start` → prompt execution → `dev:session-closeout`) and capture any friction points before considering further cleanup.
