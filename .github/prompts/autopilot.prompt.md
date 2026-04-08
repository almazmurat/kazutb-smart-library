---
name: "autopilot"
description: "Run the shortest grounded KazUTB delivery loop from a one-line draft: route to context-memory update or go through clarify/design/implement/verify/document with real verification evidence."
argument-hint: "Optional path to docs/sdlc/current/draft.md or a short task request"
agent: "agent"
---

Use [Digital Library SDLC shared rules](../instructions/digital-library-sdlc.instructions.md), [AGENT_START_HERE](../../AGENT_START_HERE.md), [AI SDLC workflow](../../docs/developer/AI_SDLC_WORKFLOW.md), and [Obsidian vault architecture](../../docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md).

Treat the argument as either:
- a path to `docs/sdlc/current/draft.md`, or
- a one-line task request.

## Your task
Run the **minimum complete delivery loop** needed for this input.

### Required routing behavior
1. Ground yourself in the repo truth, active runtime, and Obsidian startup notes.
2. Decide whether the request is primarily:
   - **feature delivery** → produce/update `spec.md`, `plan.md`, implement the change, verify it with real commands, and document the verified truth; or
   - **context / memory update** → normalize the truth into repo + Obsidian memory without shipping unrelated product code.
3. If the input is ambiguous, make the smallest reasonable assumption and state it explicitly instead of blocking.

### Non-negotiable execution rules
- Prefer the one-line draft in `docs/sdlc/current/draft.md` as sufficient input.
- Use small, grounded, testable changes rather than a giant speculative rewrite.
- For non-trivial code work, follow **red → green → refactor**.
- Verify success with real commands/output before claiming completion.
- Update the correct docs and Obsidian memory so the next session starts from accurate truth.
- Finish every meaningful run with the Obsidian writeback trail: daily note, latest handoff, and a per-task log node.
- Keep per-task traceability whenever possible: requirements, plan steps, tests, and verification evidence.

### Expected result
When the task is done, the workspace should be left in a state that is:
- implemented or memory-updated as appropriate
- verified with evidence
- documented with minimal drift
- written back to the Obsidian task/session trail
