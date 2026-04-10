# Autopilot verification — homepage KazТБУ hero refinement

## Artifact under review
- draft: `docs/sdlc/current/draft.md`
- spec: `docs/sdlc/current/spec.md`
- plan: `docs/sdlc/current/plan.md`

## Commands executed
```bash
cd /home/admlibrary/kazutb-smart-library-main && \
  docker compose run --rm --entrypoint sh -v "$PWD":/app app -lc 'php artisan test --filter="test_homepage_has_hero_search_bar|test_homepage_has_hero_quick_links|test_homepage_has_kaztbu_identity_mark|test_homepage_no_advantages_section"'

cd /home/admlibrary/kazutb-smart-library-main && \
  docker run --rm -v "$PWD":/app -w /app node:22 bash -lc 'npm run build'
```

## Visual check evidence
- Playwright opened `http://localhost/` successfully.
- The updated hero rendered:
  - title: `Библиотека КазТБУ для учёбы, поиска и академической работы.`
  - note: `Библиотека КазТБУ` + supporting description
  - round hero mark size on desktop: `320 × 320`
- Mobile re-check at `390px` width confirmed the hero grid stacked cleanly and the note aligned to center.

## Results
| Check | ID | Status | Evidence |
|---|---|---|---|
| homepage regression tests | `V1` | PASS | `4 passed (12 assertions)` in `Tests\Feature\Api\ConsolidationTest` |
| display verification | `V2` | PASS | Playwright confirmed the new `hero-campus-mark`, the `Библиотека КазТБУ` note, and responsive stacking on mobile |
| frontend production build | `V3` | PASS | `vite v8.0.3` → `✓ built in 4.44s` using the supported `node:22` runtime |

## Requirement traceability
| Requirement | Evidence | Result |
|---|---|---|
| `R1` | `landing-title`, `landing-campus-note`, `Библиотека КазТБУ` copy in `resources/views/welcome.blade.php` | PASS |
| `R2` | `.hero-campus-mark` round institutional visual | PASS |
| `R3` | search bar + quick links preserved and the new copy remains concise | PASS |
| `R4` | Playwright mobile re-check at `390px` width | PASS |
| `R5` | fresh PHPUnit run + Playwright check + production build | PASS |

## Use-case coverage
| Use case | Evidence | Result |
|---|---|---|
| `UC1` | Visitor sees `Библиотека КазТБУ` immediately in the first screen | PASS |
| `UC2` | Search and quick routes still render after the visual update | PASS |
| `UC3` | Mobile layout stacks correctly without visual overload | PASS |

## Notes
- Host `npm run build` on Node 18 failed because Vite 8 requires Node 20.19+ or 22.12+; the supported Node 22 container build passed, so the repo verification remains green.
- No backend or route contracts were changed for this task.

## Ready for /document
- yes