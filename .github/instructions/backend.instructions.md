---
applyTo: "app/**/*.php,routes/**/*.php"
---

For backend changes:
- Prefer service/controller patterns already present in the repo.
- Preserve domain integrity over generic CRUD convenience.
- Keep internal-only and CRM-facing API surfaces clearly separated.
- Sensitive mutations should be auditable.
- Do not broaden scope beyond the requested backend step.
- Prioritize core logic and database correctness before UI work.
