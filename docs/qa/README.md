# QA and Verification

This directory contains the repository’s professional QA baseline: automated scope, quality gates, CI/CD design, verification metrics, and indexed evidence. The focus is repeatable engineering verification for the Digital Library platform, not coursework-style reporting.

## Current verification baseline
The repository intentionally verifies a **risk-based critical path** rather than claiming exhaustive monolith coverage. The defended baseline covers the most important public, auth, catalog, and staff-boundary flows.

### Primary commands
```bash
composer qa:ci
npm run test:e2e:install
npm run test:e2e
composer qa:evidence
```

### Latest verified results
- `composer qa:ci` → **77 passed** (`350 assertions`) and the Vite production build completed successfully
- `npm run test:e2e` → **3 passed** (`5.5s`)
- critical-path Clover line coverage → **4.24%** against the full `app/` namespace
- GitHub Actions run `24156292471` completed successfully with `Secret Scan`, `Backend QA & Coverage`, and `Frontend Build & Playwright Smoke`

## Document map
- `verification-report.md` — executive summary, failure-history audit, and stabilization results
- `test-scope.md` — risk-ranked automation scope, scenario design, and traceability
- `tooling-rationale.md` — why PHPUnit, Playwright, Pint, Docker, and GitHub Actions are the right fit here
- `metrics-report.md` — reproducible coverage, timing, and defect-tracking metrics
- `quality-gates.md` — local/CI gate definitions, thresholds, and failure handling
- `ci-cd.md` — workflow topology, action versions, and clean-runner behavior
- `evidence-index.md` — index of raw proof artifacts under `evidence/verification/`

## Evidence location
All supporting artifacts are organized under:

```text
evidence/verification/
```

## Verification posture
This QA area is intentionally honest about scope and limitations:
- strong coverage on the highest-risk flows
- deterministic clean-runner behavior in CI
- reproducible command paths for local and remote verification
- clear evidence mapping for reviewers and maintainers
