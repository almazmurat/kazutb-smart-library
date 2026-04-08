---
name: "design"
description: "Turn a KazUTB Smart Library feature spec into a concrete implementation plan with file-level steps, contracts, and tests."
argument-hint: "Path to `docs/sdlc/current/spec.md`"
agent: "agent"
---

Use [Digital Library SDLC shared rules](../instructions/digital-library-sdlc.instructions.md), [AGENT_START_HERE](../../AGENT_START_HERE.md), the generated code maps in the Obsidian vault, and [AI SDLC workflow](../../docs/developer/AI_SDLC_WORKFLOW.md).

Treat the argument as the path to the spec file created by `/clarify`.

## Your task
Perform **only the design/planning stage**.

### Required steps
1. Read the spec fully and extract `R#` and `UC#` IDs.
2. Ground the plan in the live codebase:
   - `routes/web.php`, `routes/api.php`
   - `app/Http/Controllers/Api/`
   - `app/Services/Library/`
   - `resources/views/`, `resources/js/spa/`
   - `database/migrations/`, `tests/`
3. Create or update:
   `docs/sdlc/current/plan.md`
   using [implementation-plan.template.md](../../docs/sdlc/templates/implementation-plan.template.md).

### The plan must include
- a traceability matrix mapping each `R#` to one or more plan steps `S#`
- impacted files and likely edit locations
- backend / frontend / database / CRM-auth / test work broken into small steps
- internal and external contracts (routes, payloads, service interfaces, DB effects)
- test plan `T1..Tn` covering unit, integration, and E2E where relevant
- rollback / migration risk notes
- explicit `not in scope` reminders to prevent overbuilding

### Self-review before finalizing
- every requirement is covered by at least one step
- no step implements scope that is absent from the spec
- the plan is the simplest viable change that matches current repo conventions
- verification commands are listed and realistic for this repository

End with a short `Ready for /implement` section.
