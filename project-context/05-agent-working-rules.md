# 05 - Agent Working Rules

Deep-dive reference: `project-context/99-master-project-context.md`.

## Scope Rules
- This repository is a living product codebase.
- Do not run generic blank-project bootstrap workflows.
- Do not overwrite project truth with template assumptions.

## Priority Rules
- Prioritize library-platform convergence over new integration breadth.
- Keep CRM reservation API scope frozen unless user explicitly reopens scope.
- Protect reader UX in library platform.
- Preserve library-side admin/librarian direction in planning decisions.

## Change Safety Rules
- Default to minimal changes and verify impact.
- Do not modify runtime/business code when user asks for context/planning-only tasks.
- Keep docs accurate to implemented behavior; avoid speculative claims.

## Prompting Rules for Future Tasks
Every substantial task response should include:
1. stage-aware framing,
2. domain ownership alignment (Library core vs CRM integration),
3. explicit in-scope and out-of-scope boundaries,
4. risks and verification method.

## GSD/Copilot Integration Rules (Soft)
- Use GSD workflows as orchestration support, not as architecture authority.
- If GSD guidance conflicts with project truth here, project truth wins.
- Always read `AGENT_START_HERE.md` first in new sessions.
