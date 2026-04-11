# Verification — Assignment 2 QA automation package

## Artifact under review
- draft: `docs/sdlc/current/draft.md`
- spec: `docs/sdlc/current/spec.md`
- plan: `docs/sdlc/current/plan.md`

## Commands executed
```bash
cd /home/admlibrary/kazutb-smart-library-main && composer qa:ci
cd /home/admlibrary/kazutb-smart-library-main && npm run test:e2e
cd /home/admlibrary/kazutb-smart-library-main && composer qa:evidence
```

## Results
| Check | ID | Status | Evidence |
|---|---|---|---|
| main local QA gate | `V1` | PASS | `composer qa:ci` → `80 passed (397 assertions)` and frontend build completed successfully |
| browser smoke suite | `V2` | PASS | `npm run test:e2e` → `3 passed (5.0s)` after the Playwright output-path hardening |
| reproducibility evidence refresh | `V3` | PASS | `composer qa:evidence` wrote fresh logs under `evidence/verification/` with timestamp `20260411-001523` |

## Requirement traceability
| Requirement | Evidence | Result |
|---|---|---|
| `R1` | `docs/qa/assignment-2-submission-pack.md` and `docs/qa/qa-test-strategy-document.md` | PASS |
| `R2` | new docs reference actual repo paths, tests, routes, and CI workflow | PASS |
| `R3` | `playwright.config.ts` now falls back to writable temp report/artifact folders when repo paths are not writable | PASS |
| `R4` | `tests/e2e/public-smoke.spec.ts` now checks the current English KazTBU homepage copy | PASS |
| `R5` | fresh command outputs and timestamped evidence logs from 2026-04-11 | PASS |

## Use-case coverage
| Use case | Evidence | Result |
|---|---|---|
| `UC1` | reviewer can open the new Assignment 2 package under `docs/qa/` | PASS |
| `UC2` | maintainer can run the local QA and browser smoke suites successfully | PASS |
| `UC3` | evidence logs exist under `evidence/verification/qa-gates-20260411-001523.txt` and related files | PASS |

## Notes
- The initial local `npm run test:e2e` failure was investigated rather than ignored. The root cause was an `EACCES` write failure against Docker-owned report folders plus a stale homepage text assertion.
- Both issues were fixed and then re-verified with a clean Playwright pass.
- The QA package remains intentionally **risk-based** and truthful about scope rather than claiming exhaustive monolith coverage.

## Ready for /document
- yes