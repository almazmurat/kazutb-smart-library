---

# KazUTB Library Platform — Copilot Instructions

## MANDATORY: Read vault before every session

Before answering ANY question or making ANY change in this project,
you MUST read these files first:

1. `kazutb-library-vault/PROJECT_CONTEXT.md` — full product truth
2. `kazutb-library-vault/02-memory/CURRENT_STATE.md` — current status
3. `kazutb-library-vault/02-memory/OPEN_QUESTIONS.md` — open issues

## MANDATORY: Log important outputs to vault

After every significant interaction, you MUST suggest or perform:

### If you made a decision or recommendation:
Append to `kazutb-library-vault/02-memory/DECISIONS.md`:
[date] [decision title]
Decision: [what was decided] Reason: [why] Files affected: [if any]

### If you found an unresolved problem or question:
Append to `kazutb-library-vault/02-memory/OPEN_QUESTIONS.md`:
[PRIORITY] [question text]

Blocks: [what this blocks]
Context: [where this came from]

### If you completed a task or made changes to code:
Append to `kazutb-library-vault/02-memory/TASK_LOG.md`:
[date] [what was done]
Changed: [files/features] Status: [done/in-progress/blocked]

### If you discovered something that contradicts PROJECT_CONTEXT.md:
Flag it immediately and propose a correction.

## Project context summary

- Platform: KazUTB Smart Library (Laravel + Blade + PostgreSQL)
- Auth: CRM API at 10.0.1.47 → Bearer Token → LDAP/AD
- Roles: Guest, Member (student/teacher/employee), Librarian, Admin
- Primary truth: `kazutb-library-vault/PROJECT_CONTEXT.md`
- Vault: `kazutb-library-vault/` — Obsidian second brain

## Never do this

- Never ignore PROJECT_CONTEXT.md when making architectural decisions
- Never create routes/controllers/models that contradict the page map in PAGE_MAP.md
- Never allow file downloads for digital materials
- Never expose direct file URLs for protected content
- Never let guests access authenticated features
- Never delete vault .md files without updating GRAPH_INDEX.md

---
