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

### Jobs
| Job | Purpose |
|---|---|
| `secret-scan` | runs `gitleaks` to catch accidentally committed credentials or tokens |
| `backend-quality` | installs PHP dependencies, runs `composer qa:ci`, generates PHPUnit/JUnit and coverage artifacts, and enforces a minimum threshold |
| `browser-smoke` | installs Node dependencies, builds the frontend, installs Playwright Chromium, and runs the smoke suite |

## Release artifact workflow
A second workflow exists at:

```text
.github/workflows/release-package.yml
```

It packages a release-ready source artifact for manual review, archival, or submission use.

## Uploaded artifacts
The CI pipeline publishes:
- backend QA artifacts under `build/test-results`
- Playwright reports and traces under `playwright-report` and `test-results/playwright-artifacts`

## Publication hardening highlights
The CI/CD pass was paired with repository safety improvements:
- sanitized `.env.example`
- expanded `.gitignore`
- public-safe config defaults
- stronger auth/session regression coverage
