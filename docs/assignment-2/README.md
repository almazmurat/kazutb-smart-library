# Assignment 2 — QA Automation, CI/CD, and Publication Hardening

This folder contains the assignment-ready documentation for the repository hardening pass completed on **2026-04-08**.

## Deliverables covered
- repository publication safety and secret-hygiene improvements
- critical-path backend regression automation
- browser smoke coverage with Playwright
- GitHub Actions CI/CD workflows for verification and release packaging
- evidence indexing for screenshots, logs, and command output

## Core verification commands
```bash
composer qa:ci
npm run test:e2e:install
npm run test:e2e
```

## Verified results
- `composer qa:ci` → **77 passed** (`350 assertions`), frontend build succeeded
- `npm run test:e2e` → **3 passed** (`5.4s`)

## Document map
- `test-scope.md` — automated test coverage included in the submission
- `quality-gates.md` — local quality gate commands and thresholds
- `ci-cd.md` — GitHub Actions pipeline structure and release packaging
- `evidence-index.md` — index of supporting screenshots and text artifacts under `evidence/a2/`

## Evidence location
All raw supporting artifacts are stored in:

```text
evidence/a2/
```
