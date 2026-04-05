# KazUTB Smart Library — Phased Development Roadmap

**Generated**: 2026-04-03
**Based on**: Full codebase audit of real implementation state
**Stage**: Advanced prototype transitioning to operational platform (~30–35% overall product completeness)

---

## 1. Executive Summary

The KazUTB Smart Library has a **strong backend foundation** but significant gaps between what is coded and what constitutes an operational library platform.

**What is real and strong today:**
- 28 service classes, 15 controllers — all fully implemented
- Internal stewardship: copy/document/reader review queues, triage, flagging, resolve workflows
- Circulation: checkout/return with full audit event trail
- Copy management: CRUD + retirement with reason codes
- CRM integration: reservation read/mutate (approve/reject) + document management API
- Integration boundary middleware with header discipline, idempotency, correlation tracking
- Auth: CRM-backed login (LDAP/AD proxy) with session management
- Docker: production-ready containerization with health checks and migrations
- Tests: 34 feature tests covering all major backend workflows
- Diagnostics: health summary, bridge diagnostics for users/copies/documents

**What is not yet operational:**
- **No reservation creation** from library side (CRM owns reservation table — critical architectural gap)
- **No librarian workspace UI** beyond 2 internal pages (dashboard + review issues)
- **No admin panel** at all
- **No circulation UI** — checkout/return backend exists but no staff-facing interface
- **No copy management UI** — CRUD backend exists but no staff-facing interface
- **No reporting/analytics** layer
- **No digital materials** with controlled access (reader.blade.php is a demo mockup)
- **No external resources** (licensed database integration)
- **Catalog frontend** exists but has dual-path divergence (demo in-memory + DB-backed)
- **Auth** works but lacks token refresh, expiry handling, fallback, and systematic role enforcement

**Honest maturity breakdown:**

| Area | Maturity | Notes |
|------|----------|-------|
| Backend/domain services | 50–55% | Foundation strong; full CRUD + workflows exist but many contours missing |
| Database/data model | 40–50% | PostgreSQL migrated; quality/completeness work ahead |
| Frontend/UI | 20–25% | Blade templates + inline JS; no component library; 2 real internal pages |
| Auth/integration | 30–35% | Boundary defined; implementation incomplete (no refresh, no fallback) |
| Admin/librarian capability | 15–20% | Backend pieces exist; UI zero for most; no admin panel |
| Reporting/analytics | 5–10% | Health summary exists; no product-level reporting |
| Production readiness | 15–20% | Docker exists; auth/transport/runtime hardening far from done |

---

## 2. Phased Roadmap

### PHASE 1 — Immediate Next Steps (this week)
*Goal: Make already-implemented backend visible and operational through UI.*

#### 1A. Internal Stewardship UI Wiring
- **What**: Wire existing backend review/triage APIs to a real librarian-facing UI page
- **Why in this phase**: Backend is the most mature internal contour (queues, summaries, flag, resolve, triage all implemented). The existing `internal-review.blade.php` only shows quality issues list — it does not expose copy/document/reader queues, triage summary, or resolve/flag actions.
- **Type**: Frontend-first (backend ready)
- **Dependency**: None — backend APIs exist
- **Visible outcome**: Librarians can see review queues, triage summaries, and take action (resolve/flag) from the browser

#### 1B. Public Catalog Path Convergence
- **What**: Eliminate dual catalog path (demo in-memory `index()` vs DB-backed `dbIndex()`). Make `dbIndex` canonical. Remove or freeze the proxy path (`catalog-external`).
- **Why in this phase**: Path divergence creates test confusion and false confidence (risk #1 from project-context). Convergence is P0/Week 1 priority per active roadmap.
- **Type**: Backend-first (route cleanup + frontend re-point)
- **Dependency**: None
- **Visible outcome**: Single canonical catalog path; no confusion about which data source is real

#### 1C. Runtime E2E Verification Path
- **What**: Establish minimum runtime verification for critical paths: catalog search, book detail, account identity, reservation list, internal circulation checkout/return
- **Why in this phase**: P0/Week 1 priority per active roadmap (WS4). Currently tests exist but no systematic runtime confidence.
- **Type**: Backend/testing
- **Dependency**: 1B (canonical catalog path needed first)
- **Visible outcome**: Critical path matrix with pass/fail status; regression safety net

---

### PHASE 2 — Near-Term Steps (next 1–2 weeks)
*Goal: Build the minimum librarian operational workspace.*

#### 2A. Internal Circulation UI
- **What**: Build staff-facing UI for checkout/return workflow
- **Why in this phase**: Backend is fully implemented (InternalCirculationController with checkout, return, loan queries). This is core library operational value that has no UI surface today.
- **Type**: Frontend-first (backend ready)
- **Dependency**: Phase 1A (shared internal layout/navigation)
- **Visible outcome**: Librarians can issue and return books through the system

#### 2B. Internal Copy Management UI
- **What**: Build staff-facing UI for viewing, creating, patching, and retiring copies
- **Why in this phase**: Backend fully implemented (InternalCopyReadController, InternalCopyWriteController, retire service). No UI today.
- **Type**: Frontend-first (backend ready)
- **Dependency**: Phase 1A (shared internal layout)
- **Visible outcome**: Librarians can manage physical copy inventory through the system

#### 2C. Internal Navigation Shell
- **What**: Create a shared internal staff navigation layout connecting dashboard, review, circulation, copy management
- **Why in this phase**: Currently each internal page is isolated. A shared navigation shell makes the internal workspace feel like a real product.
- **Type**: Frontend
- **Dependency**: Phase 1A
- **Visible outcome**: Unified librarian workspace with sidebar/navbar

#### 2D. CRM Auth Session Hardening
- **What**: Implement token expiry detection, stale session handling, re-login prompt, invalid token graceful recovery
- **Why in this phase**: Current auth works for development but will break silently in real use (no refresh token, no expiry check, no fallback). This blocks real user testing.
- **Type**: Backend-first
- **Dependency**: None
- **Visible outcome**: Auth sessions that don't silently fail; users prompted to re-login when token expires

---

### PHASE 3 — Medium-Term Steps (weeks 3–5)
*Goal: Close critical domain gaps and build reader-facing confidence.*

#### 3A. Reservation Architecture Decision & Implementation
- **What**: Decide and implement reservation creation path:
  - Option A: Library creates reservations in its own table (domain ownership)
  - Option B: Library POSTs to CRM to create reservations (CRM-delegated)
  - Option C: Library writes to existing CRM-owned reservation table via integration
- **Why in this phase**: This is the single biggest architectural gap. Without reservation creation, reader-facing flows are incomplete. But rushing this without auth hardening (2D) and catalog convergence (1B) would be premature.
- **Type**: Architecture decision → backend implementation
- **Dependency**: Phase 2D (auth must be stable), Phase 1B (catalog canonical)
- **Visible outcome**: Readers can reserve books through the library system

#### 3B. Account/Personal Cabinet Completion
- **What**: Wire account.blade.php to real data: active loans, reservation history, reader profile, notification statuses
- **Why in this phase**: AccountSummaryReadService exists but the UI is partially wired. With circulation UI (2A) and auth hardening (2D) done, the personal cabinet becomes meaningful.
- **Type**: Frontend + backend enhancement
- **Dependency**: Phase 2A, 2D, 3A
- **Visible outcome**: Readers see their real library relationship (loans, reservations, status)

#### 3C. Reporting Foundation
- **What**: Build first reporting primitives: popular books, active loans count, collection usage, overdue books, reservation conversion rates
- **Why in this phase**: Reporting is a core librarian need. With circulation and copy management online, the data to report on exists.
- **Type**: Backend-first → frontend
- **Dependency**: Phase 2A, 2B
- **Visible outcome**: Basic operational reports for librarians and administrators

#### 3D. Data Stewardship Loop Completion
- **What**: Complete the stewardship correction workflow: currently review/triage/flag/resolve exist, but bulk operations, correction provenance tracking, and AI-assisted correction are not implemented
- **Why in this phase**: Data quality is a declared strategic priority. Backend foundation exists but workflow is not yet end-to-end.
- **Type**: Backend-first
- **Dependency**: Phase 1A (UI surface for stewardship)
- **Visible outcome**: Librarians can not only identify issues but systematically correct them with audit trail

---

### PHASE 4 — Late-Stage Steps (weeks 6+)
*Goal: Production hardening, advanced features, full platform.*

#### 4A. Admin Panel
- **What**: Build library-specific admin panel: user management, role management, system configuration, integration oversight, logs
- **Why in this phase**: Requires stable auth, stable internal navigation, stable domain workflows
- **Type**: Full-stack
- **Dependency**: Phase 2C, 2D, 3C
- **Visible outcome**: Administrators can govern the library system

#### 4B. Digital Materials & Controlled Viewer
- **What**: Implement real digital materials handling: upload, storage, access control by role/authorization, controlled viewer (no unrestricted download), DRM-lite protections
- **Why in this phase**: Complex domain with copyright/license constraints. Requires stable role model and auth.
- **Type**: Full-stack
- **Dependency**: Phase 2D (auth), 4A (admin for access rule management)
- **Visible outcome**: Authorized users can view digital materials in controlled mode

#### 4C. External Resources Integration
- **What**: Model external licensed resources (IPR SMART, etc.) with access periods, user categories, usage conditions
- **Why in this phase**: Operational need but requires stable user model and role system
- **Type**: Backend + frontend
- **Dependency**: Phase 2D, 4A
- **Visible outcome**: Users see available external resources with access conditions

#### 4D. Production Hardening
- **What**: HTTPS/TLS for CRM integration, transport security, session hardening, error monitoring, APM, rate limiting, input sanitization review, CORS policy, log aggregation
- **Why in this phase**: Required before any real deployment but not blocking development progress
- **Type**: Infrastructure/DevOps
- **Dependency**: All core features stabilized
- **Visible outcome**: System safe for real institutional deployment

#### 4E. Advanced Search & AI Layer
- **What**: Full-text search with PostgreSQL tsvector, autocomplete, typo correction, AI-assisted recommendations, search analytics
- **Why in this phase**: Enhancement layer on top of working catalog; not blocking operational value
- **Type**: Backend + frontend
- **Dependency**: Phase 1B (canonical catalog), 3C (reporting data)
- **Visible outcome**: Modern search experience with intelligent suggestions

#### 4F. Fund/Branch/Location Awareness
- **What**: Implement explicit fund belonging: university vs college, economic vs technological, campus/branch/library point modeling in UI and reporting
- **Why in this phase**: Important for library operational truth but requires stable reporting (3C) and admin (4A) foundations
- **Type**: Backend data model + UI + reporting
- **Dependency**: Phase 3C, 4A
- **Visible outcome**: Reports and operations respect real organizational/physical library structure

---

## 3. Frontend Wiring Opportunities Now

These backend APIs are **fully implemented and tested** — UI can be built immediately:

| Backend Slice | API Endpoints | UI Target |
|---------------|---------------|-----------|
| Copy review queue | `GET /internal/review/copies`, `GET /internal/review/copies-summary`, `POST /internal/review/copies/{id}/resolve` | Stewardship dashboard tab |
| Document review queue | `GET /internal/review/documents`, `GET /internal/review/documents-summary`, `POST /internal/review/documents/{id}/flag`, `POST /internal/review/documents/{id}/resolve` | Stewardship dashboard tab |
| Reader review queue | `GET /internal/review/readers`, `GET /internal/review/readers-summary`, `POST /internal/review/readers/{id}/resolve` | Stewardship dashboard tab |
| Triage summary | `GET /internal/review/triage-summary`, `GET /internal/review/triage-reason-codes` | Triage overview panel |
| Circulation checkout/return | `POST /internal/circulation/checkouts`, `POST /internal/circulation/returns`, `GET /internal/circulation/loans/{id}` | Staff circulation page |
| Copy CRUD | `GET /internal/copies/{id}`, `GET /internal/documents/{docId}/copies`, `POST /internal/copies`, `PATCH /internal/copies/{id}`, `POST /internal/copies/{id}/retire` | Staff copy management page |
| Library health | `GET /library/health-summary` | Dashboard (already partially wired) |
| Bridge diagnostics | `GET /bridge/summary`, `GET /bridge/users`, `GET /bridge/copies`, `GET /bridge/books` | Admin diagnostics panel |

### Frontend That Should Wait

| Feature | Why Wait |
|---------|----------|
| Reservation creation UI | Reservation ownership architecture not decided (Phase 3A) |
| Full personal cabinet | Auth hardening needed (2D), reservation creation needed (3A) |
| Admin panel | Requires stable auth, role model, and reporting foundation |
| Digital materials viewer | Access control framework not built; copyright constraints apply |
| Advanced search/filter UI | Catalog path convergence needed first (1B) |

---

## 4. Backend Gaps — Most Dangerous

### Critical (blocks product direction)
1. **Reservation creation** — No path for readers to create reservations. This is the single biggest product gap. Without it, the system cannot function as a real library for students/teachers.
2. **Auth session lifecycle** — No token expiry detection, no refresh, no graceful degradation. Will cause silent failures in real use.

### High (blocks operational completeness)
3. **Catalog path divergence** — Demo in-memory path and DB-backed path coexist. Creates confusion, test ambiguity, and false confidence.
4. **Role enforcement gaps** — Some public routes don't check auth at all. Internal routes check staff role, but no systematic role matrix exists.
5. **Reporting layer** — Zero reporting APIs beyond health summary. Librarians need operational reports to justify the system.

### Medium (blocks maturity)
6. **Data stewardship correction workflow** — Review/flag exists but actual correction + provenance tracking does not.
7. **Fund/branch/location awareness** — Data model has some fields but no systematic fund belonging enforcement.
8. **Digital materials framework** — reader.blade.php is a 3D flip mockup, not a controlled access viewer.

---

## 5. Tomorrow Plan

### ✅ Can start tomorrow
1. **Phase 1A: Wire stewardship backend to internal UI** — Highest confidence. All APIs ready. Extend `internal-review.blade.php` or create new internal pages for copy queue, document queue, reader queue, triage.
2. **Phase 1B: Catalog path convergence** — Clean, safe refactor. Point frontend to `dbIndex`, deprecate `index()`, freeze `proxy()`.
3. **Phase 1C: Runtime E2E smoke test** — Create a shell script or PHPUnit suite that hits the critical path matrix.

### ❌ Should NOT start tomorrow
- Reservation creation (architectural decision needed first)
- Admin panel (too many prerequisites)
- Digital materials (complex domain, no framework yet)
- AI/recommendation features (enhancement layer, not core)
- CRM API expansion (frozen per governance rules)

---

## 6. Recommended Single Highest-Leverage Next Implementation Step

### → Phase 1A: Wire Internal Stewardship Backend to Librarian UI

**Why this is highest leverage:**

1. **Zero backend risk** — All APIs already exist and are tested (copy queue, document queue, reader queue, triage summary, resolve, flag). No new backend code needed.

2. **Maximum visible progress** — Transforms invisible backend work into a real operational surface. Demonstrates that the platform already has value.

3. **Librarian validation** — Gives real librarians a tool they can use today. Stewardship (data quality review/correction) is one of the library's most urgent operational needs given post-migration data quality reality.

4. **Foundation for everything else** — The internal UI patterns, navigation shell, and component conventions established here will be reused by circulation UI (2A), copy management UI (2B), and eventually admin panel (4A).

5. **No dependency on unresolved architecture** — Does not require reservation ownership decision, auth hardening, or catalog convergence.

**Concrete deliverable:**
- Extend or replace `internal-review.blade.php` with a tabbed interface showing:
  - Copy review queue (with resolve action)
  - Document review queue (with flag + resolve actions)
  - Reader review queue (with resolve action)
  - Triage summary overview
- Use existing API endpoints directly
- Reuse existing internal staff session/role check pattern

**Estimated scope:** Focused frontend work, no backend changes needed.
