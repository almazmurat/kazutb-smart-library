# Start here first — deterministic Digital Library context load

This file defines the **only active startup path** for new sessions.
Use it to avoid stale notes, duplicate context, and competing project truths.

## Read only this exact order
1. `project-context/00-canonical-platform-truth.md`
2. `project-context/01-runtime-stack-and-entrypoints.md`
3. `project-context/02-domain-boundaries-and-integrations.md`
4. `project-context/03-operational-realities-and-data-truth.md`
5. `project-context/04-active-delivery-focus-and-risks.md`
6. `docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md`
7. `docs/developer/AI_SDLC_WORKFLOW.md`
8. Obsidian entrypoints under `/home/admlibrary/knowledge/kazutb-library-vault/`:
   - `00-index/read this first before any new agent session.md`
   - `00-index/root context graph - kazutb smart library.md`
   - `00-index/current-next-step.md`
   - `08-workstreams/current-workstreams.md`
   - `07-bugs-and-incidents/active problem register - runtime, data, and integration.md`
   - `11-handoffs/latest-agent-handoff.md`

## Always remember
- Digital Library is the **new primary university library platform**, not a demo, not a thin website, and not a CRM shell.
- The library product owns catalog, search, holdings/copies, accounts, reservations, teacher workflows, stewardship, librarian/admin operations, digital materials, reporting, and future AI support.
- CRM is an **auth/integration boundary only** and must not dominate product framing or connect directly to the library DB.
- UDC, metadata quality, controlled digital access, holdings reality, and reporting compatibility are first-class truths.
- **Repo files are execution truth. Obsidian is the long-term memory graph.** If they disagree, repo truth wins and the vault must be updated.

## Default working mode
- Put a one-line task in `docs/sdlc/current/draft.md`.
- Run `/autopilot` for the shortest full loop.
- Use `/remember` only for context-memory updates.

## Mandatory closeout
After every meaningful task, run:
`bash scripts/dev/obsidian-session-close.sh --summary "..." --verification "..." --next "..."`

