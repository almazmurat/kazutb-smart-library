# 04 - Known Risks

## Top Platform Risks
1. Path divergence risk
- Public/catalog area has had transitional/demo/proxy branches.
- Risk: inconsistent behavior, test confusion, false confidence.

2. Library-side operational product gap
- Admin/librarian workspace and workflows are not fully matured.
- Risk: platform remains integration-heavy instead of library-operational.

3. Data stewardship gap
- Monitoring exists, but correction/provenance workflow is not fully operationalized.
- Risk: post-migration quality debt accumulates.

4. Runtime confidence gap
- Feature-level tests exist, but critical runtime end-to-end confidence is still uneven.
- Risk: regressions across dependency boundaries.

5. External dependency risk
- Auth path depends on external CRM auth availability.
- Risk: user-facing auth reliability degradation.

## Integration Scope Risk
- Expanding CRM API before platform convergence can reintroduce strategic drift.

## Communication Risk
- Overstating stage as "platform early production" can cause wrong prioritization decisions.
