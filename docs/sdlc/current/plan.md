# assignment-2-qa-automation-package — implementation plan

## Scope summary
Package the repo’s real automation baseline into a submission-ready Assignment 2 deliverable, refresh the QA docs with current metrics, fix any reproducibility blockers in local browser execution, and verify the result with fresh evidence.

## Traceability matrix
| Requirement | Use cases | Plan steps | Tests |
|---|---|---|---|
| `R1` | `UC1` | `S2`, `S3` | `T3` |
| `R2` | `UC1` | `S1`, `S2` | `T1`, `T3` |
| `R3` | `UC2` | `S3` | `T2` |
| `R4` | `UC2` | `S3` | `T2` |
| `R5` | `UC3` | `S4` | `T1`, `T2`, `T3` |

## Implementation steps
- [x] `S1.` Inspect the current QA surface in `docs/qa/`, `.github/workflows/ci.yml`, `tests/Feature/`, `tests/e2e/`, and `scripts/dev/` to map the real automation baseline.
- [x] `S2.` Create a submission-ready package in `docs/qa/assignment-2-submission-pack.md` and `docs/qa/qa-test-strategy-document.md` with the required tables, deliverable mapping, and defense notes.
- [x] `S3.` Harden the local browser automation flow by fixing the Playwright output-path permission issue and updating the stale homepage smoke expectation to current KazTBU copy.
- [x] `S4.` Verify the completed package with `composer qa:ci`, `npm run test:e2e`, and `composer qa:evidence`, then refresh `docs/sdlc/current/verify.md` with the evidence.

## Impacted files and layers
### Automation / test infrastructure
- `playwright.config.ts`
- `tests/e2e/public-smoke.spec.ts`

### Documentation
- `docs/qa/assignment-2-submission-pack.md`
- `docs/qa/qa-test-strategy-document.md`
- `docs/qa/README.md`
- `docs/qa/test-scope.md`
- `docs/qa/metrics-report.md`
- `docs/qa/quality-gates.md`

### Evidence
- `evidence/verification/qa-gates-20260411-001523.txt`
- `evidence/verification/playwright-smoke-20260411-001523.txt`
- `evidence/verification/ci-traceability-20260411-001523.txt`
- `evidence/verification/remote-ci-summary-20260411-001523.txt`

## Test plan
- `T1.` Run `composer qa:ci` and confirm the critical-path backend gate still passes.
- `T2.` Run `npm run test:e2e` and confirm the Playwright permission issue is resolved and all browser smoke tests pass.
- `T3.` Run `composer qa:evidence` and confirm fresh reproducibility logs are written under `evidence/verification/`.

## Verification commands
```bash
cd /home/admlibrary/kazutb-smart-library-main && composer qa:ci
cd /home/admlibrary/kazutb-smart-library-main && npm run test:e2e
cd /home/admlibrary/kazutb-smart-library-main && composer qa:evidence
```

## Risks / rollback notes
- Main risk: changing Playwright output handling could break CI artifact discovery if the preferred repo folders are not preserved when writable.
- Mitigation: keep the standard repo output folders as the first choice and only fall back to temp storage when the preferred paths are not writable.
- Rollback: revert `playwright.config.ts`, `tests/e2e/public-smoke.spec.ts`, and the new docs if needed.

## Ready for /implement
- completed
- notes:
  - The Assignment 2 package now exists under `docs/qa/`.
  - The local Playwright reproducibility issue and stale smoke expectation were addressed.

## Ready for /verify
- yes
- notes:
  - Fresh evidence is available from the 2026-04-11 runs of `composer qa:ci`, `npm run test:e2e`, and `composer qa:evidence`.