# CIRCULATION_WORKFLOWS
> Derived from [[PROJECT_CONTEXT]] §15

## Reservation workflow
Member submits a reservation, librarian confirms and assigns a copy, the item becomes ready for pickup, and then the issue action converts the reservation into a fulfilled loan.

## Issue workflow
```
Librarian opens issue panel
  → finds the user
  → finds the copy by barcode or inventory number
  → confirms issue
  → loan record is created with due date
  → audit trail is written
```

## Return workflow
```
Librarian opens return panel
  → scans barcode or enters inventory number
  → confirms return
  → copy becomes available again
  → history is updated
  → overdue note is added if needed
```

## QR note
QR support is explicitly allowed for faster issue and return at the desk.

## Links
- [[PROJECT_CONTEXT]]
- [[RESERVATION_WORKFLOW]]
- [[CIRCULATION_POLICY]]
