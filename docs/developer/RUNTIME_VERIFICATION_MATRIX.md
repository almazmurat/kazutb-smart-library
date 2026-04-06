# Runtime Verification Matrix (WS4)

**Date**: 2026-04-05
**Scope**: Internal verification confidence tracking for 6 critical runtime paths.
**Environment note**: CI runs on SQLite in-memory. PostgreSQL-dependent tests require live PG and are skipped in CI.

## 1. Summary

| # | Critical path | Confidence | CI coverage | PG-only tests | Key gap |
|---|---|---|---|---|---|
| 1 | Catalog search | Partially verified | Blade/SPA wiring only | 7 tests skip | DB query coverage skips in CI |
| 2 | Book detail | Partially verified | Blade wiring only | 2 tests skip | DB query coverage skips in CI |
| 3 | Account identity | Partially verified | 6 tests run | 0 | No real CRM integration; logout untested → now tested |
| 4 | Reservation list/detail | Well verified | 9 boundary tests run | 7 data tests skip | Data-side coverage skips in CI |
| 5 | Reservation approve/reject | Well verified | 10+ boundary tests run | 0 (mocked) | Service layer only tested with mocks |
| 6 | Circulation checkout/return | Weakly verified in CI | 0 run in CI | 30+ tests skip | All tests require live PostgreSQL |

## 2. Full matrix

### Path 1 — Catalog search

| Aspect | Value |
|---|---|
| Canonical route | `GET /api/v1/catalog-db` |
| Legacy routes (frozen) | `GET /api/v1/catalog`, `GET /api/v1/catalog-external` |
| Controller | `CatalogController@dbIndex` |
| Service | `CatalogReadService` (PostgreSQL `app.document_detail_v`) |
| Middleware | None (public) |
| Test files | `CatalogDbSearchTest` (7, PG), `CatalogPageTest` (Blade, SQLite), `SpaCatalogWiringTest` (SQLite) |
| CI status | Blade/SPA tests run; DB search tests skip (PG-only) |
| Runtime probe | `docker-compose.yml` healthcheck hits `/api/v1/catalog-db?limit=1` |
| Confidence | **Partially verified** |
| Main gap | DB query behavior (filters, sort, pagination) not verified in CI |

### Path 2 — Book detail

| Aspect | Value |
|---|---|
| Canonical route | `GET /api/v1/book-db/{isbn}` |
| Legacy routes (frozen) | `GET /api/v1/catalog/{isbn}` |
| Controller | `BookController@dbShow` |
| Service | `BookDetailReadService` (PostgreSQL `app.document_detail_v`) |
| Middleware | None (public) |
| Test files | `BookDetailDbTest` (2, PG), `BookPageTest` (Blade, SQLite) |
| CI status | Blade test runs; DB detail test skips (PG-only) |
| Confidence | **Partially verified** |
| Main gap | DB-level detail retrieval + 404 handling not verified in CI |

### Path 3 — Account identity / auth

| Aspect | Value |
|---|---|
| Canonical routes | `POST /api/login`, `GET /api/v1/me`, `POST /api/v1/logout` |
| Controller | `AuthController` (login, me, logout) |
| Service | External CRM auth API (`services.external_auth.login_url`) |
| Middleware | Session-based; `EnsureInternalCirculationStaff` for staff routes |
| Test files | `AuthSessionMeTest` (2, SQLite), `LoginTest` (2, SQLite), `AuthSessionLifecycleTest` (4, SQLite) |
| CI status | All tests run (SQLite) |
| Confidence | **Partially verified** |
| Main gap | Login tests use User model, not real CRM external call; external auth API untestable without CRM |

### Path 4 — Reservation list/detail

| Aspect | Value |
|---|---|
| Canonical routes | `GET /api/v1/account/reservations`, `GET /api/integration/v1/reservations`, `GET /api/integration/v1/reservations/{id}` |
| Controllers | `AccountController@reservations`, `ReservationReadController@index`, `ReservationReadController@show` |
| Service | `IntegrationReservationReadService` (PostgreSQL `public.Reservation`) |
| Middleware | Session (account), `EnsureIntegrationBoundary` (integration) |
| Test files | `AccountReservationsTest` (7, PG), `ReservationReadTest` (9, SQLite+mocks) |
| CI status | ReservationReadTest runs (SQLite+mocks); AccountReservationsTest skips (PG) |
| Confidence | **Well verified** (boundary), **Partially** (data) |
| Main gap | Data-side query joins/pagination only tested with live PG |

### Path 5 — Reservation approve/reject

| Aspect | Value |
|---|---|
| Canonical routes | `POST /api/integration/v1/reservations/{id}/approve`, `POST .../reject` |
| Controller | `ReservationMutateController@approve`, `@reject` |
| Service | `IntegrationReservationWriteService` (PostgreSQL `public.Reservation`) |
| Middleware | `EnsureIntegrationBoundary`, `throttle:integration-mutate`, `LogIntegrationRequest` |
| Test files | `ReservationMutateTest` (10, SQLite+mocks), `ReservationMutateRetirementConsistencyTest` (SQLite+mocks) |
| CI status | All tests run (SQLite+mocks) |
| Confidence | **Well verified** (contract/boundary) |
| Main gap | Service layer tested with mocks only; real PG mutation path not tested in CI |

### Path 6 — Internal circulation checkout/return

| Aspect | Value |
|---|---|
| Canonical routes | `POST .../checkouts`, `POST .../returns`, `POST .../loans/{id}/renew`, `GET .../loans/{id}`, `GET .../copies/{id}/active-loan`, `GET .../readers/{id}/loans` |
| Controller | `InternalCirculationController` (6 methods) |
| Services | `CirculationLoanWriteService`, `CirculationLoanReadService` |
| Middleware | `EnsureInternalCirculationStaff` (librarian/admin role) |
| Test files | `InternalCirculationCheckoutReturnTest` (9, PG), `InternalCirculationRenewalTest` (11, PG), `InternalCirculationReadbackTest` (PG) |
| CI status | **All tests skip** (PG-only) |
| Confidence | **Weakly verified in CI** — tests exist but none run in CI |
| Main gap | Zero CI coverage; all checkout/return/renew/read tests require live PostgreSQL |

## 3. Environment verification limits

| Limit | Impact | Mitigation |
|---|---|---|
| CI uses SQLite in-memory | PostgreSQL-dependent tests skip (~30+ tests) | Run `composer test` with live PG locally before deploy |
| External CRM auth API | Login flow cannot be tested end-to-end without CRM | Session-inject tests cover auth state, not CRM round-trip |
| Docker build-based deployment | Tests not in container image (.dockerignore excludes tests/) | Test on host or in dev container only |
| Node 18.19 on host | Vite build requires 20.19+ | Frontend builds run in CI or container only |

## 4. Verification commands

| Command | What it covers | Environment |
|---|---|---|
| `composer test` | All tests (PG tests skip on SQLite) | Any |
| `composer test:critical-paths` | All 6 critical-path test files grouped | Any (PG tests skip on SQLite) |
| `composer test:internal` | Circulation + copy + review tests | Needs live PG |
| `composer test:reservation-core` | Reservation + circulation + copy | Needs live PG |
| `composer test:integration-reservations` | CRM integration boundary tests | SQLite OK (mocked) |
| `composer test:stewardship` | Data quality stewardship tests | Needs live PG |
| `bash scripts/dev/check-runtime-critical-paths.sh` | Summary of test files, existence, and coverage status | Any |

## 5. CI improvement opportunities (not implemented yet)

| Opportunity | Effort | Value |
|---|---|---|
| Add PostgreSQL service to CI | Medium | High — would enable ~30 skipped tests |
| Add SQLite-compatible stubs for PG-only tests | Medium | Medium — partial coverage without PG |
| Add runtime smoke endpoint | Low | Medium — gives container-level confidence |

## 6. Next verification step

Add PostgreSQL service container to CI workflow to enable the ~30 skipped critical-path tests. This is the single highest-value verification improvement remaining.
