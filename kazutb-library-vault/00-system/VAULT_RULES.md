# Vault Operating Rules

## The One Law
[[PROJECT_CONTEXT]] is the single source of truth.
Every note in this vault either derives from it, links to it, or supports it.
Nothing else is canonical. Nothing competes with it.

## Folder Purpose

| Folder | Purpose | Rule |
|--------|---------|------|
| `00-system/` | Vault rules and operating procedures | Read before every session |
| `01-master/` | Decomposed canonical truth from PROJECT_CONTEXT | Never add opinions, only facts |
| `02-memory/` | Session continuity, decisions, tasks, open questions | Update every session |
| `03-inbox/` | Raw intake only | Must be processed within 48h or deleted |
| `scripts/` | Vault maintenance automation | Run after major updates |

## Note Hygiene Rules
1. Every note MUST have at least one real wiki-link to another note
2. Every note in `01-master/` MUST link back to [[PROJECT_CONTEXT]]
3. No note can reference an archived or deleted file
4. `03-inbox/` notes older than 48h without processing = delete
5. No raw chat dumps, no paste dumps, no unprocessed fragments in live vault
6. All implementation decisions go to [[DECISIONS]]
7. All open questions go to [[OPEN_QUESTIONS]]
8. All current tasks go to [[CURRENT_STATE]]

## Linking Discipline
- Link concepts, not just mentions: if you reference auth, link [[AUTH_MODEL]]
- Use aliases for readability: [[AUTH_MODEL|authentication]]
- Never create a link that points to a non-existent file
- After creating a new note, add its link to [[GRAPH_INDEX]]

## Session Protocol
1. Start: open [[START_HERE]]
2. Read: [[CURRENT_STATE]] + [[OPEN_QUESTIONS]]
3. Work: create notes in `03-inbox/` or update `01-master/`
4. End: update [[CURRENT_STATE]], [[TASK_LOG]], move inbox to master
5. Never leave the session without updating memory

## What Goes Where

| Content Type | Location |
|---|---|
| Product decisions | [[DECISIONS]] |
| Feature specifications | `01-master/modules/` |
| Architectural truth | `01-master/architecture/` |
| Business rules / policies | `01-master/policies/` |
| Role and permission rules | `01-master/roles/` |
| Data model | `01-master/data/` |
| Raw notes / intake | `03-inbox/` |
| Tasks / next actions | [[CURRENT_STATE]] |
| Uncertainties | [[OPEN_QUESTIONS]] |
| Completed decisions | [[DECISIONS]] |

## Links
- [[PROJECT_CONTEXT]]
- [[SESSION_PROTOCOL]]
- [[GRAPH_INDEX]]
