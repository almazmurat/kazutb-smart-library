# Reservations MVP

## Scope

This document defines the completed Reservations MVP for KazUTB Smart Library.

The MVP covers:

- user reservation creation from book details
- user reservation visibility and cancellation from cabinet
- librarian/admin reservation queue management
- branch ownership restrictions
- audit event logging for reservation actions

The MVP does not include circulation operations (loans/returns), notifications, or advanced analytics.

## Reservation Lifecycle

Status values:

- `PENDING` - reservation is created and awaiting librarian processing
- `READY` - reservation is approved and ready for pickup/fulfillment workflow
- `FULFILLED` - reservation lifecycle completed
- `CANCELLED` - reservation was cancelled by user or rejected by staff
- `EXPIRED` - reservation lifetime elapsed

Supported transitions in current MVP:

- create -> `PENDING`
- librarian/admin update -> `READY` | `FULFILLED` | `CANCELLED` | `EXPIRED`
- user cancel allowed only from `PENDING` or `READY`
- user cancel forbidden from finalized states such as `FULFILLED`, `CANCELLED`, `EXPIRED`

## Role Behavior

| Role      | Create Reservation                 | View Own Reservations | Cancel Own Reservation                          | View Queue            | Update Queue Status   |
| --------- | ---------------------------------- | --------------------- | ----------------------------------------------- | --------------------- | --------------------- |
| GUEST     | No                                 | No                    | No                                              | No                    | No                    |
| STUDENT   | Yes                                | Yes                   | Yes (when status is `PENDING`/`READY`)          | No                    | No                    |
| TEACHER   | Yes                                | Yes                   | Yes (when status is `PENDING`/`READY`)          | No                    | No                    |
| LIBRARIAN | API allows, UI hides create action | Yes                   | Yes for own reservation with status constraints | Yes (own branch only) | Yes (own branch only) |
| ADMIN     | API allows, UI hides create action | Yes                   | Yes for own reservation with status constraints | Yes (all branches)    | Yes (all branches)    |

## Branch and Scope Ownership

Ownership is enforced at service level:

- reservation is created with `libraryBranchId` inherited from the book
- librarian queue listing is limited to librarian assigned branch
- librarian status updates are denied for reservations outside assigned branch
- branch filter requests from librarian cannot target another branch
- admin is not branch-limited

## API Endpoints in MVP

- `POST /api/v1/reservations`
- `GET /api/v1/reservations/my`
- `GET /api/v1/reservations`
- `GET /api/v1/reservations/:id`
- `PATCH /api/v1/reservations/:id/cancel`
- `PATCH /api/v1/reservations/:id/status`

## Frontend Routes in MVP

- `/books/:id` - reservation CTA for eligible users
- `/cabinet` - user reservation list and cancellation actions
- `/librarian` - librarian reservation queue and status actions

All authenticated routes are wrapped by `ProtectedRoute` to prevent guest access.

## Audit Events

Reservation module writes audit events:

- `RESERVATION_CREATED`
- `RESERVATION_CANCELLED`
- `RESERVATION_STATUS_UPDATED`

## Postponed to Circulation Phase

The following are intentionally postponed and out of MVP scope:

- loans and returns orchestration
- penalties/fines logic
- notification delivery (email/SMS/push)
- advanced reservation analytics dashboards
- external integrations for circulation automation
