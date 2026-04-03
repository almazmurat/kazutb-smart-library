---
mode: ask
model: GPT-5
description: Backend feature step with strict scope boundaries
---

Implement a backend step for this repository with strict boundary control.

Task:
- {{task}}

Required context to read first:
- AGENT_START_HERE.md
- project-context/00-project-truth.md
- project-context/01-current-stage.md
- project-context/06-current-focus.md
- project-context/03-api-contracts.md
- project-context/04-known-risks.md
- project-context/05-agent-working-rules.md
- project-context/02-active-roadmap.md

Constraints:
- Backend/domain-safe changes only.
- No frontend redesign or speculative tooling.
- Keep CRM scope frozen unless explicitly requested.

Output:
1. Scope framing (internal-only vs CRM-facing)
2. Minimal implementation plan
3. Code changes
4. Verification steps and results
5. Residual risks
