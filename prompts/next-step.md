Start by reading:
- `AGENT_START_HERE.md`
- `.github/copilot-instructions.md`
- `project-context/00-project-truth.md`
- `project-context/01-current-stage.md`
- `project-context/02-active-roadmap.md`
- `project-context/03-api-contracts.md`
- `project-context/04-known-risks.md`
- `project-context/05-agent-working-rules.md`
- `project-context/06-current-focus.md`
- `project-context/98-product-master-context.md`
- `docs/developer/REPO_NORMALIZATION_PLAN.md`
- `docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md`
- `docs/developer/AGENT_AUTOMATION_WORKFLOW.md`
- `docs/developer/PUBLIC_CATALOG_CONVERGENCE_AUDIT.md`
- `README.md`
- `composer.json`
- all relevant files in `scripts/dev/`
- all relevant workflow files in `.github/workflows/`
- all relevant tests under `tests/`
- all relevant runtime entrypoints in:
  - `routes/`
  - `app/Http/Controllers/`
  - `resources/views/`
  - `resources/js/` if relevant

Context:
Truth-layer normalization, prompt/tooling cleanup, bounded Obsidian mirror workflow, and WS1 Public Catalog Convergence work have already been completed.
Do not restart normalization.
Do not redesign the knowledge system.
The next task is **WS4 Runtime E2E Verification Path**.

Task:
Perform a strict, repository-grounded audit and implementation pass for the runtime verification path of the KazUTB Smart Library project.

Primary goal:
Make the critical runtime path more explicit, more testable, and more operationally verifiable without speculative refactoring.

Critical path to audit and verify:
- catalog search
- book detail
- account identity
- reservation list/detail
- reservation approve/reject
- internal circulation checkout/return

What to do:

## 1. Map the real runtime verification surface
Identify how each critical path is currently verified, if at all.

For each path, inspect:
- routes
- controllers/services
- relevant views or SPA entrypoints
- feature/integration tests
- helper scripts
- CI workflow coverage
- docker/runtime probes if relevant

Classify each critical path as:
- verified well
- partially verified
- weakly verified
- only unit-tested
- only documented
- not meaningfully verified
- blocked by environment/runtime limits

## 2. Build a runtime verification matrix
Create or update a single clear matrix that shows for each critical path:
- canonical route(s)
- canonical API route(s)
- main controller/service entry
- current test coverage
- runtime/manual verification method
- CI coverage status
- current confidence level
- main gap/blocker

This matrix should be practical, not abstract.

## 3. Strengthen verification in the highest-value areas
Implement a narrow, high-value verification improvement pass.

Prioritize:
- missing or weak checks for canonical public paths
- weak coverage on account identity/runtime auth flow
- gaps in reservation list/detail or approve/reject path verification
- circulation checkout/return critical-path confidence
- missing helper scripts or grouped test commands if they improve real operational confidence

Do not try to solve every possible test problem.
Focus on the minimum improvements that materially increase confidence.

## 4. Normalize verification entrypoints
Make the runtime verification flow easier to run for future sessions.

If clearly justified, create or update:
- a runtime verification doc
- a helper script for critical-path verification
- composer commands for grouped verification runs
- CI references or scripts that align with current canonical paths

Prefer a small number of useful entrypoints over many overlapping helpers.

## 5. Be honest about environment limits
If verification cannot be completed in the current environment, explicitly document:
- what was verified,
- what could not be verified,
- why,
- what exact environment is needed,
- what fallback confidence method was used.

Do not claim verification that did not happen.

## 6. Update docs/scripts/tests where justified
If useful and grounded, create or update:
- `docs/developer/RUNTIME_VERIFICATION_MATRIX.md`
- `scripts/dev/check-runtime-critical-paths.sh`
- composer scripts for runtime verification
- small focused tests that improve confidence on the critical path
- CI references if a minimal improvement is clearly safe

Do not introduce broad speculative test architecture changes.

Important:
- Be strict and repository-grounded.
- Do not restart repository normalization work.
- Do not jump into another full cleanup wave.
- Do not redesign runtime architecture.
- Do not expand product scope.
- Prefer narrow, useful, verifiable improvements.
- Preserve stage-aware honesty.

Output format:
1. Executive summary
2. Runtime verification matrix
3. What was strengthened
4. Remaining weak points / blockers
5. Verification actually performed
6. Files created/updated
7. Next best step

Definition of done:
- The critical runtime path is explicitly mapped.
- Verification confidence is clearer than before.
- At least one meaningful verification improvement has been implemented.
- The project has a more usable runtime verification entrypoint for future sessions.
- Any environment limitations are documented honestly.