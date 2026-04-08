# Assignment 2 — Test Automation Implementation

This folder contains the **defense-ready Assignment 2 package** for the KazUTB Digital Library repository. It was rebuilt through an adversarial audit on **2026-04-08** so that the documentation matches the real implementation, real evidence, and real CI behavior.

## Submission verdict
**Current verdict: PASS with documented limitations**

The repository does **not** claim complete full-product automation. Instead, it defends a **risk-based critical-path automation scope** covering the highest-risk reader, auth, catalog, and staff-boundary flows.

## Core verification commands
```bash
composer qa:ci
npm run test:e2e:install
npm run test:e2e
composer qa:assignment2-evidence
```

## Latest verified local results
- `composer qa:ci` → **77 passed** (`350 assertions`), Vite build succeeded
- `npm run test:e2e` → **3 passed** (`5.4s`)
- Clover line coverage for the defended critical-path suite → **4.24%** against the full `app/` namespace

## Document map
- `audit-report.md` — strict requirement-by-requirement audit and compliance matrix
- `test-scope.md` — high-risk module selection, case design, traceability, and evidence mapping
- `metrics-report.md` — reproducible metrics, timings, coverage, and defects-vs-risk tables
- `quality-gates.md` — gate definitions, thresholds, failure behavior, and alerting model
- `ci-cd.md` — GitHub Actions workflow explanation and artifact flow
- `evidence-index.md` — inventory of raw proof files in `evidence/a2/`

## Evidence location
All raw supporting artifacts are stored in:

```text
evidence/a2/
```

## Important honesty note
The strongest defense posture for this assignment is to present the work as a **targeted automation implementation** with explicit limitations, not as blanket full-system coverage.
