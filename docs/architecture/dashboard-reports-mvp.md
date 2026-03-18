# Dashboard & Reports MVP — Operational Analytics

## Scope

This document defines the Dashboard & Reports MVP for KazUTB Smart Library.

This is MVP operational analytics — not a full BI system.

The MVP covers:

- dashboard summary (KPI cards)
- popular books ranking
- recent activity summary (reservations, loans, returns)
- yearly and monthly reports
- branch-level summary for global scope
- branch-scoped access for librarians

The MVP does not include:

- XLSX/PDF export
- complex charting libraries
- accounting/acquisitions integration
- advanced trend analysis
- scheduled/emailed reports

## Analytics Endpoints

### GET /api/v1/analytics/dashboard

Returns:

| Field              | Type   | Description                  |
| ------------------ | ------ | ---------------------------- |
| totalBooks         | number | Active books in scope        |
| totalCopies        | number | Total book copies in scope   |
| activeReservations | number | PENDING + READY reservations |
| activeLoans        | number | ACTIVE loans                 |
| overdueLoans       | number | ACTIVE loans past due date   |
| totalUsers         | number | Active users                 |
| branches           | array  | Branches visible to actor    |
| scope              | string | "global" or "branch"         |

### GET /api/v1/analytics/popular-books

Returns top books ranked by a combined score.

**Ranking logic:** `score = loanCount * 2 + reservationCount`

Loans are weighted higher because they represent actual physical usage. Reservations represent intent.

Query parameters:

- `?limit=10` — number of books to return (default: 10)

### GET /api/v1/analytics/activity

Returns activity counts in time buckets:

| Metric       | Buckets                      |
| ------------ | ---------------------------- |
| reservations | today, last7days, last30days |
| loans        | today, last7days, last30days |
| returns      | today, last7days, last30days |

### GET /api/v1/reports/overview

Returns structured reporting summary.

Query parameters:

- `?year=2026` — target year (default: current year)

Returns:

| Field         | Type   | Description                                  |
| ------------- | ------ | -------------------------------------------- |
| year          | number | Selected year                                |
| scope         | string | "global" or "branch"                         |
| yearly        | object | loans, returns, reservations, currentOverdue |
| monthly       | array  | Per-month breakdown (up to current month)    |
| branchSummary | array  | Per-branch totals (global scope only)        |

## Access Control

| Role      | Dashboard        | Popular Books    | Activity         | Reports Overview |
| --------- | ---------------- | ---------------- | ---------------- | ---------------- |
| GUEST     | No               | No               | No               | No               |
| STUDENT   | No               | No               | No               | No               |
| TEACHER   | No               | No               | No               | No               |
| LIBRARIAN | Yes (own branch) | Yes (own branch) | Yes (own branch) | Yes (own branch) |
| ANALYST   | Yes (global)     | Yes (global)     | Yes (global)     | Yes (global)     |
| ADMIN     | Yes (global)     | Yes (global)     | Yes (global)     | Yes (global)     |

Branch scoping:

- Librarians: all queries filtered to their assigned branch via `libraryBranchId`
- Admin/Analyst: no branch filter applied, global view
- Students/Teachers/Guests: HTTP 403 Forbidden

## Frontend Pages

| Route      | Component     | Access                    |
| ---------- | ------------- | ------------------------- |
| /analytics | AnalyticsPage | LIBRARIAN, ANALYST, ADMIN |
| /reports   | ReportsPage   | LIBRARIAN, ANALYST, ADMIN |

### AnalyticsPage (Dashboard)

- 6 summary KPI cards (books, copies, reservations, loans, overdue, users)
- Activity table showing today / 7-day / 30-day counts
- Popular books table with rank, title, authors, loan count, reservation count, and score
- Scope badge (global vs branch)
- Overdue card highlighted in red when > 0

### ReportsPage

- Year selector dropdown (current year and 4 prior years)
- Yearly summary cards (loans, returns, reservations, overdue)
- Monthly breakdown table (per-month rows with loans, returns, reservations)
- Branch summary table (global scope only — per-branch totals)

## Visual Style

- Institutional blue/white palette
- Restrained formal typography
- Clean card-based layout
- Red highlighting only for overdue/alert states
- Fully i18n-ready (kk, ru, en — 41 new translation keys)

## Technical Implementation

- Backend: Prisma `count()` and `findMany()` queries — no raw SQL
- Popular books: application-level scoring with Prisma aggregation (`_count`)
- Monthly breakdown: sequential month queries up to current month
- Frontend: React Query hooks with staleTime caching (30s dashboard, 60s reports)
- No external charting libraries — clean HTML tables and summary cards

## Future Enhancements (Out of MVP Scope)

- XLSX/PDF export for reports
- Chart.js or Recharts integration for visual trends
- Scheduled report generation (cron)
- Email delivery of periodic reports
- Comparison reports (year-over-year, branch-over-branch)
- Acquisitions and accounting integration
- Custom date range selection for activity
- User-level activity analytics
- Real-time WebSocket dashboard updates
