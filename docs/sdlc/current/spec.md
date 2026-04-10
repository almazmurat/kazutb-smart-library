# Autopilot spec — homepage KazТБУ hero refinement

## Input
`docs/sdlc/current/draft.md`

## Assumption
Because no official logo asset is stored in the repo, the first screen will use a **large circular institutional mark / hero photo-style card** built directly in the homepage markup and styling, while keeping the existing search-first entry flow intact.

## Requirements
- **R1** — The homepage first screen on `/` must clearly show that this is the **library of КазТБУ**.
- **R2** — The first screen must include a **large round visual mark** (logo-style or photo-style block) without adding visual clutter.
- **R3** — The hero must remain calm, modern, and academically styled, preserving search and quick navigation.
- **R4** — The layout must stay responsive on desktop and mobile.
- **R5** — The change must be verified with real evidence: targeted tests, visual check, and frontend build.

## Use cases
- **UC1** — A visitor opening `/` immediately understands that the site is the library of КазТБУ.
- **UC2** — A visitor still sees the search bar and quick routes without the hero feeling overloaded.
- **UC3** — On smaller screens, the new round visual and text stack cleanly and remain readable.

## Out of scope
- Reworking the rest of the homepage sections.
- Introducing a new asset pipeline or external image dependency.
- Changing catalog, auth, or other routes.