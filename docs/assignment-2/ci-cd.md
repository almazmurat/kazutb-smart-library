# CI/CD Overview

## Main workflow
The primary verification workflow is:

```text
.github/workflows/ci.yml
```

### Trigger conditions
- push to `main`
- pull requests targeting `main`
- manual `workflow_dispatch`

## Step-by-step pipeline

| Order | Job | What it does | Why it matters for Assignment 2 |
|---|---|---|---|
| 1 | `secret-scan` | checks the repository with Gitleaks | proves the automation pipeline includes security hygiene, not just test execution |
| 2 | `backend-quality` | installs PHP deps, runs `composer qa:ci`, generates JUnit + Clover artifacts, then enforces the coverage floor | proves automated backend verification and reproducible test artifacts |
| 3 | `browser-smoke` | installs Node deps, builds assets, installs Playwright Chromium, runs smoke tests, uploads traces | proves UI-facing automation and CI browser integration |

## Reproducibility characteristics
- the workflows are **version-controlled** in the repository
- they can be triggered automatically or manually with `workflow_dispatch`
- failures are visible at commit/PR level and leave artifacts behind for diagnosis
- the backend pipeline uses the same `composer qa:ci` wrapper that developers run locally

## Uploaded artifacts
The CI pipeline publishes:
- backend QA artifacts under `build/test-results`
- Playwright reports and traces under `playwright-report` and `test-results/playwright-artifacts`
- Gitleaks SARIF output from the secret-scan job

## Failure-handling model
This project does **not** claim external pager/email alerting. The actual alert mechanism is:
1. GitHub Actions red status on the commit / pull request
2. job logs that show the exact failing step
3. uploaded artifacts for backend or Playwright diagnosis

That behavior is concrete enough for defense because it is visible, reproducible, and tied directly to the workflow files in the repo.

## Release artifact workflow
A second workflow exists at:

```text
.github/workflows/release-package.yml
```

It packages a release-ready source artifact for manual review, archival, or submission use.
