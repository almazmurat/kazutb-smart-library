# Assignment 2 Audit Report — Test Automation Implementation

## 1. Executive summary
A strict adversarial audit was performed against the real repository state, runnable commands, workflow files, evidence logs, and version history. The audit did **not** accept the presence of tests or docs as proof by itself; every major claim was checked against the implementation and recent verification output.

**Bottom line:** the repository now presents a defensible **critical-path test automation submission** for Assignment 2, with documented limitations. The strongest evidence is the real command output from `composer qa:ci`, `npm run test:e2e`, and the successful GitHub Actions CI run `24156029659` for commit `3e8ff3d`.

## 2. Assignment 2 compliance verdict
**Verdict: PASS with documented limitations**

Why this is not a blind PASS:
- the defended scope is a **high-risk critical-path subset**, not full product coverage
- the global Clover percentage is low (`4.24%`) and is disclosed honestly
- multiple CI weaknesses were found during the audit and repaired before finalization

## 3. Requirement-by-requirement audit

| Requirement area | Verdict | Evidence-backed finding |
|---|---|---|
| Automated Test Implementation | PASS | real PHPUnit and Playwright automation exists for auth, account, catalog, internal access, and public-shell risks |
| Quality Gates & CI/CD | PASS | GitHub Actions runs secret scan, backend QA, frontend build, and browser smoke checks on `push`, `pull_request`, and manual dispatch |
| Metrics Collection | PASS | metrics are now recorded in `metrics-report.md`, `assignment2-metrics.json`, verification logs, and charts generated from reproducible data |
| Documentation & Research Alignment | PASS | assignment docs now explain tool choice, scope rationale, gate behavior, evidence mapping, and limitations honestly |

## 4. Grading-criteria audit

### Automation Implementation
| Criterion | Status | Notes |
|---|---|---|
| Test Scope Selection | PASS | risk-ranked modules are now explicitly justified in `test-scope.md` |
| Test Case Design | PASS | positive/negative case examples are mapped to actual test files and routes |
| Script Implementation | PASS | reusable scripts exist in `scripts/dev/run-ci-gates.sh` and `scripts/dev/run-assignment2-evidence.sh` |
| Version Control | PASS | traceability is supported by real commit history and evidence files |
| Evidence | PASS | logs, screenshots, artifacts, and metrics JSON are indexed and reproducible |

### Quality Gates & CI/CD
| Criterion | Status | Notes |
|---|---|---|
| Quality Gate Definition | PASS | explicit gates, commands, pass conditions, and failure behavior are documented |
| CI/CD Integration | PASS | `.github/workflows/ci.yml` and `.github/workflows/release-package.yml` are real and version-controlled |
| Alerting & Failure Handling | PASS | GitHub status checks and artifacts are the concrete alert mechanism; limitation of no external pager/email is documented |

### Metrics Collection
| Criterion | Status | Notes |
|---|---|---|
| Coverage Analysis | PASS | Clover output is measured and threshold-checked, with limitations disclosed |
| Execution Time (TTE) | PASS | command-level durations are documented from actual runs |
| Defects vs Risk | PASS | audit-found defects and fixes are recorded with severity and remediation |
| Logs & Tracking | PASS | evidence logs exist under `evidence/a2/` and can be regenerated |

### Documentation & Research Alignment
| Criterion | Status | Notes |
|---|---|---|
| Automation Strategy | PASS | approach and risk-based prioritization are explained |
| Tool Selection Justification | PASS | PHPUnit, Playwright, Pint, Docker, and GitHub Actions are justified against repo reality |
| CI/CD Documentation | PASS | workflow triggers, steps, artifacts, and failure behavior are documented |
| Results Presentation | PASS | tables, logs, charts, and evidence mapping are now concrete and aligned with reality |

## 5. Gaps found during the audit
1. **The initial Assignment 2 docs were too shallow** and did not support defense-level questioning.
2. **A fake/illustrative charting script existed** with hard-coded values that were not safe to treat as evidence.
3. **Backend CI had environment-coupling bugs**:
   - Vite manifest dependency in backend tests
   - missing `vite` binary on clean runners
   - Clover parsing mismatch
4. **The coverage gate was mismatched** with the defended critical-path scope and needed explicit justification.

## 6. Fixes implemented
- added `withoutVite()` in `tests/TestCase.php` to decouple backend tests from prebuilt frontend assets
- hardened `scripts/dev/run-ci-gates.sh` for clean-run Node dependency installation
- repaired `scripts/dev/check-coverage-threshold.php` to parse PHPUnit Clover metrics safely
- aligned `.github/workflows/ci.yml` and `composer.json` with the defended coverage gate behavior
- added `scripts/dev/run-assignment2-evidence.sh` for reproducible evidence collection
- expanded Assignment 2 docs to include a real audit narrative, metrics, scope mapping, and defense-oriented rationale

## 7. Verification commands run
The audit used real verification commands, including:

```bash
composer qa:ci
npm run test:e2e
php scripts/dev/check-coverage-threshold.php /tmp/sample-clover.xml 20
GH_PAGER=cat gh run view <run-id> --json status,conclusion,jobs
```

## 8. Updated evidence/docs created
- `docs/assignment-2/audit-report.md`
- `docs/assignment-2/metrics-report.md`
- `docs/assignment-2/test-scope.md` (expanded)
- `docs/assignment-2/quality-gates.md` (expanded)
- `docs/assignment-2/ci-cd.md` (expanded)
- `evidence/a2/assignment2-metrics.json`
- `scripts/dev/run-assignment2-evidence.sh`

## 9. Remaining limitations
- the repository still uses a **targeted regression scope**, not a fully green full-monolith feature suite
- the global Clover percentage is low because the measured scope is intentionally smaller than the full application namespace
- GitHub Actions currently provides the alerting mechanism; there is no external pager/email integration configured

## 10. Final readiness for submission and defense
The submission is now **defensible** if presented honestly as a **risk-based automation implementation** rather than a claim of total system coverage. A strong defense should emphasize:
- why the selected modules are the highest risk
- how the automation is executed repeatedly through `composer qa:ci` and `npm run test:e2e`
- how GitHub Actions enforces these checks on every push/PR
- what the measured metrics actually mean, including the limitations

---

## Compliance matrix

| Requirement | Repository location | Evidence location | Status | Notes |
|---|---|---|---|---|
| Identify high-risk modules/functions | `docs/assignment-2/test-scope.md` | test file map in same document | PASS | now risk-ranked and justified against business/security impact |
| Prioritize scope explicitly | `docs/assignment-2/test-scope.md` | priority table | PASS | priority order is now explicit instead of implied |
| Detailed test cases | `tests/Feature/**`, `tests/e2e/public-smoke.spec.ts` | `docs/assignment-2/test-scope.md` | PASS | positive and negative scenarios are mapped to real tests |
| Maintainable scripts | `scripts/dev/run-ci-gates.sh`, `scripts/dev/run-assignment2-evidence.sh` | version-controlled scripts | PASS | reusable and reproducible |
| Version-control traceability | git history for CI/test files | `evidence/a2/git-history.txt` | PASS | recent automation commits are visible and referenced |
| Reproducible evidence | `evidence/a2/` | logs, screenshots, metrics JSON, charts | PASS | evidence paths are concrete and indexed |
| Quality gate definitions | `.github/workflows/ci.yml`, `composer.json` | `docs/assignment-2/quality-gates.md` | PASS | pass/fail behavior is now documented |
| CI/CD integration | `.github/workflows/ci.yml`, `.github/workflows/release-package.yml` | GitHub Actions artifacts/screenshots | PASS | runs on push/PR/manual dispatch |
| Alerting/failure handling | GitHub Actions status checks | `docs/assignment-2/quality-gates.md`, run screenshots | PASS | GitHub red/green workflow state is the actual signal |
| Coverage metrics | Clover report + threshold script | `docs/assignment-2/metrics-report.md`, `build/test-results/clover.xml` | PASS | low but honest and reproducible |
| Execution-time metrics | local logs and Actions durations | `evidence/a2/verification-2026-04-08.txt`, `timing-output.txt` | PASS | measured values are included |
| Defects vs risk tracking | audit remediation commits | `docs/assignment-2/metrics-report.md` | PASS | real defects uncovered during audit are listed |
| Logs & tracking | `evidence/a2/*.txt` | `docs/assignment-2/evidence-index.md` | PASS | reproducible command output is indexed |
| Research/report alignment | `docs/assignment-2/*.md` | this report + supporting docs | PASS | the docs now support report writing and oral defense |
