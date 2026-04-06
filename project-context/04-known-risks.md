# 04 - Known Risks

Canonical product reference: `project-context/98-product-master-context.md`.

## Top Platform Risks

1. Library-side operational product gap
- Admin/librarian workspace UI is thin — backend APIs exist but internal views need depth.
- Risk: platform remains demo-oriented instead of library-operational.

2. Data stewardship gap
- Review/triage APIs exist, but correction/provenance workflow is not fully operationalized in UI.
- Risk: post-migration quality debt accumulates without efficient correction tools.

3. UDK/subject classification gap
- No classification data in database. Thematic discovery page uses keyword search as bridge.
- Risk: teacher/syllabus resource selection remains text-search dependent until UDK data is populated.

4. Digital materials gap
- No controlled viewer, no file access rules, no license enforcement implemented yet.
- Risk: protected materials cannot be served to authorized users.

5. External dependency risk
- Auth depends on external CRM API availability (10.0.1.47).
- Risk: user-facing auth degradation if CRM is unreachable.

6. Runtime confidence gap
- Feature-level tests exist (326), but critical runtime end-to-end confidence is still uneven.
- Risk: regressions across dependency boundaries.

## Resolved Risks (from previous iterations)
- ~~Path divergence~~ — public catalog converged to canonical DB-backed routes, legacy deleted.
- ~~Auth navbar inconsistency~~ — all pages now use server-side session, JS-based auth removed.
- ~~Frontend demo quality~~ — 12 routes with shared design system, ready for leadership review.

## Integration Scope Risk
- Expanding CRM API before platform operational depth can reintroduce strategic drift.

## Communication Risk
- Overstating platform readiness based on frontend demo quality alone can cause wrong prioritization decisions.
- The backend needs operational depth before the platform is truly production-grade.
