# Defense Guide — QA Verification Walkthrough

This guide is the fastest way to present the repository in a live defense without getting lost in support files.

## 1. Open these files first
1. `docs/qa/final-qa-report.md` — main article-style report
2. `docs/qa/README.md` — map of supporting materials
3. `.github/workflows/ci.yml` — real CI pipeline definition

## 2. Best repo paths to show as concrete proof
- `tests/Feature/Api/AuthHardeningTest.php` — negative auth scenarios and contract hardening
- `tests/Feature/InternalAccessBoundaryTest.php` — protected staff boundary checks
- `tests/Feature/Api/Integration/DocumentManagementTest.php` — integration-safe validation and error envelopes
- `tests/Unit/Services/BibliographyFormatterTest.php` — explicit unit-layer proof
- `tests/e2e/public-smoke.spec.ts` — public browser smoke coverage

## 3. Commands to run live
### Primary proof commands
```bash
composer qa:ci
npm run test:e2e
```

### Optional evidence refresh
```bash
composer qa:evidence
```

## 4. Which GitHub Actions page to show
Open the **latest successful `Continuous Verification` run on `main`** in the GitHub Actions tab.

Recommended talking point:
> “I’m showing the newest green run on the default branch because it proves the current repository state is stable on a clean runner, not just on my local machine.”

## 5. Which visuals to show
From `docs/qa/final-qa-report.md`:
- coverage-by-module chart
- execution-time chart
- verification status distribution chart
- testing strategy architecture diagram

### Fast artifact map
| Artifact / visual | Where to open it | What it proves |
|---|---|---|
| Main report | `docs/qa/final-qa-report.md` | the full article-style narrative and final conclusion |
| Coverage chart | `evidence/verification/charts/coverage-by-module.svg` | which high-risk areas are strongest and which one is still below `70%` scenario coverage |
| Execution-time chart | `evidence/verification/charts/execution-time-by-run.svg` | the verification path is practical to rerun during active development |
| Verification-status chart | `evidence/verification/charts/run-status-distribution.svg` | current stability is backed by a mostly green outcome profile |
| GitHub Actions successful run | latest successful `Continuous Verification` run on `main` | the repository is green on a clean runner, not only locally |
| Job detail screen | same GitHub Actions run, open `Backend Verification & Coverage` and `Frontend Build & Browser Smoke` | exactly what the CI pipeline executed |

## 6. How to explain the quality gates
Use this short explanation:
- `composer qa:ci` checks style, critical backend regressions, and the production build
- `npm run test:e2e` checks the public critical path in a real browser
- GitHub Actions repeats that verification on a clean runner
- the package is risk-based, so it prioritizes the highest-impact library workflows instead of pretending the monolith is exhaustively covered

## 7. Strongest answers to likely questions
### “Why is global coverage low?”
Because Clover measures executed lines against the whole `app/` namespace, while the current strategy intentionally focuses on high-risk critical-path behavior. The repo reports both numbers and explains the difference.

### “Why do you say the suite is stable if there were earlier failures?”
Because the earlier failures were investigated and classified: some were deterministic environment drift, not unresolved flaky behavior. The latest local and remote runs are green.

### “Why these tools?”
PHPUnit is native to the Laravel stack, Playwright gives real browser proof, and GitHub Actions provides visible clean-runner verification for the GitHub repo.

### “What is still weak?”
Deep internal-workflow E2E coverage and long-window flaky-rate observation are still thinner than the integration layer. Those are documented as limitations rather than hidden.

## 8. Suggested presentation flow
### 1-minute version
- open `docs/qa/final-qa-report.md`
- show the abstract, final conclusion, and latest verification snapshot
- mention `composer qa:ci`, `npm run test:e2e`, and the latest green GitHub Actions run

### 3-minute version
- show the strict gap audit
- explain the risk-based testing strategy
- show one auth/integration test file and the browser smoke file
- show the latest green CI run

### 7-minute version
1. Start with `docs/qa/final-qa-report.md`
2. Show the literature-review and methodology sections briefly
3. Walk through the failed-tests, coverage-gaps, and coverage-type tables
4. Open `AuthHardeningTest.php` and `DocumentManagementTest.php`
5. Open `tests/e2e/public-smoke.spec.ts`
6. Show `.github/workflows/ci.yml`
7. Finish on the conclusion + limitations sections

## 9. Final defense framing
Best concise framing:
> “This work should be judged as a risk-aware, evidence-backed QA baseline for a real semi-production repository. I do not claim exhaustive monolith coverage; I show which high-risk flows are defended, what failures were observed, how they were fixed, and what limitations remain.”