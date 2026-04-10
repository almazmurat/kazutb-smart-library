# homepage-hero-spacing-for-kaztbu-note — implementation plan

## Scope summary
Apply a small, frontend-only layout refinement to the homepage hero in `resources/views/welcome.blade.php` so the large title, round institutional mark, and `Библиотека КазТБУ` note card no longer crowd each other on desktop while preserving current search, quick links, and responsive behavior.

## Traceability matrix
| Requirement | Use cases | Plan steps | Tests |
|---|---|---|---|
| `R1` | `UC1` | `S1`, `S2` | `T1`, `T2` |
| `R2` | `UC1` | `S2` | `T1` |
| `R3` | `UC1`, `UC3` | `S1`, `S2` | `T2` |
| `R4` | `UC2` | `S3` | `T2`, `T3` |
| `R5` | `UC3` | `S2`, `S4` | `T3`, `T4` |

## Implementation steps
- [x] `S1.` Inspect the existing hero composition in `resources/views/welcome.blade.php` and identify the exact cause of the crowding (likely oversized heading width, too-tight grid proportions, or insufficient vertical spacing in `.landing-campus-panel`).
- [x] `S2.` Adjust only the homepage hero CSS/markup to create clear separation between `.landing-title` and `.landing-campus-note` — for example by constraining the intro column width, increasing grid gap, and/or adding stronger spacing rules for the note card panel.
- [x] `S3.` Preserve the current homepage contracts: route `/` in `routes/web.php`, `hero-search-bar`, `hero-quick-links`, and `hero-campus-mark` must remain present and unchanged in purpose.
- [x] `S4.` Verify the refined layout with targeted regression tests plus browser checks on desktop and mobile widths, then refresh `docs/sdlc/current/verify.md` with the new evidence.

## Impacted files and layers
### Backend
- `routes/web.php` — no route changes expected; this confirms `/` remains the existing Blade homepage entry.

### Frontend
- `resources/views/welcome.blade.php`
  - CSS blocks for `.landing-hero-grid`, `.landing-intro`, `.landing-title`, `.landing-campus-panel`, `.landing-campus-note`
  - hero markup under `<section class="landing-hero" ...>`
- likely edit locations are the hero style block and the `landing-hero-grid` markup near the top of the homepage template

### Database
- none

### CRM / auth boundary
- none; no session, login, or external-auth changes are needed

### Tests
- `tests/Feature/Api/ConsolidationTest.php`
  - reuse the existing homepage smoke assertions for `hero-search-bar`, `hero-quick-links`, and `hero-campus-mark`
  - only add or adjust assertions if a stable layout marker is needed for the spacing fix

## Contract notes
- routes / payloads:
  - `GET /` continues to return `view('welcome')`
  - no API routes or payloads are involved
- service interfaces:
  - none; no controller/service changes are expected
- DB effects / migrations:
  - none

## Test plan
- `T1.` Verify the homepage still renders the institutional identity block (`hero-campus-mark` and `Библиотека КазТБУ`) after the spacing change.
- `T2.` Verify the homepage still renders `hero-search-bar` and `hero-quick-links` so the search-first flow is preserved.
- `T3.` Check the page visually in a browser at desktop and mobile widths to confirm the note card no longer visually touches the heading.
- `T4.` Run a frontend production build to ensure the updated Blade/CSS changes compile and ship cleanly.

## Verification commands
```bash
cd /home/admlibrary/kazutb-smart-library-main && \
  docker compose run --rm --entrypoint sh -v "$PWD":/app app -lc 'php artisan test --filter="test_homepage_has_hero_search_bar|test_homepage_has_hero_quick_links|test_homepage_has_kaztbu_identity_mark|test_homepage_no_advantages_section"'

cd /home/admlibrary/kazutb-smart-library-main && \
  docker run --rm -v "$PWD":/app -w /app node:22 bash -lc 'npm run build'
```

## Risks / rollback notes
- Risk is low and isolated to homepage presentation only.
- The main regression risk is accidentally pushing the search bar or quick links into awkward positions while fixing the title/note spacing.
- Rollback is simple: revert the `resources/views/welcome.blade.php` hero CSS/markup changes.

## Not in scope reminders
- Do not redesign lower homepage sections.
- Do not add new assets, endpoints, or backend logic.
- Do not rewrite the existing KazТБУ messaging beyond minor fit/readability adjustments.

## Ready for /implement
- completed
- notes:
  - The small Blade/CSS refinement has been applied in `resources/views/welcome.blade.php`.
  - No migration, backend, or contract work was required.

## Ready for /verify
- yes
- notes:
  - Preliminary evidence is green: the focused homepage regression suite passed (`4 passed, 12 assertions`) and the Node 22 production build succeeded (`vite build`, `✓ built in 1.07s`).