# SDLC current workspace

Keep only **one active task trace** in the repo: `docs/sdlc/current/`.
Long-term history belongs in the Obsidian vault, and previous runs should be archived under `10-archive/sdlc-history/`.

## Minimal daily flow
You do **not** create everything by hand every time.

### A. Feature delivery
What you do:
1. Create or update `docs/sdlc/current/draft.md`
2. Write **one short line** like `улучшить фильтр каталога`
3. Run `/clarify`
4. Then run `/design` → `/implement` → `/verify` → `/document`

What the agent does inside `current/`:
- writes `spec.md`
- writes `plan.md`
- implements the code
- writes `verify.md`
- writes back the outcome to Obsidian

### B. Context / memory update
Use this when you want to save a new rule, problem, requirement, or platform fact.

What you do:
1. Put the rough note into `docs/sdlc/current/draft.md`, or run:
   `bash scripts/dev/new-sdlc-draft.sh --type context-update "инвентарный номер не должен повторяться"`
2. Run `/remember`

What the agent does:
- rewrites the rough note into clean repo truth + Obsidian notes
- updates linked memory/context without implementing product code unless you explicitly ask for it

## Optional helper
If you do not want to touch the file manually, run:

```bash
bash scripts/dev/new-sdlc-draft.sh "улучшить фильтр каталога"
```

This helper will:
- archive the previous `current/` trace to Obsidian if one exists
- refresh `docs/sdlc/current/draft.md`
- leave you ready to run `/clarify`

## Repo contents that should remain here
- `current/` — the only active work item
- `templates/` — reusable prompt scaffolding
- `README.md` — this short operator note
