# QA and Verification

This directory is the retained QA surface for the repository.

It focuses on practical, repeatable verification for the Digital Library platform rather than a large set of overlapping narrative reports.

## Primary commands
```bash
composer qa:ci
npm run test:e2e:install
npm run test:e2e
composer qa:evidence
```

## Recommended reading order
Use these files as the main QA source of truth:
- [docs/qa/qa-test-strategy-document.md](qa-test-strategy-document.md) — verification scope and strategy
- [docs/qa/quality-gates.md](quality-gates.md) — local and CI gate definitions
- [docs/qa/ci-cd.md](ci-cd.md) — workflow and pipeline overview
- [docs/qa/evidence-index.md](evidence-index.md) — raw proof and evidence locations
- [docs/qa/final-qa-report.md](final-qa-report.md) — polished external report, if needed for review or submission

## Verification posture
The current QA baseline is intentionally risk-based and focused on the highest-value flows:
- authentication and session lifecycle
- catalog discovery and book detail
- reader account and reservation behavior
- protected internal boundaries
- frontend build and CI reproducibility

## Supporting notes
Some additional QA files still remain in the directory as transitional merge or archive candidates. They should not be treated as the primary long-term entry surface.

## Evidence location
Raw verification outputs are stored under [evidence/verification/](../../evidence/verification/).
