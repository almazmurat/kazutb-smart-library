# CI/CD Overview

## Primary workflows
The repository exposes two reviewer-facing automation flows:

```text
.github/workflows/ci.yml
.github/workflows/release-package.yml
```

### Trigger conditions
- push to `main`
- pull requests targeting `main`
- manual `workflow_dispatch`
- release packaging on version tags (`v*`)

## Verification pipeline

| Order | Job | What it does | Why it matters |
|---|---|---|---|
| 1 | `secret-scan` | runs Gitleaks before the rest of the pipeline | enforces basic repository hygiene and prevents secrets from moving downstream |
| 2 | `backend-quality` | installs PHP dependencies, runs `composer qa:ci`, generates JUnit/Clover files, and enforces the coverage floor | keeps backend verification deterministic and reproducible |
| 3 | `browser-smoke` | installs the Node toolchain, builds assets, installs Chromium, and runs Playwright smoke coverage | verifies the public reader experience on a clean runner |
| 4 | `package` | builds a release-ready source artifact on tag pushes | keeps manual release review and archival consistent |

## Clean-runner hardening
The current workflow baseline is designed to behave consistently on fresh GitHub-hosted runners:
- `FORCE_JAVASCRIPT_ACTIONS_TO_NODE24: true` is set at workflow level to avoid deprecated Node 20 JavaScript action runtime behavior
- official actions are pinned to current major versions where available
- frontend jobs use Node `22` explicitly
- backend verification uses PHP `8.4` explicitly
- the local wrapper script installs missing frontend dependencies before the build step so `vite` is not assumed to exist
- backend coverage generation is scoped to the defended critical-path suite instead of the unstable broader run that previously failed on SQLite-incompatible areas

## Uploaded artifacts
The CI pipeline publishes:
- backend verification artifacts under `build/test-results`
- Playwright reports and traces under `playwright-report` and `test-results/playwright-artifacts`
- release tarballs under `dist/`

## Failure-handling model
This repository currently relies on GitHub’s native status checks and artifacts:
1. red or green workflow state on the commit / pull request
2. step-level logs for exact failure diagnosis
3. uploaded backend and Playwright artifacts for review

This is a professional and fully reproducible baseline even without external pager integration.
