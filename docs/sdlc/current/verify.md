# multilingual-public-shell-and-ui-reset — verification report

## Artifact under review
- draft: `docs/sdlc/current/draft.md`
- spec: **not present for this cycle**; verification is grounded in `draft.md` + `plan.md`
- plan: `docs/sdlc/current/plan.md`
- diff / branch: `main` @ `e3a32da` (`feat: finalize multilingual public shell`)

## Commands executed
```bash
cd /home/admlibrary/kazutb-smart-library-main && \
  git status --short && git rev-parse --abbrev-ref HEAD && git rev-parse --short HEAD && git --no-pager show --stat --oneline -1

cd /home/admlibrary/kazutb-smart-library-main && \
  docker compose run --rm --entrypoint sh -v "$PWD":/app app -lc 'php artisan optimize:clear >/dev/null && php artisan test --filter="PublicShellTest|AccountPageTest"'

cd /home/admlibrary/kazutb-smart-library-main && \
  docker compose run --rm --entrypoint sh -v "$PWD":/app app -lc 'php artisan optimize:clear >/dev/null && php artisan test --filter="PublicShellTest|ExternalResourcePageTest|ShortlistPageTest|ConsolidationTest|SpaShellTest|CatalogPageTest|BookPageTest|AccountPageTest"'

cd /home/admlibrary/kazutb-smart-library-main && \
  docker compose run --rm --entrypoint sh -v "$PWD":/app app -lc './vendor/bin/pint --test'

cd /home/admlibrary/kazutb-smart-library-main && \
  docker run --rm -v "$PWD":/app -w /app node:22 bash -lc 'node -v && npm run build'

cd /home/admlibrary/kazutb-smart-library-main && \
  for pair in 'http://localhost/resources?lang=kk|<html lang="kk">|Ресурстар' 'http://localhost/contacts?lang=en|<html lang="en">|About the library and contacts' 'http://localhost/shortlist?lang=kk|<html lang="kk">|Әдебиет тізімінің жұмыс нұсқасы'; do \
    IFS='|' read -r url needle1 needle2 <<< "$pair"; \
    html=$(curl -sSf "$url"); \
    [[ "$html" == *"$needle1"* && "$html" == *"$needle2"* ]] && echo "PASS $url" || exit 1; \
  done

cd /home/admlibrary/kazutb-smart-library-main && \
  curl -I -s http://localhost/for-teachers | sed -n '1,5p'

cd /home/admlibrary/kazutb-smart-library-main && \
  docker compose run --rm --entrypoint sh -v "$PWD":/app app -lc 'php artisan optimize:clear >/dev/null && php artisan test --filter="AuthSessionMeTest|AuthSessionLifecycleTest|LoginTest"'
```

## Results
| Check | ID | Status | Evidence |
|---|---|---|---|
| requirement coverage | `V1` | WARN | The current wave can be traced to `R1`-`R7` and `UC1`-`UC5` below, but the repo is missing `docs/sdlc/current/spec.md` and the broader consolidation regression still carries stale expectations. |
| tests | `V2` | FAIL | Targeted shell/account suite passed: `13 passed (73 assertions)`. Broader public suite returned `3 failed, 83 passed (303 assertions)` — all three failures are inside `Tests\Feature\Api\ConsolidationTest`. |
| build / lint | `V3` | WARN | Node 22 build succeeded: `vite v8.0.3`, `✓ built in 1.20s`. `./vendor/bin/pint --test` failed with repo-wide style debt: `198 files, 75 style issues`. |
| dead code scan | `V4` | WARN | Legacy redirect-only views still remain in `resources/views/about.blade.php`, `resources/views/services.blade.php`, `resources/views/news.blade.php`, and `resources/views/for-teachers.blade.php` even though the live routes now redirect. |
| security / auth review | `V5` | WARN | `AccountPageTest` passes, `AuthSessionLifecycleTest` and `AuthSessionMeTest` pass, and internal staff routes still guard roles in `routes/web.php`; however `LoginTest` did not complete within the verification timeout and needs separate follow-up. |
| contract consistency | `V6` | PASS | `app/Http/Middleware/SetRequestLocale.php` sanitizes `lang` to `ru|kk|en`, live locale smoke checks all passed, and `/for-teachers` returns `HTTP/1.1 301 Moved Permanently`. |
| docs drift | `V7` | WARN | `verify.md` needed refresh and `spec.md` is absent for this SDLC cycle, so repo verification artifacts were not fully aligned before this update. |

## Requirement traceability
| Requirement | Evidence | Result | Notes |
|---|---|---|---|
| `R1` — shared calm public shell across homepage/resources/contacts/account | `resources/views/layouts/public.blade.php`, `resources/views/partials/navbar.blade.php`, `resources/views/partials/footer.blade.php`, `public/css/shell.css`; `PublicShellTest` and `ExternalResourcePageTest` pass | PASS | The common public shell is active and rendering correctly. |
| `R2` — catalog remains the product-grade working surface | `resources/views/catalog.blade.php`, `resources/js/spa/pages/CatalogPage.jsx`; `CatalogPageTest` passes and the Node 22 build succeeds | PASS | Search/filter wiring remains intact after the redesign slice. |
| `R3` — bibliographic record page stays structured and actionable | `resources/views/book.blade.php`; `BookPageTest` passes | PASS | Book detail remains stable and connected to the real API fields. |
| `R4` — teacher shortlist and role-aware account workflows stay operational | `resources/views/account.blade.php`, `resources/views/shortlist.blade.php`; `AccountPageTest` (`6 passed`) and `ShortlistPageTest` (`11 passed`) | PASS | Teacher-only workbench behavior matches the implementation. |
| `R5` — public RU/KK/EN switching is request-scoped and live | `app/Http/Middleware/SetRequestLocale.php`, `bootstrap/app.php`, `tests/Feature/PublicShellTest.php`; live smoke checks pass for `/resources?lang=kk`, `/contacts?lang=en`, and `/shortlist?lang=kk` | PASS | Locale handling is now reliable at request scope. |
| `R6` — legacy IA remains safe while consolidated into `/resources` | `routes/web.php` redirect, `ExternalResourcePageTest`, and live `curl -I` showing `HTTP/1.1 301 Moved Permanently` | PASS | Old `/for-teachers` bookmarks resolve safely. |
| `R7` — auth and role boundaries must not regress | `/account` redirect logic in `routes/web.php`, internal `abort_unless(in_array($role, ['librarian', 'admin'], true), 403)`, `AuthSessionLifecycleTest`, `AuthSessionMeTest` | WARN | Session and role gates look intact, but `LoginTest` needs its own timeout investigation. |

## Use-case coverage
| Use case | Evidence | Result | Notes |
|---|---|---|---|
| `UC1` — public visitor lands on a coherent multilingual library shell | `PublicShellTest` + Playwright/browser check on `/resources?lang=en` + live curl locale checks | PASS | `lang`, title, and hero copy match the requested locale. |
| `UC2` — reader can search catalog and inspect a book without regression | `CatalogPageTest`, `BookPageTest`, and successful production build | PASS | Core discovery surfaces still compile and render. |
| `UC3` — teacher can open shortlist and workbench from the account area | `AccountPageTest` + `ShortlistPageTest` | PASS | Faculty workflow remains functional. |
| `UC4` — legacy bookmarks do not break after the IA consolidation | `ExternalResourcePageTest` + `curl -I http://localhost/for-teachers` | PASS | Redirect is live and correct. |
| `UC5` — the broader public regression pack is clean enough to close the wave | `Tests\Feature\Api\ConsolidationTest` | FAIL | Three stale assertions still block a fully clean verification sign-off. |

## Blockers
- `Tests\Feature\Api\ConsolidationTest` still fails 3 assertions:
  1. `test_contacts_renders_with_about_content()` expects `Более 50 000` on `/contacts`
  2. `test_homepage_has_hero_search_bar()` expects `hero-search-bar`
  3. `test_homepage_has_hero_quick_links()` expects `hero-quick-links`
- `./vendor/bin/pint --test` reports repo-wide style debt (`198 files, 75 style issues`).
- `docs/sdlc/current/spec.md` is missing, so the trace chain starts from `draft.md` rather than a formal clarified spec.

## Risks / follow-ups
- Investigate whether `ConsolidationTest` should be updated to the new public-shell markers/copy or whether the implementation should restore those exact legacy hooks.
- Investigate why `tests/Feature/Auth/LoginTest.php` does not complete within the container verification timeout even though adjacent auth/session tests pass.
- Decide whether to remove or archive the now-unused legacy Blade views (`about`, `services`, `news`, `for-teachers`) to reduce dead-code drift.
- The Vite build still emits a large chunk warning for `public/build/assets/app-PuTk4XMf.js` (~1.3 MB), which is non-blocking but worth a later code-splitting pass.

## Ready for /document
- no
- notes:
  - Verification evidence is now fresh and recorded.
  - The wave should not be considered fully clean for `/document` until the three `ConsolidationTest` failures and the missing `spec.md`/trace gap are resolved or explicitly waived.
