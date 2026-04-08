# Active delivery focus and risks

## Current delivery focus
- keep the strict repo-truth + Obsidian-memory startup model deterministic
- use one-line `docs/sdlc/current/draft.md` intake plus `/autopilot` as the preferred default workflow
- preserve the verified editorial institutional homepage reset and continue the broader cross-page visual system from the Stitch direction
- deepen the product around discovery, UDC, digital materials, external resources, data stewardship, and librarian/admin capabilities

## Active automation rules
- use `/autopilot` for the shortest full-loop path: clarify/design/implement/verify/document or memory-only routing when appropriate
- use `/remember` for pure context or project-memory updates that do not ship product code
- every meaningful task should leave an Obsidian writeback trail: daily note, handoff, and task log node

## Known risks
- Docker app/runtime rebuilds may still be needed before code changes appear live
- live verification may depend on Vite state, `public/hot`, and writable Laravel cache paths
- some checks depend on seeded PostgreSQL data and reachable CRM auth endpoints
- Playwright coverage remains partial until local browser dependencies are fully installed
- repo noise under generated evidence/artifacts can confuse future sessions if treated as active truth

## Session discipline
Before claiming completion, verify with real commands and real outputs. End meaningful work with:
`bash scripts/dev/obsidian-session-close.sh --summary "..." --verification "..." --next "..."`
