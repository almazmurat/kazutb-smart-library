# Backend & Frontend Module Map — KazUTB Smart Library

## Backend Modules (NestJS)

Located at: `backend/src/modules/`

Each module follows the NestJS module convention: `module.ts`, `controller.ts`, `service.ts`, and `dto/` directory.

---

### `auth` — Authentication & Session

**Responsibility:** LDAP/AD verification, JWT issuance, token refresh, logout.

Endpoints:

- `POST /api/v1/auth/login` — authenticate via LDAP, return JWT pair
- `POST /api/v1/auth/refresh` — refresh access token
- `POST /api/v1/auth/logout` — invalidate refresh token
- `GET  /api/v1/auth/profile` — return current user profile

Guards: no auth required on login/refresh (marked `@Public()`).

---

### `users` — User Management

**Responsibility:** User records synchronized from LDAP after first login, role assignment, profile management.

Endpoints:

- `GET    /api/v1/users` — list users (Admin only)
- `GET    /api/v1/users/:id` — get user profile
- `PATCH  /api/v1/users/:id/role` — change user role (Admin only)
- `DELETE /api/v1/users/:id` — deactivate user (Admin only)

Note: Users are NOT created manually. They are auto-provisioned on first LDAP login.

---

### `roles` — Role & Permission Configuration

**Responsibility:** Define and document system roles. Roles are an enum, not a DB table — this module provides the mapping of role → permissions for the RBAC guard.

No public endpoints. Internal service used by auth and guards.

---

### `books` — Library Catalog

**Responsibility:** CRUD for bibliographic records (books), linking to authors, categories, publishers.

Endpoints:

- `GET    /api/v1/books` — paginated book list (public, guest access)
- `GET    /api/v1/books/:id` — book detail (public)
- `POST   /api/v1/books` — create book (Librarian, Admin)
- `PATCH  /api/v1/books/:id` — update book (Librarian, Admin)
- `DELETE /api/v1/books/:id` — soft-delete book (Admin)
- `GET    /api/v1/books/:id/copies` — list physical copies
- `POST   /api/v1/books/:id/copies` — add copy (Librarian, Admin)

---

### `authors` — Author Registry

**Responsibility:** Manage author entities linked to books.

Endpoints:

- `GET    /api/v1/authors` — list authors
- `GET    /api/v1/authors/:id` — author detail with book list
- `POST   /api/v1/authors` — create (Librarian, Admin)
- `PATCH  /api/v1/authors/:id` — update (Librarian, Admin)
- `DELETE /api/v1/authors/:id` — soft-delete (Admin)

---

### `categories` — Subject Categories

**Responsibility:** Hierarchical category/subject tree for classification.

Endpoints:

- `GET    /api/v1/categories` — category tree
- `GET    /api/v1/categories/:id` — category + children + books
- `POST   /api/v1/categories` — create (Admin)
- `PATCH  /api/v1/categories/:id` — update (Admin)
- `DELETE /api/v1/categories/:id` — delete (Admin)

---

### `files` — Digital Material Management

**Responsibility:** Upload, store, and serve protected digital files (PDFs, e-books, scans). Enforces copyright access controls at the controller level.

Endpoints:

- `POST   /api/v1/files/upload` — upload file for a book (Librarian, Admin)
- `GET    /api/v1/files/:id/view` — stream file to browser (authorized users only, view-only)
- `DELETE /api/v1/files/:id` — remove file (Admin)

**Security rules enforced:**

- No `Content-Disposition: attachment` header (prevents download)
- Response headers disable caching of file content
- Guests cannot access file endpoints at all

---

### `search` — Search Engine

**Responsibility:** Full-text search, trigram similarity, autocomplete, and AI-ready suggestions layer.

Endpoints:

- `GET /api/v1/search?q=&lang=&year=&category=&page=` — main search
- `GET /api/v1/search/autocomplete?q=` — suggestions (debounced)
- `GET /api/v1/search/similar/:bookId` — similar books by category + keywords

Implementation: PostgreSQL `tsvector` for FTS, `pg_trgm` for fuzzy author/title matching.

---

### `reservations` — Book Reservation

**Responsibility:** Student/teacher reserve available books; librarians fulfill or cancel.

Endpoints:

- `POST   /api/v1/reservations` — reserve a book (Student, Teacher)
- `GET    /api/v1/reservations` — list reservations (Librarian, Admin)
- `GET    /api/v1/reservations/my` — my reservations (authenticated users)
- `PATCH  /api/v1/reservations/:id/cancel` — cancel reservation
- `PATCH  /api/v1/reservations/:id/fulfill` — mark as fulfilled (Librarian)

---

### `circulation` — Loan & Return (Inventory Circulation)

**Responsibility:** Librarian workflow for issuing books to users and recording returns.

Endpoints:

- `POST   /api/v1/circulation/loan` — issue a copy to a user (Librarian)
- `POST   /api/v1/circulation/return` — return a copy (Librarian)
- `GET    /api/v1/circulation/active` — all active loans (Librarian, Admin)
- `GET    /api/v1/circulation/overdue` — overdue loans (Librarian, Admin)
- `GET    /api/v1/circulation/history/:userId` — user loan history

---

### `migration` — Data Migration Pipeline

**Responsibility:** Admin-controlled pipeline for importing data from MARC SQL exports. Manages batch state, logging, and integrity checks.

Endpoints:

- `POST   /api/v1/migration/batches` — create a new migration batch (Admin)
- `GET    /api/v1/migration/batches` — list all batches with status
- `GET    /api/v1/migration/batches/:id` — batch detail + logs
- `POST   /api/v1/migration/batches/:id/run` — execute a batch
- `GET    /api/v1/migration/batches/:id/logs` — paginated migration logs

---

### `reports` — Library & Accounting Reports

**Responsibility:** Generate structured reports required by the university library and accounting department.

Endpoints:

- `GET /api/v1/reports/monthly?year=&month=` — monthly circulation summary
- `GET /api/v1/reports/yearly?year=` — yearly summary
- `GET /api/v1/reports/acquisitions?from=&to=` — new acquisitions by period
- `GET /api/v1/reports/invoices` — acquisition invoices list
- `GET /api/v1/reports/reconciliation?year=&month=` — library/accounting reconciliation

All report endpoints support `Accept: application/json` and `Accept: text/csv`.

---

### `analytics` — Usage Analytics & Dashboards

**Responsibility:** Aggregate statistics for the library analytics dashboard.

Endpoints:

- `GET /api/v1/analytics/dashboard` — summary KPIs
- `GET /api/v1/analytics/popular-books?period=` — most requested books
- `GET /api/v1/analytics/user-activity?period=` — active users
- `GET /api/v1/analytics/circulation-trends?period=` — loan/return trends
- `GET /api/v1/analytics/fund-usage` — catalog usage statistics

---

### `audit` — Audit Log

**Responsibility:** Append-only log of all significant system actions (create/update/delete on books, user role changes, login events, admin actions).

Endpoints:

- `GET /api/v1/audit/logs` — paginated audit log (Admin only)
- `GET /api/v1/audit/logs?entityType=book&entityId=:id` — filtered audit log

No write endpoints. Logs are written internally via `AuditService.log(...)`.

---

### `settings` — System Settings

**Responsibility:** Manage configurable system parameters (e.g., loan duration, reservation expiry, library working hours).

Endpoints:

- `GET    /api/v1/settings` — list all settings (Admin)
- `PATCH  /api/v1/settings/:key` — update a setting value (Admin)

---

## Frontend Feature Modules

Located at: `frontend/src/features/`

Each feature module is self-contained: pages, components, API calls, types, and hooks live inside the feature directory.

Shared multilingual infrastructure is prepared in `frontend/src/shared/i18n/` with locale dictionaries for `kk`, `ru`, and `en`.

---

### `catalog` — Public Book Catalog

**Route:** `/catalog`, `/` (redirect)
**Access:** Public (Guest)
**Components:** CatalogPage, BookGrid, BookCard, FilterPanel, PaginationBar

---

### `auth` — Authentication

**Route:** `/login`
**Access:** Public
**Components:** LoginPage, LoginForm, UniversityLoginNote

---

### `search` — Advanced Search

**Route:** `/search?q=...`
**Access:** Public
**Components:** SearchPage, SearchBar, SearchResults, FilterSidebar, AutocompleteDropdown

---

### `book` — Book Detail

**Route:** `/books/:id`
**Access:** Public for metadata; restricted for digital viewer
**Components:** BookDetailPage, BookMetadata, CopiesTable, ReservationButton, DigitalViewer (authenticated only)

---

### `cabinet` — User Personal Cabinet

**Route:** `/cabinet`
**Access:** Authenticated (Student, Teacher, Librarian, Admin)
**Components:** CabinetPage, MyReservations, MyLoans, ProfileCard

---

### `librarian` — Librarian Workspace

**Route:** `/librarian/*`
**Access:** Librarian, Admin
**Components:** LibrarianDashboard, CirculationDesk, LoanForm, ReturnForm, OverdueList, CopyManagement

---

### `admin` — Administration Panel

**Route:** `/admin/*`
**Access:** Admin
**Components:** AdminDashboard, UserManagement, BookForm, CategoryManager, MigrationPanel, SettingsPanel

---

### `analytics` — Analytics Dashboard

**Route:** `/analytics`
**Access:** Librarian, Analyst, Admin
**Components:** AnalyticsDashboard, KPICards, PopularBooksChart, CirculationTrendChart, UserActivityChart

---

### `reports` — Report Generation

**Route:** `/reports`
**Access:** Librarian, Analyst, Admin
**Components:** ReportsPage, MonthlyReportView, YearlyReportView, AcquisitionReport, InvoiceReport, ReportDownloadButton
