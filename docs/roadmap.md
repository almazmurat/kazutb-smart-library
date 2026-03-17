# Implementation Roadmap — KazUTB Smart Library

## Approach

Development follows an **iterative, module-by-module** strategy with each phase producing a demonstrable, working increment. Each phase builds on the previous one.

The system will be developed in VS Code with AI-assisted coding agents. Each phase below is designed to be a focused agent prompt scope.

---

## Phase 0 — Foundation (CURRENT)

**Goal:** Clean, well-documented project structure. No runtime code yet.

- [x] Repository structure created
- [x] Architecture documentation
- [x] Domain model designed
- [x] Tech stack finalized
- [x] Module map defined
- [x] RBAC model documented
- [x] Migration concept documented
- [x] Phased roadmap created
- [ ] Backend NestJS project bootstrapped (skeleton)
- [ ] Frontend React project bootstrapped (skeleton)
- [ ] Prisma schema draft

**Exit criteria:** Both apps run (`npm run dev`), return health check responses, and connect to a local PostgreSQL database.

---

## Phase 1 — Database & Core Backend Infrastructure

**Goal:** Working PostgreSQL schema, Prisma, and NestJS common layer.

### Tasks

- [ ] Finalize and apply Prisma schema (`prisma migrate dev`)
- [ ] Add `pg_trgm` extension to migration
- [ ] Create DB seed script with sample data (5–10 books, users per role)
- [ ] Implement `PrismaService` (NestJS module)
- [ ] Implement `ConfigService` configuration loading
- [ ] Implement `JwtAuthGuard` and `RolesGuard`
- [ ] Implement `AllExceptionsFilter` (standardized error responses)
- [ ] Implement `LoggingInterceptor` (request/response logging)
- [ ] Implement `AuditService` (internal service for append-only audit log)
- [ ] Health check endpoint: `GET /api/v1/health`

**Exit criteria:** All DB tables created, guards functional, seed script populates test data.

---

## Phase 2 — Authentication

**Goal:** Working login via LDAP/AD with JWT issuance.

### Tasks

- [ ] Implement LDAP strategy (`passport-ldapauth`) using env config
- [ ] Implement dev mock mode (`LDAP_DEV_MOCK=true`) for local testing
- [ ] User auto-provisioning on first successful LDAP login
- [ ] JWT access + refresh token issuance
- [ ] Token refresh endpoint
- [ ] Logout (refresh token invalidation)
- [ ] `GET /api/v1/auth/profile` — current user
- [ ] Frontend: Login page with university login form
- [ ] Frontend: JWT storage (memory + secure cookie approach)
- [ ] Frontend: Axios interceptors for token attachment and refresh
- [ ] Frontend: multilingual foundation (`kk`, `ru`, `en`) with locale switching readiness

**Exit criteria:** Can log in with mocked credentials, receive JWT, call protected endpoints.

---

## Phase 3 — Book Catalog Core (CRUD + Public API)

**Goal:** Librarians can manage books; public can browse and view.

### Tasks

- [ ] Books CRUD (create, list, detail, update, soft-delete)
- [ ] Authors CRUD
- [ ] Categories CRUD (hierarchical)
- [ ] Publishers CRUD
- [ ] BookCopy management (add copies, update status)
- [ ] Frontend: Public catalog page with book grid + pagination
- [ ] Frontend: Book detail page (metadata + availability)
- [ ] Frontend: Librarian book management view (add/edit forms)
- [ ] Input validation on all DTOs
- [ ] Audit log on all write operations

**Exit criteria:** Full working catalog visible in browser; librarians can add/edit books.

---

## Phase 4 — Search

**Goal:** Fast, accurate full-text and fuzzy search.

### Tasks

- [ ] PostgreSQL FTS setup: `tsvector` column with trigger on Book
- [ ] Enable `pg_trgm` extension
- [ ] Search endpoint: query across title, author, keywords, description
- [ ] Filters: year, language, category, availability
- [ ] Autocomplete endpoint (title + author prefix matching)
- [ ] Similar books endpoint (same category + shared keywords)
- [ ] Frontend: Search bar with autocomplete dropdown
- [ ] Frontend: Search results page with filter sidebar
- [ ] Frontend: "Similar books" section on book detail page

**Exit criteria:** Search returns relevant results; autocomplete responds under 100ms.

---

## Phase 5 — Circulation & Reservations

**Goal:** Students can reserve; librarians can issue and return.

### Tasks

- [ ] Reservation creation by students/teachers
- [ ] Reservation status management by librarians
- [ ] Loan issuance (librarian assigns copy to user)
- [ ] Loan return processing
- [ ] Overdue tracking (status update job or query)
- [ ] Frontend: Reserve button on book detail page
- [ ] Frontend: My reservations and loans in user cabinet
- [ ] Frontend: Librarian circulation desk (issue/return workflow)
- [ ] Frontend: Overdue books list for librarians

**Exit criteria:** Full circulation workflow functional end-to-end.

---

## Phase 6 — Digital Materials (Protected File Viewer)

**Goal:** Authorized users can view digital materials inline; no download possible.

### Tasks

- [ ] File upload endpoint (Librarian/Admin only)
- [ ] Secure file storage on disk (path never exposed via API)
- [ ] File streaming endpoint with copyright headers
- [ ] ViewerPage component (PDF.js-based viewer)
- [ ] Disable text selection, right-click, download in viewer
- [ ] Access control: authenticated university users only
- [ ] Frontend: "View Digital" button on book detail (role-based visibility)
- [ ] File integrity hash stored and verified on upload

**Exit criteria:** PDF viewable in browser; download attempt is blocked; guests cannot access.

---

## Phase 7 — User Management & Admin Panel

**Goal:** Admins can manage users, roles, and system configuration.

### Tasks

- [ ] User list with search and role filter (Admin only)
- [ ] Role assignment UI
- [ ] Assign institution scope and library branch ownership to users
- [ ] User deactivation
- [ ] System settings management (loan duration, reservation expiry)
- [ ] Frontend: Admin panel layout
- [ ] Frontend: User management table with inline role editor
- [ ] Frontend: Settings panel

**Exit criteria:** Admin can list all users, change roles, and configure system parameters.

---

## Phase 8 — Migration Pipeline

**Goal:** Working data migration from MARC SQL export to PostgreSQL.

### Tasks

- [ ] Define MARC SQL export format (analyze actual export)
- [ ] Write `scripts/export/` — SQL queries or export instructions for MARC SQL
- [ ] Write `scripts/clean/` — Node.js/Python cleaning pipeline script
- [ ] Write `scripts/transform/` — normalization and mapping script
- [ ] Write `scripts/import/` — batch import script using Prisma
- [ ] Implement MigrationBatch + MigrationLog tracking in DB
- [ ] Migration admin panel page (upload file, run batch, view logs)
- [ ] Checksum verification at each stage
- [ ] Test with sample MARC SQL data
- [ ] Enforce scope and branch boundaries in migration ownership mapping

**Exit criteria:** Full pipeline runs from raw export file to PostgreSQL with logged results.

---

## Phase 9 — Analytics & Reports

**Goal:** Library staff can view KPI dashboards and generate institutional reports.

### Tasks

- [ ] Analytics aggregation queries (popular books, active users, trends)
- [ ] Analytics dashboard API endpoints
- [ ] Monthly report generation (circulation summary)
- [ ] Yearly report generation
- [ ] Acquisition/invoice report
- [ ] Library/accounting reconciliation report
- [ ] CSV export for all reports
- [ ] Frontend: Analytics dashboard with Recharts charts
- [ ] Frontend: Reports page with date range selectors and export buttons

**Exit criteria:** Dashboard shows real data; monthly report downloadable as CSV.

---

## Phase 10 — Polish, Security Hardening & Handoff Preparation

**Goal:** Production-quality final checks before DevOps deployment.

### Tasks

- [ ] API rate limiting (`@nestjs/throttler`)
- [ ] Input sanitization review (XSS, injection)
- [ ] Sensitive data protection (no credential logging)
- [ ] CORS configuration hardened
- [ ] Helmet.js headers
- [ ] Environment config validation on startup
- [ ] Swagger/OpenAPI documentation auto-generated
- [ ] Frontend error boundaries and graceful error handling
- [ ] Loading states and empty states across all views
- [ ] Final README for DevOps deployment instructions
- [ ] Infrastructure notes updated for deployment team

**Exit criteria:** System passes OWASP top-10 review; ready for DevOps deployment.

---

## MVP Scope

Phases 1–6 + Phase 7 (partial) constitute the **MVP**:

- Working catalog with full CRUD
- Authentication via LDAP (with dev mock)
- Search with filters
- Reservation and circulation
- Protected digital materials viewer
- Basic admin/user management

Phases 8–10 complete the **full product**.

---

## What Is Explicitly Out of Scope (Current Phase)

| Item                                | Reason                           |
| ----------------------------------- | -------------------------------- |
| Docker / CI-CD                      | DevOps team handles separately   |
| Mobile app                          | Not requested                    |
| Public API for third parties        | Future enhancement               |
| GraphQL                             | REST is sufficient               |
| Redis caching                       | Can be added when scale requires |
| Real-time notifications (WebSocket) | Nice-to-have, post-MVP           |
| Email notifications                 | Post-MVP                         |
| AI/ML book recommendations          | Phase 4+ enhancement             |
| Self-registration                   | Not allowed by university policy |
