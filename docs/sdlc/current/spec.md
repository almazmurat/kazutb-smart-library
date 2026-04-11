# assignment-2-qa-automation-package — specification

## Metadata
- status: draft
- source draft: `docs/sdlc/current/draft.md`
- owner: GitHub Copilot
- date: 2026-04-11

## Problem statement
The repository already contains a real QA baseline, but the user now needs the project to be **explicitly prepared for Assignment 2: Test Automation Implementation**. That means the repo must not only have working automation, but also a clear, submission-ready package covering risk analysis, tool selection, test scope, quality gates, CI/CD, metrics, evidence, and research-paper-ready documentation.

## Scope
### In scope
- analyze the live repo and map its current QA baseline to the Assignment 2 deliverables
- add a submission-ready documentation package under `docs/qa/`
- refresh the active QA docs with current verified metrics and evidence paths
- harden local Playwright execution if a reproducibility issue blocks `npm run test:e2e`
- verify the result with real commands and fresh evidence

### Out of scope
- redesigning unrelated product features
- expanding the application into unrelated domains outside the library platform
- claiming exhaustive monolith automation that does not actually exist

## Architecture areas touched
- automation config: `playwright.config.ts`
- browser smoke suite: `tests/e2e/public-smoke.spec.ts`
- QA / assignment docs: `docs/qa/*`
- active SDLC trace: `docs/sdlc/current/spec.md`, `plan.md`, `verify.md`
- evidence refresh: `evidence/verification/*`

## Requirements
- `R1.` The repo must expose a clear, repo-specific Assignment 2 package with practical tables and deliverable mapping.
- `R2.` The package must be grounded in the actual Digital Library architecture, routes, tests, and CI workflow.
- `R3.` Local browser automation must execute reproducibly without manual cleanup when old report folders are not writable.
- `R4.` The smoke suite must assert the **current** homepage behavior, not stale copy from earlier UI iterations.
- `R5.` Fresh verification evidence must exist for `composer qa:ci`, `npm run test:e2e`, and `composer qa:evidence`.

## User / system flows
- `UC1.` A reviewer opens `docs/qa/assignment-2-submission-pack.md` and can map the real repo to the assignment checklist immediately.
- `UC2.` A maintainer runs `composer qa:ci` and `npm run test:e2e` successfully on the current repo state.
- `UC3.` A reviewer can locate fresh reproducibility evidence under `evidence/verification/`.

## Acceptance criteria
- `AC1.` New QA documents exist and are suitable for submission / research-paper reuse.
- `AC2.` `npm run test:e2e` passes after the Playwright hardening change.
- `AC3.` The QA docs reference fresh 2026-04-11 verification evidence instead of stale numbers only.

## Assumptions
- A risk-based automation package is acceptable and preferred over pretending full-system test completeness.
- Existing PHPUnit + Playwright + GitHub Actions are the right primary tools for this repo.
- Minimal, high-value automation hardening is preferable to large speculative rewrites.

## Open questions
- Whether the final submission should be exported in English only or bilingual format.
- Whether the instructor wants additional screenshots from GitHub Actions UI beyond the current log/evidence set.

## Ready for /design
- yes
- notes:
  - The task is specific enough to move directly into a documentation + verification hardening plan.
  - The repo already contains most baseline artifacts; the work is mainly packaging, refreshing, and stabilizing them.