# Autopilot plan — homepage KazТБУ hero refinement

## Goal
Implement a focused homepage improvement so the first screen clearly presents the site as the **library of КазТБУ** with a large round institutional visual, while preserving the search-first flow.

## Traceability
| Requirement | Plan steps | Verification |
|---|---|---|
| `R1` | `S1`, `S2` | `T1`, `V1` |
| `R2` | `S2` | `T1`, `V1` |
| `R3` | `S1`, `S2` | `T2`, `V2` |
| `R4` | `S2` | `V1` |
| `R5` | `S3` | `V1`, `V2`, `V3` |

## Steps
- **S1** — Refine the hero copy in `resources/views/welcome.blade.php` so the first screen explicitly says this is the library of КазТБУ.
- **S2** — Add a large round institutional visual block and responsive hero layout/styling without removing the search bar or quick links.
- **S3** — Verify with a targeted homepage regression test, visual browser check, and `npm run build`.

## Tests and checks
- **T1** — Homepage response includes the new hero marker and КазТБУ library copy.
- **T2** — Existing homepage quick links and search marker still render.
- **V1** — Targeted PHPUnit run passes.
- **V2** — Browser view of `/` shows the updated hero cleanly.
- **V3** — Frontend production build succeeds.

## Files expected to change
- `resources/views/welcome.blade.php`
- `tests/Feature/Api/ConsolidationTest.php`
- `docs/sdlc/current/spec.md`
- `docs/sdlc/current/plan.md`
- `docs/sdlc/current/verify.md`

## Notes
- Keep `КазТБУ` as the default institutional naming in Cyrillic-facing copy.
- Avoid introducing an external image dependency; prefer a built-in institutional mark if no official asset exists in the repo.