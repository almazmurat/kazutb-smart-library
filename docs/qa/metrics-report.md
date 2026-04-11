# Metrics Report

This document records **real, reproducible QA metrics** gathered from the verified automation commands and GitHub Actions evidence.

## 1) Automation coverage by risk area

The repository does **not** claim full-monolith automation coverage. The defended scope is a **critical-path regression suite** focused on the highest-risk modules.

| Risk area | Automated checks | Evidence |
|---|---:|---|
| Authentication & session boundary | 20 | `tests/Feature/Api/AuthHardeningTest.php`, `tests/Feature/Api/AuthSessionLifecycleTest.php`, `tests/Feature/Api/AuthSessionMeTest.php` |
| Reader account & reservations | 27 | `tests/Feature/AccountPageTest.php`, `tests/Feature/Api/AccountReservationsTest.php`, `tests/Feature/Api/ReaderAccessProtectionTest.php` |
| Catalog discovery & detail | 16 | `tests/Feature/CatalogPageTest.php`, `tests/Feature/Api/CatalogDbSearchTest.php`, `tests/Feature/Api/BookDetailDbTest.php` |
| Internal staff access & operations | 17 | `tests/Feature/InternalAccessBoundaryTest.php`, `tests/Feature/InternalDashboardPageTest.php`, `tests/Feature/InternalReviewPageTest.php`, `tests/Feature/InternalStewardshipPageTest.php`, `tests/Feature/InternalCirculationPageTest.php` |
| Integration boundary & idempotency | 40 | `tests/Feature/Api/Integration/DocumentManagementTest.php`, `tests/Feature/Api/Integration/IntegrationRateLimitTest.php`, `tests/Feature/Api/Integration/ReservationMutateTest.php` |
| Teacher shortlist / bibliography formatting | 14 | `tests/Unit/Services/BibliographyFormatterTest.php` |
| Public shell & browser smoke | 10 | `tests/Feature/PublicShellTest.php`, `tests/e2e/public-smoke.spec.ts` |

## 2) Verified execution times (TTE)

| Command / step | Verified result |
|---|---|
| `composer qa:ci` critical-path PHPUnit stage | `139 passed (595 assertions)` in `8.19s` |
| Vite production build inside `composer qa:ci` | completed in `1.10s` after the clean-runner `npm ci` bootstrap |
| `npm run test:e2e` | `3 passed` in `4.8s` |
| GitHub Actions backend job (`run 24156029659`) | completed successfully in about `40s` |
| GitHub Actions frontend smoke job (`run 24156029659`) | completed successfully in about `60s` |

Source logs:
- `evidence/verification/qa-gates-20260411-014142.txt`
- `evidence/verification/playwright-smoke-20260411-014142.txt`
- GitHub Actions run logs referenced in `evidence-index.md`

## 3) Coverage metric

| Metric | Verified value | Interpretation |
|---|---:|---|
| Clover line coverage | `4.24%` | low as a global monolith percentage, because the gate intentionally measures a **critical-path subset** against the full `app/` namespace |
| CI threshold floor | `4.0%` | set just below the currently verified baseline to catch empty or materially regressed coverage artifacts |

> This low global percentage is a **known limitation**, not something hidden. The stronger metric for this review pack is the explicit mapping between high-risk modules and implemented automated checks.

## 4) Defects vs risk discovered during the adversarial audit

| Defect found during audit | Risk to submission | Fix status | Evidence |
|---|---|---|---|
| backend tests failed when no `public/build/manifest.json` existed | high | fixed | CI failures and `tests/TestCase.php` change |
| clean CI runners lacked `vite` because `node_modules` was absent | high | fixed | `scripts/dev/run-ci-gates.sh` change |
| Clover parsing assumed a `line-rate` attribute that was not always present | medium | fixed | `scripts/dev/check-coverage-threshold.php` change |
| coverage floor and documentation were misaligned with the defended critical-path scope | medium | fixed | `.github/workflows/ci.yml`, `composer.json`, `quality-gates.md` |
| local Playwright runs could fail with `EACCES` when prior Docker-owned report folders were not writable | medium | fixed | `playwright.config.ts` writable-directory fallback |
| the homepage smoke test still asserted stale hero copy after the KazTBU branding refresh | medium | fixed | `tests/e2e/public-smoke.spec.ts` update + fresh 2026-04-11 pass |

## 5) Structured metrics source

The raw structured metrics used for chart generation are stored in:

```text
evidence/verification/quality-metrics.json
```

The charts under `evidence/verification/charts/` are intended to be regenerated from that JSON, not from invented values.
