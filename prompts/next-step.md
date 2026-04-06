Start by reading:
- `AGENT_START_HERE.md`
- `.github/copilot-instructions.md`
- `project-context/00-project-truth.md`
- `project-context/01-current-stage.md`
- `project-context/02-active-roadmap.md`
- `project-context/03-api-contracts.md`
- `project-context/04-known-risks.md`
- `project-context/05-agent-working-rules.md`
- `project-context/06-current-focus.md`
- `project-context/98-product-master-context.md`
- `README.md`

Previous step completed: ✅ End-to-end subject/classification catalog feature (ce27394)
- Backend: SubjectReadService, SubjectController, catalog subject_id filter, book classification
- Frontend: catalog subject dropdown + badges, book classification chips, discover real subjects from API
- Tests: 6 new tests for subjects API, catalog filtering, classification, validation
- All 11 routes 200, all existing tests pass, zero regressions

Task:
Next priority from current-focus and roadmap — choose the most impactful next step.

Candidates (evaluate and pick one):
1. SPA shell wiring — connect React SPA CatalogPage to real /api/v1/catalog-db (currently calls nonexistent /catalog/search)
2. Reservation workflow — expose reservation API for authenticated readers
3. Data quality dashboard — internal admin view for reviewing documents needing cleanup
4. CRM-facing API hardening — tighten integration boundary contracts
5. Mobile/responsive polish pass — systematic mobile UX improvements
6. Project context documentation update — sync docs with implemented features

Pick the single highest-impact item, implement it narrowly and safely.
Do not overbuild. Keep existing flows stable.