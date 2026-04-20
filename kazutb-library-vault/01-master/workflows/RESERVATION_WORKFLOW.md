# RESERVATION_WORKFLOW
> Derived from [[PROJECT_CONTEXT]] §15.1 and §12

## Status flow
```
[pending] → [confirmed] → [ready_for_pickup] → [fulfilled]
                       ↘ [cancelled]
[pending/confirmed] → [expired]
```

## Detailed steps
1. Member reserves an available item.
2. Status becomes `pending`.
3. Librarian reviews and assigns a suitable copy.
4. Status becomes `confirmed`, then `ready_for_pickup`.
5. Member is notified.
6. At pickup, the librarian issues the copy and the reservation becomes `fulfilled`.
7. If the user does not collect it in time, the reservation auto-expires.

## Operational rules
Reservations are limited, cancellable by the user before confirmation, and always visible in the member dashboard.

## Links
- [[PROJECT_CONTEXT]]
- [[CIRCULATION_POLICY]]
- [[CIRCULATION_WORKFLOWS]]
