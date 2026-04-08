---
name: "implement"
description: "Implement a Digital Library feature from its approved plan using grounded, incremental, test-first changes."
argument-hint: "Path to `docs/sdlc/current/plan.md`"
agent: "agent"
---

Use [Digital Library SDLC shared rules](../instructions/digital-library-sdlc.instructions.md), [AGENT_START_HERE](../../AGENT_START_HERE.md), and [AI SDLC workflow](../../docs/developer/AI_SDLC_WORKFLOW.md).

Treat the argument as the path to the plan file created by `/design`.

## Your task
Perform **only the implementation stage**.

### Non-negotiable implementation rules
- Work in **small batches**, not one giant rewrite.
- Before each batch, state which `R#`, `UC#`, and `S#` items you are implementing.
- For non-trivial work, follow **red → green → refactor**.
- Predict which tests/build checks should pass before running them.
- After each meaningful change, run the smallest relevant verification command and compare the result with the prediction.
- If prediction and reality differ, stop guessing and investigate before making more changes.

### Project-specific defaults
- Prefer existing patterns in `app/Services/Library/`, `app/Http/Controllers/Api/`, `resources/views/`, `resources/js/spa/`, and `tests/`.
- If framework/library behavior is uncertain, use MCP/documentation sources instead of inventing APIs.
- If the feature spans layers, implement in an order that preserves contracts: data → backend → frontend → docs.

### Expected result
- the code changes are implemented
- relevant tests/build checks are run with evidence
- the plan file can be updated with status/checkmarks if helpful
- the work is left in a state that is explicitly `Ready for /verify`
