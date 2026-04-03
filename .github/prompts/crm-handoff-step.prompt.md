---
mode: ask
model: GPT-5
description: CRM handoff step for integration-safe changes only
---

Prepare a CRM handoff for integration-facing work.

Handoff scope:
- {{scope}}

Rules:
- Keep CRM as integration/admin/auth client, not domain owner.
- Confirm no expansion beyond frozen integration v1 contract unless explicitly approved.
- Preserve existing request headers, correlation context, and response envelope compatibility.

Output:
1. Changed endpoints/behaviors (if any)
2. Backward-compatibility check
3. Required CRM-side updates (if any)
4. Verification commands and results
5. Rollback/safety notes
