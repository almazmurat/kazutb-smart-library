# global-library-frontend-modernization — implementation plan

## Scope summary
- Deliver the next **global frontend modernization wave** across the existing Blade pages, the React/Vite SPA under `/app`, and the internal operational screens so the platform feels like one coherent university library product.
- Ground the content and information architecture in the official KazUTB library page (`https://www.kaztbu.edu.kz/ru/biblioteka`) while using the Lovable reference (`https://lovable.dev/projects/22d25805-2d7b-424f-b3d2-ee61cbc34d43`) only as art-direction inspiration.
- Keep Laravel routes, API payloads, CRM/session auth, and PostgreSQL schema stable; this plan is intentionally **frontend-first** with only minimal supporting controller/view wiring where the current UI needs content or locale state.

## Delivery decisions fixed in /design
- Keep the current mixed architecture: **Laravel + Blade + React/Vite + PostgreSQL + Docker Compose**.
- Preserve canonical public/reader endpoints such as `/api/v1/landing`, `/api/v1/catalog-db`, `/api/v1/book-db/{isbn}`, `/api/v1/account/*`, `/api/v1/shortlist/*`, and `/api/v1/external-resources`.
- Treat `/for-teachers` as a **legacy entry point** that will be absorbed into the broader IA:
  - teacher-facing support content moves into `/resources`, `/shortlist`, and `/account`
  - the standalone nav item is removed
  - the legacy route should end as a `301` redirect to `/resources`
- Implement tri-language support as the **simplest viable public-content layer** (`kk`, `ru`, `en`) rather than a large full-product localization rewrite.

## Official content mapping
| Source content block | Planned destination surface |
|---|---|
| Library identity, mission, value, hero narrative | `/`, `LandingController`, shared public shell |
| Director, librarians, hours, contacts | `/contacts` |
| Licensed resources, agreements, access guidance | `/resources` |
| Discovery and academic navigation | `/discover`, `/catalog`, `/app/catalog` |
| Teacher/syllabus support and shortlist workflow | `/resources`, `/shortlist`, `/account` |
| Rules / access notes for digital materials | `/resources`, `/digital-viewer/{materialId}` |

## Traceability matrix
| Requirement | Use cases | Plan steps | Tests |
|---|---|---|---|
| `R1` | `UC1`, `UC2`, `UC3`, `UC4` | `S1`, `S2`, `S3`, `S4`, `S5` | `T1`, `T2`, `T4`, `T5` |
| `R2` | `UC1`, `UC3`, `UC5` | `S1`, `S2`, `S3` | `T1`, `T3`, `T6` |
| `R3` | `UC1`, `UC2`, `UC4` | `S2`, `S3`, `S4`, `S5` | `T1`, `T2`, `T5`, `T7` |
| `R4` | `UC3`, `UC6` | `S1`, `S3`, `S6` | `T3`, `T6` |
| `R5` | `UC1`, `UC5` | `S1`, `S2`, `S4` | `T1`, `T6` |
| `R6` | `UC2`, `UC3`, `UC4` | `S1`, `S3`, `S4`, `S5`, `S6` | `T2`, `T3`, `T4`, `T5` |
| `R7` | `UC2` | `S2`, `S4` | `T2`, `T4` |
| `R8` | `UC4` | `S5`, `S6` | `T5`, `T7` |
| `R9` | `UC1`, `UC2`, `UC4` | `S2`, `S5` | `T1`, `T7` |
| `R10` | `UC1`, `UC2`, `UC4`, `UC5` | `S2`, `S4`, `S5`, `S6` | `T1`, `T4`, `T5`, `T7` |
| `R11` | `UC1`, `UC2`, `UC3`, `UC4`, `UC5`, `UC6` | `S1`, `S2`, `S3`, `S4`, `S5`, `S6` | `T1`-`T7` |

## Implementation steps
- [x] `S1.` **Lock the IA, content sources, and lightweight locale strategy before visual rewrites**.
  - map the official library content into the existing route families (`/`, `/contacts`, `/resources`, `/discover`, `/shortlist`, `/account`)
  - define the simple locale-state approach for public content (`?lang=kk|ru|en` and/or session-backed selection) without changing the auth model
  - formalize the `/for-teachers` retirement path and update affected tests from “page exists” to “legacy redirect + consolidated faculty support”

- [x] `S2.` **Build the shared visual system and public shell foundation**.
  - centralize typography, spacing, color tokens, card styles, badges, CTA groups, empty states, and motion rules in `public/css/shell.css`, `resources/css/spa.css`, and `resources/css/app.css`
  - modernize `resources/views/layouts/public.blade.php`, `resources/views/partials/navbar.blade.php`, and `resources/views/partials/footer.blade.php`
  - add strong focus states, keyboard-visible navigation, and `prefers-reduced-motion` coverage as first-class constraints

- [x] `S3.` **Rebuild the public content routes around the official library narrative and new IA**.
  - refresh `resources/views/welcome.blade.php` so the homepage presents the scientific library identity, modern editorial sections, and strong resource/discovery CTAs
  - migrate institutional/about content into `resources/views/contacts.blade.php` and resource access/support content into `resources/views/resources.blade.php`
  - retune `resources/views/discover.blade.php` for discovery-first academic entry points
  - remove the top-level `/for-teachers` Blade landing page from navigation and replace the route with the agreed legacy redirect behavior

- [x] `S4.` **Unify the reader journey surfaces without breaking their current APIs**.
  - restyle `resources/views/catalog.blade.php`, `book.blade.php`, `auth.blade.php`, `account.blade.php`, `shortlist.blade.php`, and `digital-viewer.blade.php`
  - keep the live data hookups untouched: `/api/v1/catalog-db`, `/api/v1/book-db/{isbn}`, `/api/v1/account/*`, `/api/v1/shortlist/*`, and digital-material endpoints
  - make teacher-relevant actions discoverable through `/shortlist` and `/account` instead of a separate public landing page

- [x] `S5.` **Bring the `/app` SPA and internal operational surfaces into the same design language**.
  - update `resources/js/spa/App.jsx`, `resources/js/spa/components/AppLayout.jsx`, `resources/js/spa/pages/CatalogPage.jsx`, `resources/js/spa/pages/NotFoundPage.jsx`, and `resources/css/spa.css`
  - refresh `resources/views/internal-dashboard.blade.php`, `internal-review.blade.php`, `internal-stewardship.blade.php`, `internal-circulation.blade.php`, and `internal-ai-chat.blade.php` with a denser operator-friendly variant of the same system
  - keep operational scanning fast: tighter spacing, status emphasis, and fewer decorative effects than the public marketing shell

- [x] `S6.` **Finish with contract-safe regression coverage, browser verification, and rollout proof**.
  - update or add feature tests for nav/footer structure, public landing/info pages, `/for-teachers` redirect behavior, SPA shell rendering, and internal page smoke coverage
  - run PHP tests, a production Vite build, and browser-level smoke checks on `http://10.0.1.8`
  - keep rollback simple and file-local by avoiding schema or service-contract rewrites

## Impacted files and layers
### Backend
- `routes/web.php`
  - keep public/internal route truth stable; the only planned route-behavior change is the legacy consolidation of `/for-teachers`
- `routes/api.php`
  - preserve canonical reader/internal APIs already consumed by the UI
- `app/Http/Controllers/Api/LandingController.php`
  - likely minor content/locale-aware payload cleanup for the modern homepage while keeping existing JSON sections stable
- `app/Http/Controllers/Api/BookController.php`
- `app/Http/Controllers/Api/AccountController.php`
- `app/Http/Controllers/Api/ExternalResourceController.php`
  - contract-preservation review only; redesign should consume these controllers as-is unless a tiny presentation helper is needed
- `app/Services/Library/CatalogReadService.php`
  - search/sort/filter contract stays unchanged and remains the truth for `/catalog` and `/app/catalog`

### Frontend
- shared shell:
  - `resources/views/layouts/public.blade.php`
  - `resources/views/partials/navbar.blade.php`
  - `resources/views/partials/footer.blade.php`
  - `public/css/shell.css`
- public + reader Blade routes:
  - `resources/views/welcome.blade.php`
  - `resources/views/contacts.blade.php`
  - `resources/views/resources.blade.php`
  - `resources/views/discover.blade.php`
  - `resources/views/catalog.blade.php`
  - `resources/views/book.blade.php`
  - `resources/views/auth.blade.php`
  - `resources/views/account.blade.php`
  - `resources/views/shortlist.blade.php`
  - `resources/views/digital-viewer.blade.php`
  - `resources/views/for-teachers.blade.php` (expected to be retired or reduced to redirect-only handling)
- SPA shell:
  - `resources/js/spa/App.jsx`
  - `resources/js/spa/components/AppLayout.jsx`
  - `resources/js/spa/pages/CatalogPage.jsx`
  - `resources/js/spa/pages/NotFoundPage.jsx`
  - `resources/css/spa.css`
- internal surfaces:
  - `resources/views/internal-dashboard.blade.php`
  - `resources/views/internal-review.blade.php`
  - `resources/views/internal-stewardship.blade.php`
  - `resources/views/internal-circulation.blade.php`
  - `resources/views/internal-ai-chat.blade.php`
- build entrypoints:
  - `vite.config.js`
  - `resources/css/app.css`
  - `resources/js/spa/main.jsx`

### Database
- `database/migrations/*`
  - **no planned edits**
- no schema, seed, or query-shape rewrite is part of this redesign wave

### CRM / auth boundary
- `config/services.php` and session-based `library.user` behavior remain intact
- login/logout/CRM handoff stays HTTP-boundary based; no CRM UI embedding or protocol change is in scope
- any locale state must stay library-side and must not alter CRM token exchange or RBAC flow

### Tests
- public/shell coverage to update or add:
  - `tests/Feature/PublicShellTest.php`
  - `tests/Feature/ExternalResourcePageTest.php`
  - `tests/Feature/Api/ConsolidationTest.php`
- reader flow coverage to keep green:
  - `tests/Feature/CatalogPageTest.php`
  - `tests/Feature/BookPageTest.php`
  - `tests/Feature/AccountPageTest.php`
  - `tests/Feature/ShortlistPageTest.php`
- SPA/internal smoke coverage:
  - `tests/Feature/SpaShellTest.php`
  - `tests/Feature/InternalDashboardPageTest.php`
  - `tests/Feature/InternalReviewPageTest.php`
  - `tests/Feature/InternalStewardshipPageTest.php`
  - `tests/Feature/InternalCirculationPageTest.php`
- contract checks that must keep passing:
  - `tests/Feature/Api/CatalogDbSearchTest.php`
  - `tests/Feature/Api/BookDetailDbTest.php`
  - `tests/Feature/Api/AccountSummaryDbTest.php`

## Contract notes
- routes / payloads to preserve:
  - public routes: `GET /`, `/catalog`, `/contacts`, `/resources`, `/discover`, `/login`, `/account`, `/shortlist`, `/book/{isbn}`, `/digital-viewer/{materialId}`
  - SPA shell: `GET /app/{any?}` renders the same React root for `/app` and subroutes
  - internal routes: `GET /internal/dashboard`, `/internal/review`, `/internal/stewardship`, `/internal/circulation`, `/internal/ai-chat`
  - legacy consolidation: `/for-teachers` changes from a standalone destination to a redirect-only legacy path
  - public APIs: `/api/v1/landing`, `/api/v1/catalog-db`, `/api/v1/book-db/{isbn}`, `/api/v1/account/*`, `/api/v1/shortlist/*`, `/api/v1/external-resources`, `/api/v1/documents/{documentId}/digital-materials`, `/api/v1/digital-materials/{id}/stream`
- service interfaces:
  - continue using existing read services and controller shapes; do not add redesign-only business logic to `CatalogReadService`, book/account services, or CRM auth plumbing
- DB effects / migrations:
  - none expected
  - if implementation later requires persistent locale preferences or new content tables, that would need a separate clarify/design cycle

## Test plan
- `T1.` **Public shell + accessibility regression**: homepage, contacts, resources, discover, and login render with the shared shell, visible focus states, and reduced-motion-safe behavior.
- `T2.` **Reader journey regression**: `/catalog`, `/book/{isbn}`, `/account`, and `/shortlist` preserve current API integrations and session-aware behavior after the redesign.
- `T3.` **Faculty-support consolidation regression**: `/for-teachers` is no longer advertised in nav/footer, and the legacy route redirects users into the new faculty-support experience under `/resources`.
- `T4.` **SPA parity regression**: `/app` and `/app/catalog` still render the same SPA shell and keep canonical `/api/v1/catalog-db` behavior.
- `T5.` **Internal operations regression**: dashboard/review/stewardship/circulation/AI chat pages still render expected task surfaces and do not lose scanning density.
- `T6.` **Tri-language smoke verification**: the main public content can be viewed in `kk`, `ru`, and `en` without losing route compatibility or core meaning.
- `T7.` **Responsive/performance smoke**: desktop/mobile layouts remain stable, animation remains modest, and the production Vite bundle still builds cleanly.

## Verification commands
```bash
php artisan test --filter='PublicShellTest|ExternalResourcePageTest|ConsolidationTest'
php artisan test --filter='CatalogPageTest|BookPageTest|AccountPageTest|ShortlistPageTest'
php artisan test --filter='SpaShellTest|Internal(Dashboard|Review|Stewardship|Circulation)PageTest'
php artisan test --filter='CatalogDbSearchTest|BookDetailDbTest|AccountSummaryDbTest'
npm run build

# Docker / fallback commands for this repo:
docker compose run --rm --entrypoint sh -v "$PWD":/app app -lc 'php artisan test --filter="PublicShellTest|ExternalResourcePageTest|ConsolidationTest|CatalogPageTest|BookPageTest|AccountPageTest|ShortlistPageTest|SpaShellTest"'
docker run --rm -v "$PWD":/workspace -w /workspace node:22 sh -lc 'npm run build'
```

## Risks / rollback notes
- **Scope-creep risk:** trying to redesign content, IA, localization, and new features at the same time. Mitigation: keep this wave presentation-first and contract-safe.
- **Consistency risk:** Blade, SPA, and internal views may diverge again if tokens are duplicated. Mitigation: land the shared visual system in `shell.css` and `spa.css` first.
- **Localization risk:** a full Laravel i18n rewrite would slow delivery. Mitigation: keep the first implementation to a lightweight public-content locale layer only.
- **Operational UX risk:** internal staff pages could become too “marketing-like.” Mitigation: use a denser, lower-motion operator variant for internal routes.
- **Rollback plan:** revert changed Blade/CSS/SPA view files and the `/for-teachers` route adjustment; no migration or deep service rollback should be required.

## Not in scope reminders
- no Laravel/Blade-to-Next.js or other stack migration
- no CRM auth redesign or direct CRM UI embedding
- no PostgreSQL schema or migration work in this wave
- no new catalog/reservation/circulation business rules hidden inside the redesign
- no direct copying of protected Lovable assets or copyrighted layouts
- no broad multilingual rewrite of every backend message, validation error, or admin workflow beyond the main public-content experience

## Ready for /verify
- yes
- notes:
  - the public IA now consolidates faculty support into the main routes: `/for-teachers` returns `301` to `/resources`, and the shared nav/footer no longer advertise a separate teacher landing page
  - the public shell now exposes a lightweight `kk / ru / en` locale switcher and localized copy on the main public routes
  - fresh implementation evidence collected on 2026-04-08:
    - `docker compose run --rm --entrypoint sh -v "$PWD":/app app -lc 'php artisan optimize:clear >/dev/null && php artisan test --filter="PublicShellTest|ExternalResourcePageTest|ShortlistPageTest|ConsolidationTest|SpaShellTest|CatalogPageTest|BookPageTest|AccountPageTest"'` → `81 passed (259 assertions)`
    - `docker run --rm -v "$PWD":/workspace -w /workspace node:22 sh -lc 'npm run build >/tmp/build.log && tail -n 25 /tmp/build.log'` → Vite production build succeeded
    - live smoke check after `docker compose up -d --build app`:
      - `GET http://10.0.1.8/for-teachers` → `301 Moved Permanently` to `/resources`
      - `GET http://10.0.1.8/resources?lang=en` → `200` with `data-locale-switcher` and `Digital collections and research databases`
      - `GET http://10.0.1.8/contacts?lang=en` → `200` with `About the library and contacts`