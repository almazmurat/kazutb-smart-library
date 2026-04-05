# KazUTB Smart Library — Repo Normalization Plan

**Purpose**: Single canonical reference for how this repository is structured, what is authoritative, and what agents/developers should read by default.

**Last updated**: 2026-04-05 (repo normalization pass 2)

---

## Table 1 — Layer Map

| Path | Layer | Role | Canonical? | Default agent startup? |
|------|-------|------|-----------|----------------------|
| `AGENT_START_HERE.md` | Layer 1 — Policy/truth | Agent startup router | ✅ Yes | ✅ Always |
| `project-context/00-project-truth.md` | Layer 1 — Policy/truth | Product and domain truth | ✅ Yes | ✅ Always |
| `project-context/01-current-stage.md` | Layer 1 — Policy/truth | Current platform maturity | ✅ Yes | ✅ Always |
| `project-context/02-active-roadmap.md` | Layer 1 — Policy/truth | 2-week execution map | ✅ Yes | On planning tasks |
| `project-context/03-api-contracts.md` | Layer 1 — Policy/truth | API surface and boundary | ✅ Yes | On API tasks |
| `project-context/04-known-risks.md` | Layer 1 — Policy/truth | Active risk register | ✅ Yes | On planning tasks |
| `project-context/05-agent-working-rules.md` | Layer 1 — Policy/truth | Agent behavior rules | ✅ Yes | ✅ Always |
| `project-context/06-current-focus.md` | Layer 1 — Policy/truth | Active execution window | ✅ Yes | ✅ Always |
| `project-context/98-product-master-context.md` | Layer 1 — Policy/truth | Deep product/domain reference | ✅ Yes | On architecture/planning |
| `.github/copilot-instructions.md` | Layer 1 — Policy/truth | VS Code Copilot repo rules | ✅ Yes | Injected automatically |
| `.github/instructions/*.instructions.md` | Layer 1 — Policy/truth | File-scoped Copilot rules | ✅ Yes | Injected per file type |
| `prompts/*.md` | Layer 2 — Execution/tooling | Canonical repo task prompts (CLI) | ✅ Yes (canonical) | On demand |
| `.github/prompts/*.prompt.md` | Layer 2 — Execution/tooling | VS Code Copilot Chat adapters | Subordinate to `prompts/` | On demand (IDE) |
| `scripts/dev/*.sh` | Layer 2 — Execution/tooling | Dev/test/session scripts | ✅ Yes | Via `composer dev:*` |
| `.github/workflows/ci.yml` | Layer 2 — Execution/tooling | CI pipeline | ✅ Yes | Automatic on push |
| `agents/` | Layer 2 — Execution/tooling | Custom agent definitions | Yes (task-specific) | On demand |
| `app/`, `routes/`, `database/`, `tests/`, `resources/`, `config/`, `public/` | Product/runtime | Actual codebase | ✅ Yes | When code tasks active |
| `docs/` | Layer 3 — Reference | Technical docs and specs | Partial | On specific tasks |
| `docs/developer/` | Layer 3 — Reference | Developer workflow docs | Reference | On process questions |
| `docs/archive/` | Layer 3 — Reference | Historical phase records | ❌ No | Never by default |
| Obsidian vault | Layer 3 — Reference | Personal/team knowledge workspace | ❌ No (not repo truth) | Never |

---

## Table 2 — File Disposition

| Path | Keep | Merge | Archive | Delete after confirmation | Ignore by default |
|------|------|-------|---------|--------------------------|-------------------|
| `AGENT_START_HERE.md` | ✅ | | | | |
| `project-context/00-06` | ✅ | | | | |
| `project-context/98-product-master-context.md` | ✅ | | | | |
| `.github/copilot-instructions.md` | ✅ | | | | |
| `.github/instructions/*.instructions.md` | ✅ | | | | |
| `.github/prompts/*.prompt.md` | ✅ | | | | |
| `.github/workflows/ci.yml` | ✅ | | | | |
| `prompts/*.md` | ✅ | | | | |
| `scripts/dev/*.sh` | ✅ | | | | |
| `docs/integration-api-contract.json` | ✅ | | | | |
| `docs/INTEGRATION_21ST_SDK.md` | ✅ | | | | |
| `docs/COPILOT_MCP_SETUP.md` | ✅ | | | | |
| `docs/DEVELOPER_COPILOT_WORKFLOW.md` | | ✅ → into `AI_WORKFLOW.md` | | | |
| `docs/developer/AI_WORKFLOW.md` | ✅ (merge target) | | | | |
| `docs/developer/INTERNAL_REVIEW_TRIAGE_API.md` | ✅ | | | | |
| `docs/archive/*` | ✅ | | | | ✅ |
| `project-context/99-master-project-context.md` | | | ✅ done | | ✅ |
| `README.md` | ✅ (rewritten) | | | | |
| Runtime code dirs | ✅ | | | | |

---

## Table 3 — Canonical Map

| Area | Canonical file | Legacy/duplicates | Note |
|------|---------------|-------------------|------|
| Master project context | `project-context/98-product-master-context.md` | `docs/archive/99-master-project-context.md` | 99 archived |
| Current stage | `project-context/01-current-stage.md` | `docs/archive/PHASED_DEVELOPMENT_ROADMAP.md` | Roadmap doc archived |
| Current focus / execution window | `project-context/06-current-focus.md` | — | Reset each execution window |
| Active roadmap | `project-context/02-active-roadmap.md` | — | Single source |
| API surface and boundaries | `project-context/03-api-contracts.md` + `docs/integration-api-contract.json` | — | Both needed; different detail levels |
| Known risks | `project-context/04-known-risks.md` | — | Single source |
| Agent behavior rules | `project-context/05-agent-working-rules.md` | — | Single source |
| Agent startup routing | `AGENT_START_HERE.md` | — | Single source |
| Copilot repo behavior | `.github/copilot-instructions.md` | — | Auto-injected by VS Code |
| Developer workflow | `docs/developer/AI_WORKFLOW.md` | `docs/DEVELOPER_COPILOT_WORKFLOW.md` (pointer only now) | Merge pending |
| Canonical task prompts | `prompts/*.md` | `.github/prompts/*.prompt.md` (IDE adapters) | See Table 4 |
| Dev scripts | `scripts/dev/` | — | Single location |
| Historical phases | `docs/archive/` | Root-level phase docs (archived) | Archive complete |

---

## Table 4 — Prompt Boundary Map

Three prompt surfaces exist in this repo. They are **not duplicates** — they serve different invocation contexts.

| Surface | Location | Invoked by | Role | Canonical? |
|---------|----------|-----------|------|-----------|
| Repo task prompts | `prompts/*.md` | Copilot CLI, terminal, `@prompts/file.md` in chat | **Canonical** full-length task workflows | ✅ Yes — canonical layer |
| VS Code Chat adapters | `.github/prompts/*.prompt.md` | VS Code Copilot Chat slash commands | Thin entry points for IDE workflow | Subordinate to `prompts/` |
| File-scoped rules | `.github/instructions/*.instructions.md` | Injected by VS Code per matching file pattern | Per-file type coding rules | ✅ Yes — scope rules |

**Rule**: Full prompt logic lives in `prompts/`. `.github/prompts/` files may call or reference `prompts/` content but must not duplicate it.

### Current prompt inventory

| File in `prompts/` | `.github/prompts/` equivalent | Overlap? |
|--------------------|-------------------------------|---------|
| `backend-step.md` | `backend-feature-step.prompt.md` | Conceptually similar, different depth |
| `hardening-step.md` | `hardening-step.prompt.md` | Conceptually similar, different depth |
| `crm-handoff.md` | `crm-handoff-step.prompt.md` | Conceptually similar, different depth |
| `verification-step.md` | `verification-step.prompt.md` | Conceptually similar, different depth |
| `next-step.md` | — | CLI-only |
| `session-closeout.md` | — | CLI-only |
| `repo-cleanup-and-architecture-audit.md` | — | CLI-only |
| `repo-normalization-audit.md` | — | CLI-only |

---

## Table 5 — Obsidian Boundary Rules

| Content type | Allowed in vault | Mirror-only (link to repo) | Forbidden as execution truth |
|-------------|-----------------|---------------------------|------------------------------|
| Session notes and work logs | ✅ Yes | | |
| Investigation notes / sketches | ✅ Yes | | |
| Meeting notes | ✅ Yes | | |
| Architecture brainstorming | ✅ Yes | | |
| Personal synthesis and ideas | ✅ Yes | | |
| Future ideas / parking lot | ✅ Yes | | |
| Decision logs (with repo links) | ✅ Yes | | |
| Summaries of `project-context/` files | | ✅ Mirror/link only | |
| Roadmap overview | | ✅ Mirror/link only | |
| API contract summaries | | ✅ Mirror/link only | |
| Canonical current stage | | | ❌ Must live in `project-context/01` |
| Active roadmap authority | | | ❌ Must live in `project-context/02` |
| API contract truth | | | ❌ Must live in `project-context/03` |
| Agent rules | | | ❌ Must live in `project-context/05` |
| Canonical startup instructions | | | ❌ Must live in `AGENT_START_HERE.md` |

**Conflict resolution rule**: If vault and repo disagree → **repo wins**. Vault requires update.

---

## Pending actions

| Action | Status | Priority |
|--------|--------|---------|
| Merge `docs/DEVELOPER_COPILOT_WORKFLOW.md` into `docs/developer/AI_WORKFLOW.md` | Pending | Medium |
| Rewrite README as project README (remove Laravel boilerplate) | Done | High |
| Create `scripts/dev/show-startup-context.sh` | Done | Medium |
| Add `.github/workflows/ci.yml` | ✅ Done | High |
| Archive root-level phase artifacts | ✅ Done | High |
| Archive `docs/` phase step docs | ✅ Done | High |
| Archive `project-context/99` | ✅ Done | High |
