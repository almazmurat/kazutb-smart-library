---
name: "verify"
description: "Run a structured verification pass for a Digital Library change: tests, build, traceability, security, dead code, and docs drift."
argument-hint: "Path to `docs/sdlc/current/spec.md` or `docs/sdlc/current/plan.md`"
agent: "agent"
---

Use [Digital Library SDLC shared rules](../instructions/digital-library-sdlc.instructions.md), [AGENT_START_HERE](../../AGENT_START_HERE.md), and [AI SDLC workflow](../../docs/developer/AI_SDLC_WORKFLOW.md).

Treat the argument as the spec or plan path for the feature under review.

## Your task
Perform **only the verification/review stage**.

### Required outputs
Create or update:
`docs/sdlc/current/verify.md`
using [verification-report.template.md](../../docs/sdlc/templates/verification-report.template.md).

### Verification checklist
1. **Requirement traceability** — every `R#` and `UC#` must map to code and evidence.
2. **Build/test evidence** — run the appropriate real commands for the changed area. Common repo commands include:
   - `./vendor/bin/pint --test`
   - `php artisan test --filter <relevant tests>`
   - `composer test:critical-paths`
   - `npm run build`
   - Playwright/browser checks if public or staff UI flows changed
3. **Dead code scan** — unused imports, routes, services, exports, or stale comments.
4. **Security/auth review** — session/auth guards, integration boundary, CORS, injection risks, role leakage.
5. **Contract consistency** — frontend/backend/database/API behavior matches the plan.
6. **Docs drift** — identify any repo markdown or Obsidian notes that are now stale.

### Output format
- `PASS`, `WARN`, or `FAIL` for each check
- exact commands run and a short evidence summary
- blockers if anything is not ready
- a short `Ready for /document` section when clean
