# Quality Gates

## 1) Gate definitions

| Gate | Command / CI step | Pass condition | Failure behavior |
|---|---|---|---|
| Code style | `./vendor/bin/pint --test ...` inside `composer qa:ci` | no style violations on the defended critical-path files | local command exits non-zero; the backend job fails |
| Critical-path backend regression | `php artisan test --filter='...'` inside `composer qa:ci` | all selected PHPUnit tests pass | push/PR verification fails and blocks a clean status |
| Frontend build gate | `npm run build` | the Vite manifest and production bundle are generated successfully | local `composer qa:ci` fails; the backend job fails |
| Browser smoke gate | `npm run test:e2e` | all Playwright smoke checks pass | the browser job fails and uploads trace artifacts |
| Secret scan | `gitleaks/gitleaks-action@v2` | no secrets are detected | the workflow fails before backend/browser jobs proceed |
| Coverage floor | `composer qa:coverage-threshold` | the Clover artifact remains above the current defended floor | the backend job fails if coverage is empty or materially regresses |

## 2) Primary local gate
Run:

```bash
composer qa:ci
```

This wrapper executes `scripts/dev/run-ci-gates.sh`, which performs:
1. Laravel configuration reset for a clean testing context
2. targeted `Pint` checks on the hardened critical-path files
3. critical-path PHPUnit feature/API regression tests, including the unit-level bibliography formatter pack and the integration boundary pack (`BibliographyFormatterTest`, `DocumentManagementTest`, `IntegrationRateLimitTest`, `ReservationMutateTest`)
4. frontend production build verification with Vite

## 3) Threshold justification

| Threshold | Current value | Why it is set this way |
|---|---:|---|
| Pint style violations | `0` | style verification should remain strict |
| Critical-path PHPUnit pass rate | `100%` | failures in the defended risk scope are not acceptable |
| Playwright smoke pass rate | `100%` | each smoke flow represents a core public path |
| Clover coverage floor | `4.0%` | the defended suite is intentionally targeted while Clover still measures against the full `app/` namespace; the verified baseline is `4.24%` |

> The coverage floor is intentionally modest but not arbitrary: it is a regression floor anchored to the measured critical-path baseline.

## 4) Clean-runner normalization
The QA baseline is explicitly hardened for fresh machines and GitHub-hosted runners:
- the wrapper detects whether the host has **PHP 8.4+** and falls back to Docker automatically if it does not
- frontend dependency installation is performed before the build when `vite` is not already present
- Playwright now falls back to a writable temp report/artifact directory when repo-owned output folders are not writable locally
- CI uses Node `22` for the toolchain and `FORCE_JAVASCRIPT_ACTIONS_TO_NODE24: true` for JavaScript-action runtime compatibility
- npm install steps use quieter, non-auditing flags to avoid unnecessary noise in CI logs

## 5) Browser smoke verification
Install the browser once locally:

```bash
npm run test:e2e:install
```

Then run:

```bash
npm run test:e2e
```

The Playwright configuration falls back to the Docker-served application when host PHP is below the supported version.

## 6) Alerting and failure handling
This repository currently uses **GitHub Actions status checks** as its concrete alerting model.

| Failure signal | What the developer sees |
|---|---|
| red GitHub Actions job | visible on the commit/PR summary page |
| uploaded backend artifacts | JUnit/Clover files remain available even when the backend job fails |
| uploaded Playwright artifacts | HTML report, traces, screenshots, and videos are available for browser failures |

## 7) Recent instability now closed
The latest hardening work explicitly addressed the recent CI failure chain:
- missing Vite tooling on clean runners (`exit 127`)
- broader SQLite-incompatible coverage execution (`exit 2`)
- Clover parsing mismatch (`exit 1`)
- unrealistic coverage threshold mismatch (`exit 1`)

These were fixed in version-controlled code and workflow changes rather than suppressed.
