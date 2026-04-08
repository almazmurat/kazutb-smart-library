# Test Scope

## Automated backend regression scope
The hardened QA pass focuses on the reader and staff flows that are most important for publication safety and baseline product stability.

| Area | Representative tests | Purpose |
|---|---|---|
| Public shell | `PublicShellTest`, `CatalogPageTest` | verifies homepage, catalog shell, and public navigation remain stable |
| Reader account | `AccountPageTest`, `AccountReservationsTest`, `ReaderAccessProtectionTest` | protects authenticated account behavior and guest redirect boundaries |
| Auth hardening | `AuthHardeningTest`, `AuthSessionLifecycleTest`, `AuthSessionMeTest` | checks secure login/session behavior and non-leaky error handling |
| Internal staff access | `InternalAccessBoundaryTest`, `InternalDashboardPageTest`, `InternalReviewPageTest`, `InternalStewardshipPageTest`, `InternalCirculationPageTest` | ensures `/internal/*` surfaces stay protected from guest/reader access |
| Catalog data endpoints | `CatalogDbSearchTest`, `BookDetailDbTest` | validates core catalog discovery and detail API behavior |

## Browser smoke scope
`tests/e2e/public-smoke.spec.ts` verifies:
1. the new editorial homepage shell renders
2. catalog discovery controls remain visible
3. guest access to ` /account ` redirects to login while public resources stay reachable

## Current verified execution
- `composer qa:ci` → **77 passed (350 assertions)**
- `npm run test:e2e` → **3 passed (5.4s)**

## Scope note
This assignment set is intentionally **critical-path oriented** rather than exhaustive. It is designed to catch regressions that would most affect public readers, authenticated users, and internal staff boundaries during publication and deployment.
