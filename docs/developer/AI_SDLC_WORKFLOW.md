# AI SDLC Workflow for KazUTB Smart Library

This repository now has a project-specific **AI delivery loop** built around reusable slash commands.

### Default one-command mode
- `/autopilot` — read a one-line `docs/sdlc/current/draft.md`, route to the minimum full loop needed, verify with evidence, and write the result back to Obsidian

### Manual five-stage mode
1. `/clarify`
2. `/design`
3. `/implement`
4. `/verify`
5. `/document`

### Context-only mode
- `/remember` — normalize rough platform facts, corrections, risks, and requirements into repo/Obsidian memory without shipping feature code

These commands are workspace-scoped and live in `.github/prompts/` so they can be used directly inside Copilot Chat for this project.

---

## Why this workflow fits this repo

The workflow is adapted to the actual architecture of **Digital Library KazTBU**, not to a generic sample stack.

### Project facts it assumes
- **Backend:** Laravel 13
- **Frontend:** Blade views + React/Vite islands and SPA shell
- **Database:** PostgreSQL
- **Runtime:** Docker Compose
- **Boundary:** CRM provides auth/integration via HTTP API and must not connect directly to the library DB
- **Long-term memory:** Obsidian vault at `/home/admlibrary/knowledge/kazutb-library-vault`

### Why “vibe coding” breaks here
Large one-shot prompts tend to fail in this repo for the same reasons they fail elsewhere, but the cost is higher because this codebase has:
- multiple UI layers (Blade + React)
- session auth plus CRM-boundary rules
- database-backed operational flows
- long-lived documentation and Obsidian memory that must stay accurate

The fix is a staged workflow with **traceability** and **verification** at every step.

---

## The five-stage pipeline

```mermaid
flowchart LR
    A[current/draft.md] --> B[/clarify/]
    B --> C[current/spec.md]
    C --> D[/design/]
    D --> E[current/plan.md]
    E --> F[/implement/]
    F --> G[code + tests + diff]
    G --> H[/verify/]
    H --> I[current/verify.md]
    I --> J[/document/]
    J --> K[repo docs + Obsidian updated]
```

### Stage summary

| Stage | Input | Output | Main responsibility |
|---|---|---|---|
| `/clarify` | rough `draft.md` or free text | `docs/sdlc/current/spec.md` | turn intent into explicit, testable requirements |
| `/design` | approved spec | `docs/sdlc/current/plan.md` | break the change into file-level, contract-aware implementation steps |
| `/implement` | approved plan | code + tests + diff | ship the change incrementally and with evidence |
| `/verify` | spec/plan + diff | `docs/sdlc/current/verify.md` | prove the implementation matches the requirements |
| `/document` | verify report + diff | updated repo docs + Obsidian handoff | remove documentation drift and prepare the next session |

---

## Traceability rules from Clarify to Verify

Every stage uses stable IDs so requirements remain visible all the way to the end.

| Artifact | ID pattern | Purpose |
|---|---|---|
| Requirement | `R1`, `R2`, ... | what must be true |
| Use case | `UC1`, `UC2`, ... | concrete user flow / E2E scenario |
| Plan step | `S1`, `S2`, ... | implementation action |
| Test | `T1`, `T2`, ... | verification target |
| Verify check | `V1`, `V2`, ... | final evidence point |

### Minimum chain
- `/clarify`: `draft -> R#/UC#`
- `/design`: `R#/UC# -> S#/T#`
- `/implement`: `S#/T# -> code + executed checks`
- `/verify`: `R#/UC# -> evidence + PASS/WARN/FAIL`
- `/document`: update docs/vault to reflect the verified truth

If a requirement cannot be traced from spec to verification, the change is **not done**.

---

## Command behavior adapted to this repo

### `/autopilot`
Use this as the default path when the operator gives a one-line task in `docs/sdlc/current/draft.md`.

It should:
- decide whether the task is feature delivery or context-memory maintenance
- run the smallest grounded path needed instead of forcing manual stage selection every time
- still leave behind verification evidence, updated docs/memory, and an Obsidian task/session log

### `/clarify`
Use when you want manual control over the first planning stage or need to refine a fuzzy feature request.

It should:
- read repo truth first (`AGENT_START_HERE.md`, `project-context/*`)
- read the Obsidian memory architecture and code-map notes
- inspect the real code paths likely to change
- ask clarifying questions only when they materially affect the implementation
- produce a spec with **testable** requirements and E2E-ready user flows

### `/design`
Use when the spec is approved.

It should:
- map each requirement to concrete files and steps
- separate backend / frontend / database / CRM-auth / testing concerns
- define payloads, service contracts, and data effects explicitly
- include a self-review pass before showing the plan

### `/implement`
Use when the plan is approved.

It should:
- implement in small batches, not as one giant rewrite
- use **red → green → refactor** for non-trivial changes
- predict which tests should pass before running them
- compare prediction vs actual output and investigate mismatches immediately
- follow repo patterns instead of inventing new architecture

### `/verify`
Use after implementation is complete.

It should verify, with real evidence:
- targeted tests
- critical-path tests when relevant
- `pint` / style validation
- `npm run build` when UI assets changed
- requirement traceability
- dead code, security, boundary rules, and docs drift

### `/document`
Use after `/verify` is clean or nearly clean.

It should:
- update only the docs that actually changed
- refresh generated code-map notes
- update Obsidian handoff and daily note
- leave the repo and vault consistent for the next session

### `/remember`
Use when the input is not a feature request but a new project fact, rule, correction, operational nuance, or domain requirement.

It should:
- read rough notes from chat or `docs/sdlc/current/draft.md`
- normalize them into concise repo truth plus richer Obsidian memory
- update the right domain/workstream/decision notes automatically
- avoid shipping product code unless you explicitly ask for implementation

---

## Recommended free/standard MCP stack for this project

These are the default useful MCP servers for the current stack and workflow.

| Server | Purpose in this repo | Free / standard | Notes |
|---|---|---|---|
| `filesystem` | inspect/write local project and vault files | ✅ | core for repo + Obsidian |
| `memory` | persistent structured memory | ✅ | complements the vault workflow |
| `fetch` | pull external docs/pages | ✅ | useful for package docs and references |
| `context7` | library/framework documentation grounding | ✅ | avoids hallucinated framework APIs |
| `playwright` | browser/E2E checks for reader and staff flows | ✅ | ideal for `/verify` on UI changes |
| `stitch` | UI/design exploration for frontend modernization | ✅ with Google API key | useful for inspiration and reference capture during frontend work; adapt designs instead of cloning proprietary assets verbatim |
| `github` | repo / PR context | ✅ with PAT | useful when changes are reviewed via GitHub |
| `postgres` | DB/schema inspection | ✅ with connection string | optional but strong for DB-heavy tasks |

Workspace config is stored in `.vscode/mcp.json`.

---

## Working convention for feature artifacts

Recommended active folder layout:

```text
docs/sdlc/current/
  draft.md
  spec.md
  plan.md
  verify.md
```

Templates live in `docs/sdlc/templates/`, and older finished runs should be archived to the Obsidian vault under `10-archive/sdlc-history/`.

---

## Quick start

### Minimal operator mode
For normal day-to-day work, you only need to do this:

1. Create or update `docs/sdlc/current/draft.md`
2. Write one short line, for example: `улучшить фильтр каталога`
3. Run `/autopilot`

If you need finer control, fall back to `/clarify` → `/design` → `/implement` → `/verify` → `/document`.

You do **not** manually create the spec, plan, verify report, or documentation checklist each time. The workflow and writeback trail should be generated automatically.

### Why repo docs still exist
- `docs/developer/*` = stable operating rules for the agent
- `docs/sdlc/*` = short-lived execution trace for the current task
- Obsidian vault = long-term evolving project memory

So the repo should stay **small and operational**, while the knowledge graph grows in Obsidian.

---

## Good defaults for this repository

- Prefer **granular features** over large epics.
- If the change spans multiple layers, implement in this order:
  `DB/data -> backend -> frontend -> verify -> docs`
- If a UI change touches reader or staff flows, plan at least one Playwright scenario.
- If a change alters startup truth, update `project-context/*` and relevant Obsidian notes.
- End meaningful work with:

```bash
bash scripts/dev/obsidian-session-close.sh \
  --summary "..." \
  --verification "..." \
  --next "..."
```

---

## Questions worth tuning later

The workflow now has good defaults, but these are useful team decisions to refine next:
- where feature artifacts should live (`docs/sdlc/` vs `.planning/`)
- whether `/verify` should block on `WARN` or only on `FAIL`
- which UI changes require mandatory Playwright coverage
- whether branch naming / commit scaffolding should become part of the workflow
- whether a future `/debug` or `/commit` command should be added
