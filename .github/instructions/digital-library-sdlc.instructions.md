---
description: "Shared rules for the KazUTB Smart Library five-stage SDLC prompts. Use when running /clarify, /design, /implement, /verify, or /document."
---

# KazUTB Smart Library — SDLC shared rules

## Grounding order
1. `AGENT_START_HERE.md`
2. `project-context/*`
3. `docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md`
4. Obsidian entrypoints and generated code maps in `/home/admlibrary/knowledge/kazutb-library-vault/`
5. The live code paths in `routes/`, `app/`, `resources/`, `database/`, and `tests/`

## Architecture truth
- This is a **reader-first library platform**, not a CRM-owned shell.
- Runtime stack: **Laravel 13 + Blade + React/Vite + PostgreSQL + Docker Compose**.
- CRM is a **bounded auth/integration client** and must **not** connect directly to the library database.
- Library-side domain behavior should follow existing patterns in `app/Services/Library/`, `app/Http/Controllers/Api/`, `resources/views/`, and `resources/js/spa/`.
- Route truth lives in `routes/web.php` and `routes/api.php`.

## Quality bars across all five stages
- Trace everything with stable IDs:
  - Requirements: `R1`, `R2`, ...
  - Use cases: `UC1`, `UC2`, ...
  - Plan steps: `S1`, `S2`, ...
  - Tests: `T1`, `T2`, ...
  - Verification checks: `V1`, `V2`, ...
- Do **not** add extra scope or “helpful” rewrites that were not requested.
- Prefer incremental, grounded changes over large speculative rewrites.
- For non-trivial code changes, use **red → green → refactor**.
- Before declaring success, verify with **real commands and outputs**.
- If repository docs or Obsidian notes drift, fix them in `/document`.

## Default artifact locations
- Active draft input: `docs/sdlc/current/draft.md`
- Clarify output: `docs/sdlc/current/spec.md`
- Design output: `docs/sdlc/current/plan.md`
- Verify output: `docs/sdlc/current/verify.md`
- Supporting template files live under `docs/sdlc/templates/`
- Previous `current/` traces should be archived to the Obsidian vault under `10-archive/sdlc-history/`

## Minimal input rule
- Treat a one-line `draft.md` as sufficient input.
- Do **not** require the user to fill full templates manually.
- Expand the rough idea into requirements, plan steps, and verification artifacts during the workflow.

## MCP-first execution rule
- Use MCP tools first when they materially improve grounding or verification.
- Prefer `filesystem`, `memory`, `fetch`, `context7`, and `playwright` over blind assumptions.
- For UI verification on critical flows, prefer Playwright when available.
- Treat the Obsidian vault as the long-term memory target for session and project writeback.

## Project-specific reminders
- Public reader flows, account behavior, reservations, circulation, and internal staff tools already exist — extend by pattern.
- Use Obsidian as the long-term knowledge graph, but treat repo files as execution truth.
- End meaningful implementation work with:
  `bash scripts/dev/obsidian-session-close.sh --summary "..." --verification "..." --next "..."`
