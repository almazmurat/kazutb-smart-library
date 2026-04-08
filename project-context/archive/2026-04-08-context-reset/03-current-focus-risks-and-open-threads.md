# Current focus, risks, and open threads

## Current focus after the latest verified slice
- keep the new Obsidian memory graph as the durable long-term context layer
- use the repo files in `project-context/` plus `AGENT_START_HERE.md` as deterministic session startup truth
- use the five-stage AI workflow (`/clarify`, `/design`, `/implement`, `/verify`, `/document`) for feature delivery and `/remember` for platform-memory updates
- keep the verified global frontend modernization slice anchored: shared accessible public shell, official-content refresh on `/resources` and `/contacts`, legacy `/for-teachers` redirect to `/resources`, and lightweight `kk / ru / en` public content switching
- keep the verified `/app/catalog` improvement grounded on the canonical `/api/v1/catalog-db` contract with URL-synced `q`, `language`, `year_from`, `year_to`, `available_only`, and `sort`
- treat the active UI initiative as a **full visual reset** driven by Stitch project `4601252383613536784` and `docs/sdlc/current/stitch-mapping.md`, not as another homepage polish pass
- keep the next product depth centered on digital materials, data stewardship, librarian/admin surfaces, UDC-driven discovery, and the cross-page redesign system
- continue product work from the active workstreams in the vault instead of re-explaining architecture every session

## Known operational risks
- Docker app image may need rebuild before code changes appear in the live runtime
- live-edit mode now depends on `app` + `frontend-dev`; if the public site regresses, verify `public/hot`, `bootstrap/cache` writability, and `http://10.0.1.8:5173/@vite/client`
- some tests and read paths depend on live PostgreSQL seed data
- the legacy MARC-side source still changes while development continues, so refresh/import/reconciliation workflows remain necessary
- CRM auth depends on the external auth endpoint being reachable
- Playwright browser verification remains partial until local Chrome/Chromium runtime is installed
- repo-wide Pint style debt still exists outside the current feature slice
- 21st staff tooling requires a valid `API_KEY_21ST`

## Open product threads
- digital materials controlled viewer and attachment foundation
- data stewardship / metadata correction depth
- librarian/admin operational surface foundation
- UDC-driven discovery and teacher resource-selection depth
- reporting and analytics foundation
- fund / branch awareness in the data model
- runtime verification confidence for critical paths

## Session discipline
Every meaningful session should end with an Obsidian writeback:
- what changed
- why it changed
- verification performed
- risks/regressions
- next step
