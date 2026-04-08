---
name: "remember"
description: "Normalize a rough project fact, correction, risk, or platform note into structured KazUTB Smart Library memory in the repo and Obsidian vault."
argument-hint: "Path to `docs/sdlc/current/draft.md` or a raw note about project truth, roles, requirements, risks, or operations"
agent: "agent"
---

Use [Digital Library SDLC shared rules](../instructions/digital-library-sdlc.instructions.md), [AGENT_START_HERE](../../AGENT_START_HERE.md), [Obsidian vault architecture](../../docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md), and [AI SDLC workflow](../../docs/developer/AI_SDLC_WORKFLOW.md).

Treat the user argument as either:
- a path to `draft.md`, or
- a free-text note about project truth, requirements, policies, roles, risks, reporting, or domain reality.

## Your task
Perform **only the memory / context update stage**.

### Required steps
1. Read the current repo truth and the relevant Obsidian notes.
2. Normalize the rough note into concise, linked, agent-readable context.
3. Update only the impacted sources, such as:
   - `project-context/*`
   - `docs/developer/*` if workflow truth changed
   - relevant Obsidian notes under `/home/admlibrary/knowledge/kazutb-library-vault/`
4. If the note introduces a standing product or architecture rule, add or update a dated decision note under `06-decisions/`.
5. If the note implies future implementation work, capture that in the right workstream/next-step notes, but do **not** implement feature code unless explicitly asked.
6. Keep repo docs minimal and put richer, linked narrative in Obsidian.

### Output bar
- rewrite rough notes into structured memory the next session can trust
- preserve important domain meaning, audit/reporting constraints, and product boundaries
- avoid stale or duplicated statements
- end with a short summary of what context changed and any follow-up questions
