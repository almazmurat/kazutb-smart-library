---
name: "clarify"
description: "Turn a rough feature draft into a structured spec for KazUTB Smart Library. Use when you have `draft.md`, free text requirements, or a fuzzy feature request."
argument-hint: "Path to draft.md or a short feature request"
agent: "agent"
---

Use [Digital Library SDLC shared rules](../instructions/digital-library-sdlc.instructions.md), [AGENT_START_HERE](../../AGENT_START_HERE.md), and [AI SDLC workflow](../../docs/developer/AI_SDLC_WORKFLOW.md).

Treat the user argument as either:
- a path to `draft.md`, or
- a free-text feature request.

## Your task
Perform **only the clarification/specification stage**.

### Required steps
1. Ground yourself in the repository truth and Obsidian architecture/memory notes.
2. Inspect the actual code paths that are likely to be affected.
3. Ask concise clarifying questions **only** if the missing answer would materially change the design. Otherwise, state assumptions explicitly.
4. Create or update the active feature spec at:
   `docs/sdlc/current/spec.md`
   using the template in [feature-spec.template.md](../../docs/sdlc/templates/feature-spec.template.md).

### The spec must include
- problem statement and scope
- numbered requirements `R1..Rn`
- concrete user/use flows `UC1..UCn` that can later become Playwright scenarios
- explicit in-scope / out-of-scope boundaries
- impacted architecture areas: `backend`, `frontend`, `database`, `CRM/auth`, `tests`, `docs`, `Obsidian`
- assumptions and open questions
- a short `Ready for /design` section

### Hard constraints
- Keep the feature aligned with this repo’s architecture: Laravel, Blade/React/Vite, PostgreSQL, Docker, and CRM-bounded auth.
- Do not invent endpoints, database fields, or UI surfaces that are not grounded in the codebase.
- Make every requirement testable and traceable into later stages.
