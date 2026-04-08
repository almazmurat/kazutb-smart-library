Start each session by reading `AGENT_START_HERE.md`.

Rules:
- Treat repo files as execution truth.
- Treat the Obsidian vault at `/home/admlibrary/knowledge/kazutb-library-vault` as the long-term connected memory graph.
- If repo truth and vault notes differ, update the vault to match the repo.
- For staged feature delivery, use the workspace slash commands in `.github/prompts/`: `/clarify`, `/design`, `/implement`, `/verify`, `/document`.
- The orchestration guide lives in `docs/developer/AI_SDLC_WORKFLOW.md`.
- Daily workflow should be minimal: the user may provide only a one-line `docs/sdlc/current/draft.md`, and the agent should generate the rest.
- Use MCP tools first when relevant (`filesystem`, `memory`, `fetch`, `context7`, `playwright`, `github`, `postgres`) so work stays grounded and verifiable.
- Keep repo docs minimal and operational; prefer Obsidian for long-term evolving context and session memory.
- Before ending a session, write back the result with `bash scripts/dev/obsidian-session-close.sh --summary "..." --verification "..." --next "..."`.
- Keep notes factual, linked, and concise.