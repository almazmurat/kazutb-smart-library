---
name: "document"
description: "Update repo documentation and Obsidian memory after a Digital Library change so the next session starts from accurate context."
argument-hint: "Path to `docs/sdlc/current/verify.md` or a short summary of the completed change"
agent: "agent"
---

Use [Digital Library SDLC shared rules](../instructions/digital-library-sdlc.instructions.md), [AGENT_START_HERE](../../AGENT_START_HERE.md), [Obsidian vault architecture](../../docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md), and [AI SDLC workflow](../../docs/developer/AI_SDLC_WORKFLOW.md).

Treat the argument as the verification report path or final implementation summary.

## Your task
Perform **only the documentation / knowledge-update stage**.

### Required actions
1. Review the diff and the verification report.
2. Update only the docs that actually drifted, such as:
   - `README.md`
   - `project-context/*`
   - `docs/developer/*.md`
   - `docs/sdlc/*`
   - relevant Obsidian notes under `/home/admlibrary/knowledge/kazutb-library-vault/`
3. Refresh code-map notes with:
   - `composer dev:memory-refresh-maps`
4. Write a factual session handoff with:
   - `bash scripts/dev/obsidian-session-close.sh --summary "..." --verification "..." --next "..."`

### Documentation bar
- keep docs concise, factual, and linked
- do not leave stale architecture statements behind
- make the next session immediately understandable without re-explaining the project

End with a short merge/handoff checklist.
