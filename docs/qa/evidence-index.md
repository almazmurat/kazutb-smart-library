# Evidence Index

The following artifacts support the repository’s QA and verification baseline.

| Artifact | Purpose |
|---|---|
| `evidence/verification/verification-2026-04-08.txt` | verified local `composer qa:ci` and `npm run test:e2e` results |
| `evidence/verification/qa-gates-*.txt` | timestamped backend/local QA gate logs |
| `evidence/verification/playwright-smoke-*.txt` | timestamped Playwright smoke logs |
| `evidence/verification/ci-traceability-*.txt` | timestamped git-history trace for QA/CI files |
| `evidence/verification/remote-ci-summary-*.txt` | timestamped snapshot of recent GitHub Actions runs |
| `evidence/verification/remote-ci-success-2026-04-08.json` | successful GitHub Actions run summary for the stabilized baseline |
| `evidence/verification/quality-metrics.json` | structured source of the numeric metrics used in the docs and charts |
| `evidence/verification/commit-diff-tests-ci.png` | screenshot of the tests/CI-focused repository diff |
| `evidence/verification/commit-diff-tests-ci.txt` | text version of the same diff summary |
| `evidence/verification/focused-test-run.txt` | backend verification output from the focused regression scope |
| `evidence/verification/git-history.png` | visual snapshot of the repository history |
| `evidence/verification/git-history.txt` | text export of git history used for traceability |
| `evidence/verification/github-actions-critical-path-step.png` | screenshot of the backend CI stage |
| `evidence/verification/github-actions-run-summary.png` | summary screenshot of a GitHub Actions workflow run |
| `evidence/verification/github-actions-workflow-list.png` | screenshot proving the configured workflows exist |
| `evidence/verification/pint-changed-files-pass.txt` | style-gate output for changed critical files |
| `evidence/verification/pint-global-output.txt` | wider style-audit snapshot |
| `evidence/verification/timing-output.txt` | timing evidence from earlier focused runs |
| `evidence/verification/charts/coverage-by-module.svg` | regenerated chart built from real metrics JSON |
| `evidence/verification/charts/execution-time-by-run.svg` | timing chart built from verified execution data |
| `evidence/verification/charts/run-status-distribution.svg` | resolved-defect chart built from the structured metrics file |

## Regeneration path
Fresh evidence can be regenerated with:

```bash
composer qa:evidence
```

## Latest verified command summary
- `composer qa:ci` → **77 passed (350 assertions)** and the frontend build succeeded
- `npm run test:e2e` → **3 passed (5.5s)**
- Clover coverage floor currently defended in CI → **4.24% measured vs 4.0% minimum**
