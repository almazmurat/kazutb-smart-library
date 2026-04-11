# Evidence Index

The following artifacts support the repository’s QA and verification baseline.

| Artifact | Purpose |
|---|---|
| `evidence/verification/qa-gates-*.txt` | timestamped backend/local QA gate logs from the defended verification path |
| `evidence/verification/playwright-smoke-*.txt` | timestamped Playwright smoke logs for the public critical path |
| `evidence/verification/remote-ci-summary-*.txt` | snapshot of recent GitHub Actions runs used to confirm clean-runner status |
| `evidence/verification/quality-metrics.json` | structured source of the numeric metrics used in the docs and charts |
| `evidence/verification/charts/coverage-by-module.svg` | regenerated chart built from the live metrics JSON |
| `evidence/verification/charts/execution-time-by-run.svg` | timing chart built from verified execution data |
| `evidence/verification/charts/run-status-distribution.svg` | pass/fail distribution chart for the current defended scope |

## Regeneration path
Fresh evidence can be regenerated with:

```bash
composer qa:evidence
```

## Latest verified command summary
- `composer qa:ci` → **139 passed (595 assertions)** and the frontend build succeeded on the 2026-04-11 evidence refresh
- `npm run test:e2e` → **3 passed (4.7s)** on the latest smoke verification bundle
- the latest successful main-branch `Continuous Verification` run → **`24274728457` completed success** on the current report package
- Clover coverage floor currently defended in CI → **4.24% measured vs 4.0% minimum**

## Latest evidence bundle
The most recent refresh captured under `evidence/verification/` is the `20260411-034516` bundle:
- `qa-gates-20260411-034516.txt`
- `playwright-smoke-20260411-034516.txt`
- `remote-ci-summary-20260411-034516.txt`
- `ci-traceability-20260411-034516.txt`
