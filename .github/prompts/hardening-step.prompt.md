---
mode: ask
model: GPT-5
description: Hardening step for reliability and boundary safety
---

Execute a hardening step for this repository.

Hardening target:
- {{target}}

Guardrails:
- No product feature expansion.
- No CRM API scope growth beyond v1 behavior.
- Preserve backward-compatible envelopes unless explicitly changed.

Expectations:
- Prefer minimal edits with clear rollback points.
- Add or run focused tests for changed risk surface.
- Highlight assumptions and open risks.

Output:
1. Hardening scope and threat/risk being reduced
2. Exact changes made
3. Verification evidence
4. Follow-up hardening opportunities (if any)
