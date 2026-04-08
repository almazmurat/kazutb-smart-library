# Test Scope

## 1) High-risk module selection and prioritization

The submission scope was selected by **risk**, not by convenience. Modules were prioritized using three questions:
1. does a failure affect authentication, authorization, or protected data?
2. does a failure break a public reader journey?
3. does a failure expose internal staff tools or damage trust in the system?

| Priority | High-risk module / function | Why it is high risk | Repository location | Automated coverage |
|---|---|---|---|---|
| P1 | Authentication and session boundary | login/session bugs can leak data or allow improper access | `app/Http/Controllers/Api/AuthController.php`, auth routes, session middleware | `AuthHardeningTest`, `AuthSessionLifecycleTest`, `AuthSessionMeTest` |
| P1 | Reader account access and reservations | account pages expose personal borrowing and reservation state | `/account`, `/api/v1/account/*` | `AccountPageTest`, `AccountReservationsTest`, `ReaderAccessProtectionTest` |
| P1 | Internal staff boundary | `/internal/*` must reject guests/readers and allow staff only | `routes/web.php`, internal Blade views | `InternalAccessBoundaryTest`, internal page tests |
| P2 | Catalog discovery and book detail | catalog errors directly break the main public value of the library | `CatalogController.php`, `/api/v1/catalog-db`, `/catalog`, `/book/*` | `CatalogDbSearchTest`, `BookDetailDbTest`, `CatalogPageTest` |
| P2 | Public shell and smoke flows | homepage/resources/login redirect issues are visible to all users | public Blade shell and Playwright routes | `PublicShellTest`, `public-smoke.spec.ts` |

## 2) Detailed test-case design

| Area | Positive scenarios | Negative scenarios | Evidence |
|---|---|---|---|
| Auth hardening | valid login returns sanitized user data; valid integration token accepted | invalid credentials do not leak CRM details; CRM outage hides internal errors; invalid token rejected; blank credentials rejected | `tests/Feature/Api/AuthHardeningTest.php` |
| Reader access | authenticated reader can load `/api/v1/me` and account summary | guest gets `401`; guest `/account` redirects to `/login`; logout without session returns `401` | `tests/Feature/Api/ReaderAccessProtectionTest.php` |
| Internal staff boundary | librarian session receives `200 OK` on all core `/internal/*` pages | guest and reader sessions both receive `403 Forbidden` | `tests/Feature/InternalAccessBoundaryTest.php` |
| Catalog API | search, filters, sort, availability, and detail endpoints return results | bad parameters are rejected; no-result queries return empty payloads | `tests/Feature/Api/CatalogDbSearchTest.php`, `tests/Feature/Api/BookDetailDbTest.php` |
| Public shell smoke | homepage hero, search shell, and catalog controls render | guest account access redirects to login instead of exposing reader state | `tests/e2e/public-smoke.spec.ts` |

## 3) Maintainable script implementation

| Script / command | Purpose | Reusability |
|---|---|---|
| `composer qa:ci` | one-command local quality gate for style, critical-path PHPUnit, and Vite build | high |
| `scripts/dev/run-ci-gates.sh` | shell wrapper that handles PHP-version fallback and Node install behavior | high |
| `npm run test:e2e` | browser smoke suite for public flows | high |
| `playwright.config.ts` | local Docker fallback for browser execution on older PHP hosts | high |
| `composer qa:assignment2-evidence` | reproducible log collection for the assignment evidence set | high |

## 4) Version-control traceability

The automation work is traceable in Git history and was not added as a one-off dump.

| Commit | Evidence of progress |
|---|---|
| `d8daed3` | public release hardening, README/CI/Playwright baseline |
| `81344dc` | CI stabilized against missing Vite manifest |
| `734f2d9` | QA gate fixed for clean runners without preinstalled frontend deps |
| `cea7d76` | backend workflow stabilized for critical-path coverage generation |
| `afa1598` | Clover coverage parser fixed for PHPUnit output compatibility |

## 5) Reproducible evidence map

| Evidence type | Location | What it proves |
|---|---|---|
| local QA pass log | `evidence/a2/verification-2026-04-08.txt` | `composer qa:ci` and `npm run test:e2e` really passed |
| commit trace | `evidence/a2/git-history.txt` | automation work evolved over multiple commits |
| GitHub screenshots | `evidence/a2/github-actions-*.png` | CI exists and runs on GitHub Actions |
| structured metrics | `evidence/a2/assignment2-metrics.json` | numeric metrics used in the report are reproducible |
| fresh log collector | `scripts/dev/run-assignment2-evidence.sh` | evidence can be regenerated on demand |

## 6) Current verified execution
- `composer qa:ci` → **77 passed (350 assertions)**
- `npm run test:e2e` → **3 passed (5.4s)**

## 7) Scope limitation
This assignment set is intentionally **critical-path oriented** rather than exhaustive. That limitation is disclosed because the repository still contains broader legacy areas that are not fully automated at the same depth.
