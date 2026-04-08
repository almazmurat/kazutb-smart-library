# Evidence Index

The following artifacts support the Assignment 2 submission and publication-hardening pass.

| Artifact | Purpose |
|---|---|
| `evidence/a2/commit-diff-a2-tests-ci.png` | visual snapshot of the tests/CI-focused repository diff |
| `evidence/a2/commit-diff-a2-tests-ci.txt` | text version of the same diff summary |
| `evidence/a2/focused-test-run.txt` | earlier focused backend verification output |
| `evidence/a2/git-history.png` | screenshot of the recent repository history |
| `evidence/a2/git-history.txt` | text export of git history used for evidence |
| `evidence/a2/github-actions-critical-path-step.png` | screenshot of the main GitHub Actions QA stage |
| `evidence/a2/github-actions-run-summary.png` | screenshot of workflow run summary |
| `evidence/a2/github-actions-workflow-list.png` | screenshot of configured workflows |
| `evidence/a2/pint-changed-files-pass.txt` | successful style verification output for changed critical files |
| `evidence/a2/pint-global-output.txt` | broader style audit output captured during hardening |
| `evidence/a2/timing-output.txt` | timing and execution evidence for the focused test pass |
| `evidence/a2/verification-2026-04-08.txt` | latest verified `composer qa:ci` and `npm run test:e2e` results |

## Latest verified command summary
- `composer qa:ci` → **77 passed (350 assertions)** and frontend build succeeded
- `npm run test:e2e` → **3 passed (5.4s)**
