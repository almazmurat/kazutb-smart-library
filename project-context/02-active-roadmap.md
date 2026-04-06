# 02 - Active Roadmap

Canonical product reference: `project-context/98-product-master-context.md`.

## Strategic Priority (Current)
Frontend demo layer is mature. Shift focus to backend operational depth, data quality, and admin capabilities.

## Priority Order

### P0 / Immediate — Backend Operational Depth
1. Library-side operational workflows — librarian/admin UI for circulation, copy management, review
2. Data stewardship operationalization — correction workflows, batch resolution, quality dashboards
3. Reporting foundation — collection usage, circulation statistics, acquisition data

### P1 / Near-Term — Core Platform Hardening
4. Catalog search improvements — full-text quality, filters, suggestions
5. UDK/subject classification data ingestion — populate classification columns, enable thematic filtering
6. Student-facing account features — reading history, favorites, notifications
7. Digital materials handling — controlled viewer, access rules, license enforcement

### P2 / Following — Integration and Expansion
8. CRM-facing API hardening and publication — conservative, explicit contract governance
9. Auth/security hardening — HTTPS enforcement, session management improvements
10. Analytics and operational dashboards for librarians

### P3 / Deferred Advanced
11. AI-assisted enrichment, recommendation layers, smart search
12. Advanced reporting (invoices, reconciliation, yearly reports)

## Explicit Freeze
- Do not expand CRM reservation API beyond current v1 scope.
- No new integration mutations outside existing approve/reject behavior.
- Do not add more public frontend pages unless explicitly requested — demo layer is sufficient.

## Completed Work (for context)
- Public catalog convergence: canonical DB-backed routes, legacy routes removed.
- Auth hardening: rate limiting, info-leak prevention, session-based auth consistency.
- Frontend demo: 12 public routes, shared design system, teacher/discovery UX, premium footer.
- Reader account: summary, loans, reservations, loan renewal.
- Internal circulation: checkout, return, renewal with staff middleware.
- Copy management: CRUD, retirement, review workflow.
- Review/stewardship APIs: triage, metrics, document/copy/reader queues.

## Roadmap Use Rule
- Any new proposal must state: "How does this improve library platform operational depth now?"
- Frontend polish proposals should be deferred unless they fix real usability issues.
