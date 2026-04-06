# 06 - Current Focus (Execution Now)

Canonical product reference: `project-context/98-product-master-context.md`.

## Active Window
Post-frontend-demo phase. Backend operational depth is the primary focus.

## What Is Complete (do not redo)
- Public catalog convergence: canonical DB-backed routes, legacy deleted.
- Auth hardening: rate limiting, info-leak prevention, server-side session consistency.
- Frontend demo: 12 public routes with shared design system, teacher/discovery UX, premium footer.
- Reader account: summary, loans, reservations, renewal.
- Internal circulation APIs: checkout, return, renewal.
- Copy management APIs: CRUD, retirement, review workflow.
- Review/stewardship APIs: triage, metrics, document/copy/reader queues.
- Test suite: 326 tests (314 pass, 10 pre-existing failures, 2 skipped).

## Primary Focus (Now)
1. **Backend operational depth** — make internal staff views functional and useful:
   - circulation UI improvements,
   - review/stewardship UI with real queue interaction,
   - copy management UI.

2. **Data stewardship operationalization** — expose correction workflows:
   - document/copy/reader review queue interaction in the UI,
   - batch resolution tools,
   - quality dashboards with triage metrics.

3. **Reporting foundation** — basic operational reports:
   - collection usage,
   - circulation statistics,
   - acquisition and fund overview.

## Secondary Focus (Next)
4. Catalog search quality improvements (relevance, suggestions).
5. UDK/subject classification data ingestion.
6. Student account features expansion.
7. Digital materials controlled viewer.

## Not In Scope During Current Focus
- More public frontend pages or polish (demo layer is sufficient).
- New CRM API endpoints.
- AI/recommendation features.
- Major architecture refactors.

## Execution Outcome Expected
- Internal staff views become functionally useful (not just skeletons).
- Data correction workflows are usable by librarians.
- Basic operational reporting exists.
- Backend substance matches frontend presentation quality.
