# homepage-hero-spacing-for-kaztbu-note — specification

## Metadata
- status: draft
- source draft: `docs/sdlc/current/draft.md`
- owner: GitHub Copilot
- date: 2026-04-10

## Problem statement
The homepage first screen on `/` already includes the new `Библиотека КазТБУ` identity block, but the current desktop composition allows the large hero heading and the supporting note card to visually touch or crowd each other. This weakens readability and makes the first impression feel less polished than intended.

## Scope
### In scope
- adjust the homepage hero layout in `resources/views/welcome.blade.php`
- ensure the main title, round institutional mark, and supporting note card have clear visual separation
- preserve the current `КазТБУ` identity copy and the search-first homepage behavior
- keep the layout clean on desktop and mobile widths

### Out of scope
- rewriting the broader homepage sections below the hero
- changing routes, backend logic, database structure, or CRM/auth behavior
- replacing the current institutional copy with a new content direction unless minor text shortening is required for fit

## Architecture areas touched
- backend: none expected
- frontend: `resources/views/welcome.blade.php` hero markup and CSS (`.landing-hero-grid`, `.landing-title`, `.landing-campus-panel`, `.landing-campus-note`)
- database: none
- CRM/auth: none
- tests: homepage regression coverage in `tests/Feature/Api/ConsolidationTest.php` can be extended or reused if layout markers change
- docs / Obsidian: `docs/sdlc/current/spec.md`, later `plan.md` and `verify.md` if implementation proceeds

## Requirements
- `R1.` The homepage hero on `/` must maintain a clear visual gap between the main heading and the `Библиотека КазТБУ` note card at common desktop widths.
- `R2.` The supporting note card must not overlap, touch, or appear visually fused with the headline block.
- `R3.` The round institutional mark and current KazТБУ identity message must remain present and readable.
- `R4.` The search bar and quick links below the hero must remain intact and not be pushed into awkward spacing.
- `R5.` The fix must stay responsive for mobile and tablet widths without introducing new crowding.

## User / system flows
- `UC1.` A visitor opens `/` on desktop and immediately sees a calm, separated composition: headline on the left, round institutional mark on the right, and the supporting note clearly below it.
- `UC2.` A visitor can still use the hero search and quick routes without the updated layout feeling cramped.
- `UC3.` A visitor on a smaller screen sees the same content stack cleanly with no touching or overlap.

## Acceptance criteria
- `AC1.` At desktop widths, the headline block and the note card have visible spacing and no edge collision.
- `AC2.` The current strings `Библиотека КазТБУ` and the supporting description remain readable and visually distinct from the title.
- `AC3.` Existing homepage markers such as `hero-search-bar` and `hero-quick-links` still render after the layout adjustment.

## Assumptions
- The issue is primarily a layout/spacing bug, not a request for a new content rewrite.
- A frontend-only adjustment should be sufficient.
- The current institutional naming should continue to use `КазТБУ`.

## Open questions
- None blocking. If needed during `/design`, the implementation can decide whether the cleaner fix is extra spacing, narrower title width, adjusted panel placement, or a combination.

## Ready for /design
- yes
- notes:
  - The task is specific enough to move directly into a small frontend layout plan.
  - No backend or domain clarification is needed.