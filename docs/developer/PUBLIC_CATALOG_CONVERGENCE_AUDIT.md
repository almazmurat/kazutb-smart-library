# Public Catalog Convergence Audit (WS1)

**Date**: 2026-04-05  
**Last refined**: 2026-04-05 (legacy freeze/deprecation hardening step)
**Scope boundary**: **internal-only** convergence work in the library platform. **No CRM-facing API expansion or contract changes** are included.

## 1. Executive summary

The public catalog has a clear DB-backed canonical path, but parallel transitional paths are still active. Canonical Blade pages (`/catalog`, `/book/{isbn}`) are wired to canonical DB endpoints (`/api/v1/catalog-db`, `/api/v1/book-db/{isbn}`), while legacy/demo/proxy paths remain in routes and are still referenced by reader and healthcheck flows.

## 2. Canonical public catalog path decision

### Canonical active path (single decision)
- **Public catalog entry route**: `GET /catalog` (Blade: `resources/views/catalog.blade.php`)
- **Public catalog search/list API**: `GET /api/v1/catalog-db` (`CatalogController::dbIndex` + `CatalogReadService`)
- **Book detail route**: `GET /book/{isbn}` (Blade: `resources/views/book.blade.php`)
- **Book detail API**: `GET /api/v1/book-db/{isbn}` (`BookController::dbShow` + `BookDetailReadService`)

### Non-canonical public catalog path
- `GET /app/catalog` (SPA shell path) is **not** canonical for this window.

## 3. Full catalog surface classification

| Surface | Location | Classification | Notes |
|---|---|---|---|
| Public catalog page route | `routes/web.php` (`/catalog`) | canonical | Primary public catalog entry |
| Public book detail route | `routes/web.php` (`/book/{isbn}`) | canonical | Primary public detail entry |
| Reader route | `routes/web.php` (`/book/{isbn}/read`) | active but transitional | Named `reader.transitional`; frozen for controlled migration only |
| SPA shell route | `routes/web.php` (`/app/{any?}`) | active but transitional | Exists and is routable, but not canonical |
| Catalog DB API route | `routes/api.php` (`/v1/catalog-db`) | canonical | Canonical search/list API |
| Book DB API route | `routes/api.php` (`/v1/book-db/{isbn}`) | canonical | Canonical detail API |
| Legacy catalog API route | `routes/api.php` (`/v1/catalog`) | **removed** | Deleted in delete-after-confirmation wave |
| Legacy detail alias route | `routes/api.php` (`/v1/catalog/{isbn}`) | **removed** | Deleted in delete-after-confirmation wave |
| External catalog proxy route | `routes/api.php` (`/v1/catalog-external`) | active but transitional | Explicitly marked `[WS1-FROZEN][TRANSITIONAL-EXTERNAL]` |
| Catalog DB handler | `CatalogController::dbIndex` | canonical | DB-backed search |
| Catalog demo handler | `CatalogController::index` | **removed** | Deleted with legacy route |
| Catalog external proxy handler | `CatalogController::proxy` | active but transitional | Controller-level freeze note for transitional migration window |
| Book DB handler | `BookController::dbShow` | canonical | DB-backed detail |
| Book alias handler | `BookController::show` | **removed** | Deleted with legacy route |
| Catalog read service | `CatalogReadService` | canonical | DB view `app.document_detail_v` |
| Book detail read service | `BookDetailReadService` | canonical | DB-backed detail + location availability |
| Landing API handler | `LandingController::index` | canonical | Uses `CatalogReadService` DB-backed catalog data |
| Public catalog Blade | `resources/views/catalog.blade.php` | canonical | Uses `/api/v1/catalog-db` |
| Public book Blade | `resources/views/book.blade.php` | canonical | Uses `/api/v1/book-db/` |
| Public landing Blade catalog block | `resources/views/welcome.blade.php` | active but transitional | Uses `/api/v1/catalog-db`, but landing API pulls external data |
| Reader Blade | `resources/views/reader.blade.php` | active — canonical primary, external fallback | Uses `/api/v1/book-db/{isbn}` as primary, `/api/v1/catalog-external` as fallback |
| SPA shell view | `resources/views/spa.blade.php` | active but transitional | Entry shell only |
| SPA catalog route map | `resources/js/spa/App.jsx` | active but transitional | `/app/catalog` route exists |
| SPA catalog page data call | `resources/js/spa/pages/CatalogPage.jsx` | canonical | Calls `/api/v1/catalog-db` with canonical params (`q`, `page`, `limit`) |
| SPA API helper | `resources/js/spa/lib/api.js` | active but transitional | Generic helper; CatalogPage now uses canonical `/api/v1/catalog-db` |
| Catalog page feature tests | `tests/Feature/CatalogPageTest.php` | canonical | Covers canonical `/catalog` + `/api/v1/catalog-db` wiring |
| Book page feature tests | `tests/Feature/BookPageTest.php` | canonical | Covers canonical `/book/{isbn}` + `/api/v1/book-db` wiring |
| Catalog DB API tests | `tests/Feature/Api/CatalogDbSearchTest.php` | canonical | DB-backed search coverage |
| Book DB API tests | `tests/Feature/Api/BookDetailDbTest.php` | canonical | DB-backed detail coverage |
| SPA shell tests | `tests/Feature/SpaShellTest.php` | active but transitional | Shell-only checks; no canonical API behavior |
| Container healthcheck | `docker-compose.yml` (`/api/v1/catalog-db?limit=1`) | canonical | Probes canonical DB-backed catalog API path |

## 4. Convergence debt analysis

1. **Duplicate routes/controllers** — **Resolved**  
   - Legacy `/api/v1/catalog` and `/api/v1/catalog/{isbn}` deleted.  
   - Canonical: `/api/v1/catalog-db`, `/api/v1/book-db/{isbn}`.

2. **Blade vs SPA overlap**  
   - Blade catalog/detail are operational and canonical.  
   - SPA CatalogPage now calls canonical `/api/v1/catalog-db` (fixed in convergence step).

3. **External proxy dependency** — **Mostly resolved**  
   - Reader uses canonical `/api/v1/book-db/{isbn}` as primary.  
   - `/api/v1/catalog-external` kept as reader fallback only.

4. **Misleading naming debt** — **Reduced**  
   - Legacy "clean" names removed; only `-db` canonical and `-external` transitional remain.

5. **Runtime confidence debt** — **Improved**  
   - `ReaderConvergenceTest` verifies canonical API wiring.  
   - SPA path remains transitional.

## 4.1 Reader/external transitional flow audit

| Surface | Current role | Classification | Evidence |
|---|---|---|---|
| `GET /book/{isbn}/read` | Transitional reader page entry route | transitional but replaceable | `routes/web.php` (`reader.transitional`) |
| `resources/views/reader.blade.php` | Reader UI fetches book data from external proxy endpoint by `q=isbn` | transitional but replaceable | `API_ENDPOINT = '/api/v1/catalog-external'` |
| `GET /api/v1/catalog-external` (`CatalogController::proxy`) | Proxies requests to external host `http://10.0.1.8:5173/api/v1/catalog` | transitional but replaceable | `CatalogController::proxy()` |
| External upstream host availability | Runtime dependency for reader flow | unclear / needs runtime verification | external network dependency is outside this repo |
| Caller references to `/book/{isbn}/read` in app | No active public callers found in Blade/SPA routes | still needed (for direct/deep links) | repository grep: route exists, no internal caller links found |

### Reader/external controlled migration plan (non-destructive)

1. **Freeze now (done in this step)**  
   - Keep `/book/{isbn}/read` and `/api/v1/catalog-external` but mark them as WS1-frozen transitional surfaces.

2. **Migration target**  
   - Move reader content data source from `/api/v1/catalog-external?q={isbn}` to canonical `/api/v1/book-db/{isbn}` payload adaptation in `reader.blade.php`.

3. **Intermediate safe step**  
   - Introduce dual-read fallback in reader page (prefer `/api/v1/book-db/{isbn}`, fallback to external proxy only on explicit miss/compat mode).

4. **Verification step**  
   - Add focused feature test asserting reader page uses canonical endpoint string and no default dependency on `/api/v1/catalog-external`.

5. **Deletion gate (later)**  
   - Only remove `/api/v1/catalog-external` and `/book/{isbn}/read` after:  
     a) no runtime callers remain,  
     b) controlled viewer constraints are preserved,  
     c) migration verification passes in runtime environment.

## 5. Safe cleanup plan

### keep
- Keep `/catalog` + `/book/{isbn}` Blade routes and views as primary public UX.
- Keep `/api/v1/catalog-db` and `/api/v1/book-db/{isbn}` as canonical APIs.
- Keep DB-backed services (`CatalogReadService`, `BookDetailReadService`) unchanged.

### freeze
- Freeze new usage of `/api/v1/catalog`, `/api/v1/catalog/{isbn}`, `/api/v1/catalog-external`, `/book/{isbn}/read`, and `/app/*` for public catalog default flow.
- Freeze healthcheck dependence on legacy endpoint as a tracked debt item (do not expand this pattern).

### refactor next
- Migrate `reader.blade.php` data source to canonical `/api/v1/book-db/{isbn}` with controlled compatibility fallback.

### archive later
- Archive transitional reader/proxy flow docs and code paths after controlled viewer replacement is ready.
- Archive legacy alias route docs once canonical naming plan is adopted.

### delete after confirmation
- ~~Delete `/api/v1/catalog` demo handler/route~~ — **Done** (removed).
- ~~Delete `/api/v1/catalog/{isbn}` alias~~ — **Done** (removed).
- Delete `/api/v1/catalog-external` and `/book/{isbn}/read` after reader fallback is confirmed unnecessary in production.

### ignore for default startup
- Ignore `/app/*` SPA catalog path for default public startup path in current convergence window.

## 6. Automation plan for current window

1. Keep the lightweight **catalog path guard script** to detect canonical wiring regressions and highlight transitional debt.
2. Run the guard from composer helper scripts in local/CI workflows.
3. Keep focused SPA wiring checks (`tests/Feature/SpaCatalogWiringTest.php`) to prevent regressions back to non-canonical paths.
4. Require explicit WS1 freeze markers on legacy/transitional routes to prevent silent reactivation of non-canonical paths.
5. Use warnings to track unresolved convergence debt without forcing destructive cleanup in this step.

## 7. Concrete files created/updated

- **Created**: `docs/developer/PUBLIC_CATALOG_CONVERGENCE_AUDIT.md`
- **Created**: `scripts/dev/check-public-catalog-paths.sh`
- **Updated**: `composer.json` (helper script entries for catalog path checks)
- **Updated**: `resources/js/spa/pages/CatalogPage.jsx` (canonical API/detail wiring)
- **Updated**: `app/Http/Controllers/Api/LandingController.php` (DB-backed catalog source)
- **Updated**: `docker-compose.yml` (canonical catalog healthcheck probe)
- **Created**: `tests/Feature/SpaCatalogWiringTest.php` (focused SPA wiring regression checks)
- **Created**: `tests/Feature/ReaderConvergenceTest.php` (reader canonical API wiring + fallback verification)
- **Updated**: `routes/api.php` (legacy/transitional route freeze markers)
- **Updated**: `routes/web.php` (transitional reader route freeze marker + named route)
- **Updated**: `app/Http/Controllers/Api/CatalogController.php` (freeze/deprecation annotations)
- **Updated**: `app/Http/Controllers/Api/BookController.php` (freeze/deprecation annotation)

## 8. Next best step

Legacy routes `/api/v1/catalog` and `/api/v1/catalog/{isbn}` have been removed. Reader data source migrated to canonical `/api/v1/book-db/{isbn}` with fallback to external proxy. Next: remove external proxy route and `CatalogController::proxy()` once reader fallback is confirmed unnecessary in production.
