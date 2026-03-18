# Circulation MVP — Loan / Return / Copy Status

## Scope

This document defines the Circulation MVP for KazUTB Smart Library.

The MVP covers:

- librarian/admin loan issuance to borrowers
- librarian/admin return processing
- copy status consistency during circulation
- branch/scope ownership enforcement
- user-facing loan visibility (cabinet)
- audit integration for all circulation mutations

The MVP does not include fines/penalties, notifications, advanced overdue automation, or analytics.

## Loan Data Model

The `Loan` model (`backend/prisma/schema.prisma`) tracks individual book copy issuances.

| Field | Type | Description |
| --- | --- | --- |
| id | UUID | Primary key |
| userId | UUID → User | Borrower |
| copyId | UUID → BookCopy | Physical copy issued |
| libraryBranchId | UUID → LibraryBranch | Branch where copy belongs |
| issuedBy | UUID | Librarian/admin who issued the loan |
| loanedAt | DateTime | When the loan was created |
| dueDate | DateTime | Expected return date |
| returnedAt | DateTime? | Actual return timestamp |
| notes | String? | Librarian notes |
| status | LoanStatus | Current loan state |

## Loan Status Lifecycle

Status values:

- `ACTIVE` — loan is currently in effect, copy is with borrower
- `RETURNED` — copy has been returned and processed
- `OVERDUE` — loan has passed its due date (tracked via query, not automatic status change in MVP)
- `LOST` — copy marked as lost during loan period (reserved for future use)

Supported transitions in MVP:

- issue → `ACTIVE`
- return (from `ACTIVE` or `OVERDUE`) → `RETURNED`
- overdue detection is done at query time by checking `dueDate < now()` for ACTIVE loans

## Copy Status Lifecycle

Copy statuses relevant to circulation:

- `AVAILABLE` — copy is free for lending or reservation
- `LOANED` — copy is currently on loan to a borrower
- `RESERVED` — copy is held by reservation logic (from Reservations MVP)
- `LOST` / `ARCHIVED` / `DAMAGED` / `WRITTEN_OFF` — non-circulating statuses

Circulation rules:

- Issuing a loan: copy must be `AVAILABLE` → set to `LOANED`
- Returning a loan: copy is `LOANED` → restored to `AVAILABLE`
- Non-available copies (RESERVED, LOANED, LOST, etc.) cannot be issued
- Returning always restores to `AVAILABLE` (not RESERVED) — reservation/circulation interaction is simplified for MVP

## Reservation / Circulation Interaction

In this MVP:

- Reservations and loans operate independently
- A reservation reaching FULFILLED status does not automatically create a loan
- Issuing a loan requires the copy to be AVAILABLE — if a copy is RESERVED, it cannot be issued
- Future phases may link reservation fulfillment to loan creation

## Role Behavior

| Role | Issue Loan | Return Loan | View All Loans | View Own Loans |
| --- | --- | --- | --- | --- |
| GUEST | No | No | No | No |
| STUDENT | No | No | No | Yes |
| TEACHER | No | No | No | Yes |
| LIBRARIAN | Yes (own branch) | Yes (own branch) | Yes (own branch) | Yes |
| ADMIN | Yes (all branches) | Yes (all branches) | Yes (all branches) | Yes |

## Branch and Scope Ownership

Ownership is enforced at service level:

- loan is created with `libraryBranchId` inherited from the book copy
- librarian loan listing is limited to their assigned branch
- librarian issue/return is denied for copies outside their assigned branch
- admin has full bypass — no branch restrictions apply
- students/teachers cannot perform circulation actions, only view their own loans

## API Endpoints

| Method | Path | Roles | Description |
| --- | --- | --- | --- |
| POST | /api/v1/circulation/loans | LIBRARIAN, ADMIN | Issue a new loan |
| PATCH | /api/v1/circulation/loans/:id/return | LIBRARIAN, ADMIN | Process return |
| GET | /api/v1/circulation/loans | LIBRARIAN, ADMIN | List loans (filterable) |
| GET | /api/v1/circulation/loans/:id | AUTH | Get single loan details |
| GET | /api/v1/circulation/my | AUTH | User's own loans |

List filters: status, userId, copyId, branchId, overdueOnly, page, limit.

## Frontend Routes

| Path | Component | Access |
| --- | --- | --- |
| /librarian/circulation | CirculationPage | LIBRARIAN, ADMIN |
| /cabinet | CabinetPage (includes loans section) | Authenticated |
| /cabinet/reservations | CabinetPage | Authenticated |

## Audit Events

Circulation module writes audit events:

- `LOAN_CREATED` — when a librarian issues a loan
- `LOAN_RETURNED` — when a librarian processes a return
- `COPY_STATUS_UPDATED` — when copy status changes due to circulation (AVAILABLE→LOANED or LOANED→AVAILABLE)

## Postponed to Future Phases

The following are intentionally postponed and out of MVP scope:

- fines and penalties logic
- automatic overdue status transition (cron job)
- notification delivery (email/SMS/push)
- linking reservation fulfillment to loan creation
- advanced analytics and reporting on circulation metrics
- barcode/RFID scanning integration
- multi-copy reservation-to-loan orchestration
