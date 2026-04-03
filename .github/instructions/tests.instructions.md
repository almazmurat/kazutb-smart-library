---
applyTo: "tests/**/*.php"
---

For tests:
- Prefer targeted feature tests around changed behavior.
- Use existing repo testing patterns and container-aware verification paths.
- Avoid broad new test infrastructure unless explicitly requested.
- Keep regression coverage close to changed domain area.
- Any risky backend mutation should be accompanied by focused tests.
