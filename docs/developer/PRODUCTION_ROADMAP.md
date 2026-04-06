# KazUTB Smart Library — Production Roadmap

## Purpose

This roadmap defines the path from the current transition-stage platform to a production-usable KazUTB Smart Library system.

It is designed to be:
- execution-oriented,
- agent-friendly,
- stage-aware,
- aligned with repository truth,
- compatible with CRM/LDAP integration realities,
- safe against architecture drift.

This roadmap assumes the following target model:

- **Library system** remains the source of truth for library domain logic and data.
- **CRM** acts as:
  - authentication provider via API,
  - LDAP / Active Directory integration point,
  - external operational client that can build staff/admin/librarian interfaces on top of library APIs.
- **Reader-facing UX** remains in the library system.
- **Library staff/admin UI** may remain partially in the library system, but must be treated as optional/secondary if CRM-side operational panels become primary.
- **CRM must not connect directly to the library database.**
- **Library APIs are the strategic integration boundary.**

---

# 0. Current stage summary

Current project stage should be understood as:

- whole platform: **advanced prototype transitioning to operational platform**
- library backend core: **early operational core**
- CRM reservation integration slice: **pilot-ready**

Interpretation rule:
- do not describe the whole platform as production-ready,
- do not treat the repo as an empty scaffold,
- do not reduce the project to just a public frontend.

---

# 1. Final target state

The final production target is a unified digital library platform that:

- provides a real public catalog and book detail experience,
- supports reader account flows,
- supports real library domain logic,
- exposes strong APIs for CRM-side operational panels,
- enforces its own library-side authorization,
- supports data stewardship and correction workflows,
- supports reporting and analytics,
- supports digital materials in controlled mode,
- accounts for the real physical and organizational structure of the fund,
- respects external licensed resources and access contracts,
- is deployable, monitorable, governable, and operationally usable.

---

# 2. Non-negotiable architectural principles

1. Library domain logic remains in the library system.
2. Library data truth remains in the library system.
3. CRM is an external auth/integration/operational client, not the owner of library domain truth.
4. Login UX remains in the library system.
5. CRM must not connect directly to the library database.
6. Reader UX remains in the library system.
7. Staff/admin/librarian workflows may exist in CRM UI, but library APIs must enforce safety and authority.
8. The system must not collapse into “just a pretty frontend”.
9. Data quality, auditability, and reporting compatibility are first-class concerns.
10. Obsidian is a knowledge workspace, not execution truth.

---

# 3. Phase structure

The roadmap is organized into 6 phases:

- **Phase A — Architecture and integration freeze**
- **Phase B — Authentication, identity, and authorization**
- **Phase C — Reader-ready platform**
- **Phase D — CRM operational API platform**
- **Phase E — Domain/data/operations maturity**
- **Phase F — Production readiness and rollout**

Each phase includes:
- goals,
- deliverables,
- exit criteria,
- key risks.

---

# Phase A — Architecture and integration freeze ✅ COMPLETE

## Goal
Remove strategic ambiguity and freeze the target operating model before deeper implementation.

## Status
**Complete.** All deliverables created and frozen on 2026-04-06.

## Why this phase matters
The project currently contains tension between:
- library-first operational UI,
- CRM-first staff UI,
- hybrid operational ownership.

Without a frozen model, implementation can drift.

## Work items

### A1. Freeze target operating model ✅
Defined in `docs/developer/TARGET_OPERATING_MODEL.md`:
- Reader UX, public catalog, account — mandatory library-side
- Staff/admin UI — preferred library-side, CRM optional enhancement
- Library domain logic and data — mandatory library-side
- CRM role — bounded auth provider and API consumer

### A2. Freeze CRM/library boundary ✅
Defined in `docs/developer/RBAC_AND_CRM_BOUNDARY.md`:
- Auth boundary: CRM authenticates, library enforces authorization
- Data boundary: library PostgreSQL is canonical, CRM never connects directly
- API boundary: integration at `/api/integration/v1/*` with header contract
- UI boundary: library owns reader UX; CRM may build staff panels via APIs

### A3. Freeze identity/RBAC authority split ✅
Defined in `docs/developer/RBAC_AND_CRM_BOUNDARY.md` (sections 2-3):
- CRM as identity source (LDAP/AD)
- Library as authorization enforcer
- Role mapping: CRM raw role → library normalizes to reader/librarian/admin
- Two auth models: session-based (web/staff) + bearer-based (CRM integration)

### A4. Freeze API publication policy ✅
Defined in `docs/developer/API_PUBLICATION_POLICY.md`:
- 4-tier classification: public, reader-facing, internal staff, CRM integration
- Full route inventory by tier
- Governance rules: no silent removal, CRM scope frozen, versioning strategy
- 6-point publication criteria for future CRM-facing APIs

## Deliverables
- ✅ `docs/developer/TARGET_OPERATING_MODEL.md`
- ✅ `docs/developer/RBAC_AND_CRM_BOUNDARY.md`
- ✅ `docs/developer/API_PUBLICATION_POLICY.md`

## Exit criteria
- ✅ Target operating model is explicit.
- ✅ CRM/library boundary is explicit.
- ✅ Staff UI strategy is no longer ambiguous.
- ✅ Future agents can act without reopening the same architecture question.

## Risks (mitigated)
- Strategic drift → frozen model prevents
- Repeated rework → explicit boundaries prevent
- Misaligned staff-vs-reader implementation → classification matrix prevents

---

# Phase B — Authentication, identity, and authorization (IN PROGRESS)

## Goal
Make login, identity resolution, and permission enforcement production-credible.

## Status
**B1–B5 partially complete** (Phase B hardening pass, 2026-04-06).
B6 (HTTPS) blocked by CRM infrastructure.

## Work items

### B1. Implement CRM auth integration robustly ✅
CRM auth adapter implemented in AuthController:
- `POST /api/login` — sends credentials to CRM, receives token + user
- `GET /api/me` — reads session, returns normalized user
- `POST /api/logout` — invalidates session, notifies CRM

### B2. Implement secure session handling ⚠️ Partial
- ✅ Token lifecycle: stored in session, used for CRM logout
- ✅ Session lifecycle: regeneration on login, invalidation on logout
- ✅ Invalid token handling: empty token returns 502
- ✅ CRM unavailable: returns 503 with user-friendly message
- ⚠️ Session encryption: not enabled by default (`SESSION_ENCRYPT=false`)
- ⚠️ Token refresh: no refresh mechanism (CRM token lifecycle undefined)

### B3. Implement role mapping ✅
- Normalized to reader/librarian/admin
- Unknown roles fall back to reader
- Student/teacher not yet differentiated (both map to reader)

### B4. Enforce authorization library-side ✅
- `EnsureInternalCirculationStaff` checks session role for staff routes
- `EnsureIntegrationBoundary` validates bearer + headers for CRM integration
- Integration token allowlist available via `INTEGRATION_ALLOWED_TOKENS`

### B5. Harden auth-related observability ✅
- ✅ Successful logins logged (info)
- ✅ Failed logins logged (warning) with IP, login identifier, CRM status
- ✅ CRM unavailable logged (error) with IP, login identifier, error message
- ✅ Invalid integration tokens logged (warning)
- ✅ Login rate limiting (5/min per login+IP, configurable)
- ✅ Info disclosure prevented (no CRM response or exception leak)
- ✅ CRM URL removed from login page HTML

### B6. Prepare HTTPS migration path ❌ Blocked
Current CRM integration over HTTP is a known risk.
Requires CRM infrastructure team to enable TLS.

## Deliverables
- ✅ Auth hardening in AuthController (info leak, failure logging)
- ✅ Login rate limiting in routes + AppServiceProvider
- ✅ Integration token allowlist in EnsureIntegrationBoundary
- ✅ 10 focused auth hardening tests (AuthHardeningTest)
- ✅ `docs/developer/AUTH_INTEGRATION_AND_SESSION_MODEL.md`

## Exit criteria
- ✅ Login/logout/profile flow works reliably.
- ✅ Failure cases are handled honestly.
- ✅ Role mapping is explicit.
- ✅ Library-side authz enforcement exists for protected actions.

## Remaining gaps
- Session encryption not enabled by default
- HTTPS for CRM communication (blocked by infrastructure)
- Cryptographic token verification (blocked by CRM token format)
- Token refresh mechanism (CRM lifecycle undefined)
- Student/teacher role differentiation (not yet needed)

## Risks (partially mitigated)
- CRM unavailability → handled with 503 + error logging
- insecure token/session handling → partially mitigated (rate limit, allowlist)
- accidental trust in CRM UI → library-side authz enforced

---

# Phase C — Reader-ready platform

## Goal
Make the public and reader-facing surface coherent, canonical, and usable.

## Work items

### C1. Finish canonical public catalog flow
- search/list
- filters
- sorting
- book detail
- canonical routes only
- remove or freeze transitional public surfaces

### C2. Finish reader account flows
- account summary
- reservation visibility
- loan visibility
- renewal flow
- error/loading/empty states

### C3. Finish login-to-reader journey
- login
- account entry
- protected flow behavior
- guest/reader boundaries

### C4. Finish public UX polish
- responsive behavior
- accessibility basics
- clean navigation
- consistent UI language
- quality empty states and form behavior

### C5. Finish public runtime confidence
- smoke checks
- grouped tests
- critical-path verification for public/reader paths

## Deliverables
- canonical public routes/views/APIs
- cleaned reader flow
- targeted public feature tests
- `docs/developer/PUBLIC_READER_SURFACE_MAP.md`
- `docs/developer/RUNTIME_VERIFICATION_MATRIX.md`

## Exit criteria
- Public catalog and book detail are canonical and stable.
- Reader account flow is coherent.
- Public critical paths are verifiable and not split across transitional routes.
- Public UI is no longer prototype-fragile.

## Risks
- lingering transitional routes
- weak reader auth/runtime behavior
- fragile public UX and regressions

---

# Phase D — CRM operational API platform

## Goal
Build the API platform that allows CRM-side operational panels to work safely and fully without direct DB access.

## Why this phase matters
If CRM is going to host staff/admin/librarian interfaces, then library APIs must cover operational workflows properly.

## Work items

### D1. Catalog/document management API
Support:
- search/manage records
- create/update documents
- metadata correction
- category/author/publisher relations
- quality flagging where needed

### D2. Copy/fund/location API
Support:
- create/update copies
- copy status
- branch/campus/service-point assignment
- ownership/fund belonging
- movement/history

### D3. Reservation API maturity
Support:
- list/detail
- approve/reject
- possibly create/cancel only if policy reopens scope

### D4. Circulation API maturity
Support:
- checkout
- return
- renew
- history
- current status

### D5. Reader/contacts API
Support:
- reader lookup
- contacts
- normalization workflows
- status visibility

### D6. Stewardship/review API
Support:
- quality dashboards
- review tasks
- enrichment
- bulk validation
- correction workflows

### D7. Reporting/analytics API
Support:
- operational stats
- reservation/circulation reports
- collection usage metrics
- staff-facing metrics needed by CRM panels

### D8. API governance and publication hardening
Add:
- clear namespace policy
- versioning rules
- contract docs
- authz rules
- audit behavior
- rate limits where needed
- deprecation policy

## Deliverables
- CRM-facing API surface map
- expanded OpenAPI contracts
- policy-checked endpoints
- API tests
- `docs/developer/CRM_OPERATIONAL_API_ROADMAP.md`
- `docs/developer/INTERNAL_VS_CRM_API_MATRIX.md`

## Exit criteria
- CRM can implement staff/admin/librarian panels through library APIs.
- Direct DB access is unnecessary.
- APIs are governed, testable, and permission-aware.

## Risks
- exposing too much too early
- contract sprawl
- weak authz on operational mutations

---

# Phase E — Domain, data, and operations maturity

## Goal
Finish the real library-system substance beyond catalog/demo-level capability.

## Work items

### E1. Copy/fund/ownership model maturity
Support the real library structure:
- university vs college
- economic vs technological
- branch/library point
- physical location
- fund belonging
- reporting contour

### E2. Reservation lifecycle maturity
Complete:
- statuses
- limits
- expiration
- librarian actions
- user visibility
- business rule enforcement

### E3. Circulation lifecycle maturity
Complete:
- checkout
- return
- renewals
- status transitions
- action history
- overdue/edge behavior if needed

### E4. Data stewardship operationalization
Complete:
- issue queues
- provenance
- duplicate handling
- reconciliation flows
- bulk remediation
- metrics and audit trail

### E5. Digital materials model
Complete:
- covers
- files
- controlled viewer
- restricted access
- usage rules
- access logging

### E6. External licensed resources
Support:
- contract periods
- user eligibility
- access rules
- differentiation from internal fund
- resource visibility

### E7. Reporting and analytics maturity
Support:
- collection reports
- process dynamics
- branch/campus reports
- fund split reporting
- acquisition reporting
- data quality reporting

## Deliverables
- stronger domain services
- stewardship tooling
- digital materials architecture
- external resources model
- reporting APIs/reports
- `docs/developer/DOMAIN_MATURITY_MAP.md`
- `docs/developer/DIGITAL_MATERIALS_ARCHITECTURE.md`
- `docs/developer/EXTERNAL_RESOURCES_AND_LICENSES.md`

## Exit criteria
- The system is operationally meaningful beyond public catalog.
- Data quality work is supported by real tools.
- Digital materials and external resources are represented correctly.
- Reporting reality is preserved and improved.

## Risks
- library logic staying too shallow
- data quality debt accumulating
- reporting mismatch
- digital-content handling becoming legally unsafe

---

# Phase F — Production readiness and rollout

## Goal
Move from transition-stage platform to deployable, governable, supportable production candidate.

## Work items

### F1. Runtime verification hardening
- critical-path matrix
- grouped verification commands
- CI verification alignment
- smoke checks
- environment-aware test strategy

### F2. Security hardening
- HTTPS path for CRM integration
- secret management
- session/token hardening
- permission hardening
- audit trail review
- abuse/rate limiting review

### F3. Deployment readiness
- production config
- queue/worker setup
- storage strategy
- migrations discipline
- backup strategy
- logging/monitoring

### F4. Staging/UAT/pilot
- staging environment
- operator walkthroughs
- acceptance checklists
- pilot readiness
- rollback plan

### F5. Production rollout
- controlled rollout
- post-launch monitoring
- incident response
- first-phase support process

## Deliverables
- deployment checklist
- security checklist
- staging/pilot checklist
- monitoring/logging setup
- `docs/developer/PRODUCTION_READINESS_CHECKLIST.md`

## Exit criteria
- A staging/pilot deployment is viable.
- Core paths are verified.
- Security posture is acceptable for the deployment stage.
- Rollout can happen with monitoring and rollback.

## Risks
- “feature-complete” but not operable
- weak runtime visibility
- insecure transport/auth handling
- rollout without incident readiness

---

# 4. Priority order across phases

## Immediate priority order
1. Phase A — architecture and boundary freeze
2. Phase B — auth/identity/authz
3. Phase C — reader-ready platform + runtime confidence
4. Phase D — CRM operational API platform
5. Phase E — domain/data/operations maturity
6. Phase F — production readiness and rollout

---

# 5. Immediate next 10 steps

These are the best near-term execution steps.

## Step 1
Freeze the target operating model and CRM/library boundary.

## Step 2
Write explicit RBAC and identity-source policy.

## Step 3
Implement/finish CRM auth integration flow:
- login
- me
- logout
- session handling
- failure behavior

## Step 4
Finish WS4 Runtime E2E Verification Path.

## Step 5
Finish remaining public/reader convergence debt.

## Step 6
Design the full CRM operational API surface by domain.

## Step 7
Implement first high-value CRM operational APIs beyond reservations.

## Step 8
Strengthen library-side authorization enforcement.

## Step 9
Operationalize data stewardship workflows further.

## Step 10
Prepare staging-grade runtime/deployment/security baseline.

---

# 6. Prompt-ready task template

Use this format for future agent tasks.

## Template

### Context files to read first
- `AGENT_START_HERE.md`
- `.github/copilot-instructions.md`
- relevant `project-context/*`
- relevant docs under `docs/developer/`
- relevant runtime files
- relevant tests

### Task format
- what to build/change
- what is in scope
- what is out of scope
- what canonical behavior to preserve
- what must not be broken
- what deliverables to create/update
- what verification must be performed
- what honesty constraints to observe

---

# 7. Prompt-ready task examples

## Example 1 — operating model freeze
Freeze the project target operating model:
- library as domain/data truth
- CRM as auth provider and operational client
- reader UX remains in library
- staff UI CRM-first, library-secondary/optional
Create/update canonical developer docs accordingly.
Do not change runtime code in this step.

## Example 2 — CRM auth integration
Implement/finalize CRM auth integration using:
- `POST /api/login`
- `GET /api/me`
- `POST /api/logout`
Keep login UX inside the library.
Add/adjust session handling, role mapping, and failure behavior.
Do not expand unrelated product scope.

## Example 3 — runtime verification
Audit and strengthen the critical runtime verification path for:
- catalog
- book detail
- account identity
- reservations
- circulation
Add only narrow, useful verification helpers and docs.

## Example 4 — CRM operational API design
Design the next CRM-facing operational API slice for:
- catalog/document management
- copy/fund/location operations
- stewardship workflows
Create/update contracts and implementation plan without broad speculative coding.

---

# 8. Exit condition for “production candidate”

The project can be considered a production candidate only when all of the following are true:

1. target architecture is frozen and coherent,
2. auth and role behavior are reliable,
3. public/reader flows are canonical and stable,
4. CRM can consume the required operational APIs safely,
5. library-side authz and audit rules are enforced,
6. data stewardship is operationally usable,
7. digital materials and external resources are modeled safely,
8. reporting integrity is preserved,
9. runtime verification is credible,
10. deployment/security/monitoring readiness exists.

---

# 9. Final note

This roadmap is intentionally stage-aware.

It does **not** assume:
- the platform is already production-ready,
- CRM should take over library domain truth,
- the library should collapse into just a frontend,
- or that UI polish is enough.

The path to production requires:
- architecture clarity,
- auth clarity,
- API maturity,
- domain maturity,
- data stewardship maturity,
- runtime confidence,
- deployment discipline.

This roadmap should be used as the canonical execution map for future step-by-step agent tasks.