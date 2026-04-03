---
mode: ask
model: GPT-5
description: Verification step for internal core and integration confidence
---

Run a verification-oriented task for this repository.

Verification target:
- {{target}}

Process:
- Confirm scope alignment with project-context.
- Choose focused suite first, then broaden only if needed.
- Report pass/fail with concise diagnostic notes.

Suggested script options:
- composer test:internal
- composer test:reservation-core
- composer test:stewardship

Output:
1. What was verified
2. Commands executed
3. Results summary
4. Confidence level and remaining gaps
