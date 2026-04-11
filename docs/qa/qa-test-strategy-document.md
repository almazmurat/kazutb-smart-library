# QA Test Strategy Document — Digital Library

## 1. Introduction
Explain that the project is a **production-oriented digital library web application** and that the QA approach is based on **risk-driven automation**, reproducibility, and CI-backed release confidence.

**Insert:**
- project context summary
- short justification for risk-based scope

---

## 2. System Under Test
Describe the real system boundaries:
- public discovery shell (`/`, `/catalog`, `/book/{isbn}`, `/resources`)
- reader account and reservation flows (`/account`, `/api/v1/account/*`)
- internal staff surfaces (`/internal/*`, `/api/v1/internal/*`)
- integration boundary (`/api/integration/v1/*`)

**Insert:**
- architecture snapshot or route table
- stack summary table

---

## 3. Risk-Based Automation Scope
Document the prioritized modules and why they were selected.

**Insert:**
- `Module / High-Risk Function / Risk Level / Priority` table
- explanation of the top 3–5 critical modules

---

## 4. Automation Approach
State the layered approach:
- backend/API verification through Laravel `PHPUnit`
- UI smoke coverage through `Playwright`
- reproducible evidence through logs and artifacts
- CI/CD enforcement via GitHub Actions

**Insert:**
- short testing pyramid adapted to this repo
- note on positive + negative + boundary cases

---

## 5. Tool Selection and Justification
Summarize the final stack and why it fits the repo better than alternatives.

| Area | Chosen Tool | Why It Fits |
|---|---|---|
| UI automation | Playwright | real browser + traces + CI friendliness |
| API/backend automation | PHPUnit | native Laravel integration |
| CI/CD | GitHub Actions | repository-native verification surface |
| Reporting | JUnit + Clover + HTML | exportable, standard, reproducible |

---

## 6. Test Architecture
Describe the actual project structure and reusability model.

```text
tests/Feature/
tests/Feature/Api/
tests/e2e/
scripts/dev/
docs/qa/
evidence/verification/
```

Cover:
- naming conventions
- reusable fixtures/helpers
- thin smoke strategy for UI
- avoiding flaky tests and stale selectors

---

## 7. Automated Test Cases
Include the high-risk detailed test-case matrix.

**Insert:**
- `Test Case ID / Description / Precondition / Input / Expected Result / Scenario Type / Automation Level`
- at least one positive and one negative case per critical module

---

## 8. Quality Gates
Define the release gates that block merges or releases.

**Insert:**
- `QG-01 ... QG-n` table
- thresholds for pass rate, coverage artifact, critical defects, flaky tolerance
- justification for each threshold

---

## 9. CI/CD Integration
Explain how tests run in continuous verification.

**Insert:**
- pipeline step table from `.github/workflows/ci.yml`
- trigger policy for push, PR, manual runs
- artifact outputs (`build/test-results`, Playwright traces)

---

## 10. Alerting and Failure Handling
Document what happens when a gate fails.

Cover:
- red CI status on PR/commit
- triage flow
- rerun policy
- rollback or block-merge rule
- defect logging practice

---

## 11. Metrics Collection Approach
Explain which metrics are collected and how.

**Core metrics:**
- critical-scenario coverage
- execution time
- pass/fail rate
- defects vs expected risk
- artifact generation success

**Insert:**
- formulas
- data sources (`JUnit`, `Clover`, log files)

---

## 12. Initial Results
Use fresh, real evidence only.

### Verified baseline on 2026-04-11
- `composer qa:ci` → **139 passed, 595 assertions**
- frontend production build completed successfully
- `npm run test:e2e` → **3 passed (4.7s)**

**Insert:**
- command outputs or screenshots
- exact evidence file paths under `evidence/verification/`

---

## 13. Coverage and Defect Analysis
Be explicit and honest:
- global Clover coverage is still modest because the suite is scoped to critical paths
- defended scenario coverage is much stronger across the selected P1 flows
- recent automation hardening fixed clean-runner and Playwright permission issues

---

## 14. Reproducibility Evidence
List how another reviewer can reproduce the results.

### Commands
```bash
composer qa:ci
npm run test:e2e
composer qa:evidence
```

### Evidence locations
- `evidence/verification/qa-gates-*.txt`
- `evidence/verification/playwright-smoke-*.txt`
- `build/test-results/phpunit-feature.xml`
- `build/test-results/clover.xml`

---

## 15. Limitations
State the current limits clearly:
- not full-monolith automation yet
- more internal workflow depth can be added later
- some metrics are scenario-based rather than exhaustive line coverage

---

## 16. Next Steps
Recommended follow-up work:
1. add nightly extended regression coverage
2. expand internal circulation and integration E2E flows
3. export metrics to CSV/JSON for chart regeneration
4. optionally add Slack/Telegram notifications for CI failures
