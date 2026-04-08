# Metrics Report

This document records **real, reproducible Assignment 2 metrics** gathered from the verified automation commands and GitHub Actions evidence.

## 1) Automation coverage by risk area

The repository does **not** claim full-monolith automation coverage. The defended scope is a **critical-path regression suite** focused on the highest-risk modules.

| Risk area | Automated checks | Evidence |
|---|---:|---|
| Authentication & session boundary | 17 | `tests/Feature/Api/AuthHardeningTest.php`, `tests/Feature/Api/AuthSessionLifecycleTest.php`, `tests/Feature/Api/AuthSessionMeTest.php` |
| Reader account & reservations | 27 | `tests/Feature/AccountPageTest.php`, `tests/Feature/Api/AccountReservationsTest.php`, `tests/Feature/Api/ReaderAccessProtectionTest.php` |
| Catalog discovery & detail | 14 | `tests/Feature/CatalogPageTest.php`, `tests/Feature/Api/CatalogDbSearchTest.php`, `tests/Feature/Api/BookDetailDbTest.php` |
| Internal staff access & operations | 15 | `tests/Feature/InternalAccessBoundaryTest.php`, `tests/Feature/InternalDashboardPageTest.php`, `tests/Feature/InternalReviewPageTest.php`, `tests/Feature/InternalStewardshipPageTest.php`, `tests/Feature/InternalCirculationPageTest.php` |
| Public shell & browser smoke | 7 | `tests/Feature/PublicShellTest.php`, `tests/e2e/public-smoke.spec.ts` |

## 2) Verified execution times (TTE)

| Command / step | Verified result |
|---|---|
| `composer qa:ci` critical-path PHPUnit stage | `77 passed (350 assertions)` in `6.79s` |
| Vite production build inside `composer qa:ci` | completed in `1.17s` |
| `npm run test:e2e` | `3 passed` in `5.6s` |
| GitHub Actions frontend smoke job | completed successfully in about `1m 22s` on hosted runners |

Source logs:
- `evidence/a2/verification-2026-04-08.txt`
- `evidence/a2/timing-output.txt`
- GitHub Actions run logs referenced in `evidence-index.md`

## 3) Coverage metric

| Metric | Verified value | Interpretation |
|---|---:|---|
| Clover line coverage | `4.24%` | low as a global monolith percentage, because the gate intentionally measures a **critical-path subset** against the full `app/` namespace |
| CI threshold floor | `4.0%` | set just below the currently verified baseline to catch empty or materially regressed coverage artifacts |

> This low global percentage is a **known limitation**, not something hidden. The stronger metric for this assignment is the explicit mapping between high-risk modules and implemented automated checks.

## 4) Defects vs risk discovered during the adversarial audit

| Defect found during audit | Risk to submission | Fix status | Evidence |
|---|---|---|---|
| backend tests failed when no `public/build/manifest.json` existed | high | fixed | CI failures and `tests/TestCase.php` change |
| clean CI runners lacked `vite` because `node_modules` was absent | high | fixed | `scripts/dev/run-ci-gates.sh` change |
| Clover parsing assumed a `line-rate` attribute that was not always present | medium | fixed | `scripts/dev/check-coverage-threshold.php` change |
| coverage floor and documentation were misaligned with the defended critical-path scope | medium | fixed | `.github/workflows/ci.yml`, `composer.json`, `quality-gates.md` |

## 5) Structured metrics source

The raw structured metrics used for chart generation are stored in:

```text
evidence/a2/assignment2-metrics.json
```

The charts under `evidence/a2/charts/` are intended to be regenerated from that JSON, not from invented values.
