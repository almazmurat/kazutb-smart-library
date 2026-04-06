# 03 - API Contracts (Operational Snapshot)

Canonical product reference: `project-context/98-product-master-context.md`.

## Public/Library Surface

### Catalog and Book Detail
- `GET /api/v1/catalog-db` — canonical catalog search (q, language, year_from, year_to, sort, available_only, page, limit).
- `GET /api/v1/book-db/{isbn}` — canonical book detail by ISBN.
- `GET /api/v1/catalog-external` — reader fallback to external proxy (legacy, read-only).
- Legacy `/api/v1/catalog` and `/api/v1/catalog/{isbn}` have been deleted.

### Reader Account (requires `library.auth` middleware)
- `GET /api/v1/account/summary` — reader identity, profile, and account overview.
- `GET /api/v1/account/loans` — active loans list.
- `POST /api/v1/account/loans/{loanId}/renew` — loan renewal.
- `GET /api/v1/account/reservations` — reservations list.
- `GET /api/v1/me` — auth status check.
- `POST /api/v1/logout` — session logout.

### Authentication
- `POST /api/login` — CRM-proxied login (throttled: 5/min per login+IP).
- Session-based: sets `library.user` in server session.

## Internal Staff Surface (requires `internal.circulation.staff` middleware)

### Circulation
- `GET /api/v1/internal/circulation/loans` — list loans.
- `GET /api/v1/internal/circulation/loans/{loanId}` — loan detail.
- `GET /api/v1/internal/circulation/copies/{copyId}/active-loan` — active loan for copy.
- `GET /api/v1/internal/circulation/readers/{readerId}/loans` — reader's loans.
- `POST /api/v1/internal/circulation/checkouts` — checkout book.
- `POST /api/v1/internal/circulation/returns` — return book.
- `POST /api/v1/internal/circulation/loans/{loanId}/renew` — renew loan.

### Copy Management
- `GET /api/v1/internal/copies/{copyId}` — copy detail.
- `GET /api/v1/internal/documents/{documentId}/copies` — copies by document.
- `POST /api/v1/internal/copies` — create copy.
- `PATCH /api/v1/internal/copies/{copyId}` — update copy.
- `POST /api/v1/internal/copies/{copyId}/retire` — retire copy.

### Review and Data Quality
- `GET /api/v1/internal/review/copies` — copy review queue.
- `GET /api/v1/internal/review/copies-summary` — copy review summary.
- `POST /api/v1/internal/review/copies/{copyId}/resolve` — resolve copy issue.
- `POST /api/v1/internal/review/copies/bulk-resolve` — bulk resolve copies.
- `GET /api/v1/internal/review/documents` — document review queue.
- `GET /api/v1/internal/review/documents-summary` — document review summary.
- `POST /api/v1/internal/review/documents/{documentId}/flag` — flag document.
- `POST /api/v1/internal/review/documents/{documentId}/resolve` — resolve document.
- `POST /api/v1/internal/review/documents/bulk-flag` — bulk flag.
- `POST /api/v1/internal/review/documents/bulk-resolve` — bulk resolve.
- `GET /api/v1/internal/review/readers` — reader review queue.
- `GET /api/v1/internal/review/readers-summary` — reader review summary.
- `POST /api/v1/internal/review/readers/{readerId}/resolve` — resolve reader.
- `POST /api/v1/internal/review/readers/bulk-resolve` — bulk resolve readers.
- `GET /api/v1/internal/review/triage-summary` — triage overview.
- `GET /api/v1/internal/review/triage-reason-codes` — reason code list.
- `GET /api/v1/internal/review/stewardship-metrics` — stewardship metrics.

## Integration Surface (CRM-facing)
- Boundary namespace: `/api/integration/v1`.
- Current contract scope:
  - reservations list,
  - reservation detail,
  - reservation approve,
  - reservation reject,
  - technical boundary ping.
- Request discipline includes required integration headers and consistent request/correlation context.
- Token validation against `INTEGRATION_ALLOWED_TOKENS` env (comma-separated allowlist).

## Contract Governance Rules
- Preserve backward-compatible response envelopes unless explicitly planned.
- Treat current integration v1 as frozen for scope expansion.
- Prefer canonical library-side paths; avoid proliferating parallel endpoint behavior.
