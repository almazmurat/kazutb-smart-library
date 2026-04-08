# Evidence Index

The following artifacts support the Assignment 2 submission and the adversarial completion audit.

| Artifact | Purpose |
|---|---|
| `evidence/a2/verification-2026-04-08.txt` | verified local `composer qa:ci` and `npm run test:e2e` results |
| `evidence/a2/assignment2-qa-*.txt` | timestamped reproducibility logs for the backend/local QA gate |
| `evidence/a2/assignment2-playwright-*.txt` | timestamped Playwright smoke logs produced by the evidence runner |
| `evidence/a2/assignment2-traceability-*.txt` | timestamped git-history trace focused on Assignment 2 files |
| `evidence/a2/assignment2-remote-ci-*.txt` | timestamped snapshot of recent GitHub Actions runs |
| `evidence/a2/assignment2-metrics.json` | structured source of the numeric metrics used in the docs and charts |
| `evidence/a2/commit-diff-a2-tests-ci.png` | screenshot of the tests/CI-focused repository diff |
| `evidence/a2/commit-diff-a2-tests-ci.txt` | text version of the same diff summary |
| `evidence/a2/focused-test-run.txt` | backend verification output from the focused A2 regression scope |
| `evidence/a2/git-history.png` | visual snapshot of the repository history |
| `evidence/a2/git-history.txt` | text export of git history used for version-control traceability |
| `evidence/a2/github-actions-critical-path-step.png` | screenshot of the backend CI stage |
| `evidence/a2/github-actions-run-summary.png` | summary screenshot of a GitHub Actions workflow run |
| `evidence/a2/github-actions-workflow-list.png` | screenshot proving the configured workflows exist |
| `evidence/a2/pint-changed-files-pass.txt` | style gate output for changed critical files |
| `evidence/a2/pint-global-output.txt` | wider style-audit snapshot |
| `evidence/a2/timing-output.txt` | timing evidence from earlier focused runs |
| `evidence/a2/charts/coverage-by-module.svg` | regenerated chart built from real metrics JSON |
| `evidence/a2/charts/execution-time-by-run.svg` | timing chart built from verified execution data |
| `evidence/a2/charts/run-status-distribution.svg` | audit outcome chart built from the structured metrics file |

## Regeneration path
Fresh evidence can be regenerated with:

```bash
composer qa:assignment2-evidence
```

## Latest verified command summary
- `composer qa:ci` → **77 passed (350 assertions)** and frontend build succeeded
- `npm run test:e2e` → **3 passed (5.4s)**
- Clover coverage floor currently defended in CI → **4.24% measured vs 4.0% minimum**
