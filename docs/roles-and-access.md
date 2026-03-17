# Roles & Access Control — KazUTB Smart Library

## Overview

The system implements **Role-Based Access Control (RBAC)** with six roles. Access is enforced at the API level via NestJS Guards, and additionally at the frontend routing level for UX.

Authentication is exclusively through **university Active Directory / LDAP**. Self-registration is not possible.

In addition to role checks, the platform follows ownership segmentation:

- Institution scope: `UNIVERSITY` and `COLLEGE`
- Library branch ownership: `ECONOMIC_LIBRARY`, `TECHNOLOGICAL_LIBRARY`, `COLLEGE_LIBRARY`
- A librarian can only manage records inside the assigned branch responsibility zone.
- Cross-branch updates are denied by business rules (progressively enforced module by module).

---

## Role Definitions

### GUEST (unauthenticated)

Default state for any visitor who has not logged in.

| Permission                                             | Access |
| ------------------------------------------------------ | ------ |
| Browse public book catalog                             | ✅     |
| View book metadata (title, author, year, availability) | ✅     |
| Search by title, author, keyword                       | ✅     |
| View copy availability count                           | ✅     |
| View book details page                                 | ✅     |
| View digital materials / files                         | ❌     |
| Reserve a book                                         | ❌     |
| Access user cabinet                                    | ❌     |
| Access any protected endpoints                         | ❌     |

---

### STUDENT

University student authenticated via LDAP.

| Permission                                      | Access |
| ----------------------------------------------- | ------ |
| Everything Guest can do                         | ✅     |
| Reserve available books                         | ✅     |
| Cancel own reservations                         | ✅     |
| View own loan history                           | ✅     |
| Access user cabinet                             | ✅     |
| View digital materials (VIEW_ONLY, inline only) | ✅     |
| Download digital materials                      | ❌     |
| Copy / select text in digital viewer            | ❌     |
| Access librarian or admin sections              | ❌     |

---

### TEACHER

University faculty/staff authenticated via LDAP. Same permissions as STUDENT with identical access level to digital materials.

| Permission                                    | Access            |
| --------------------------------------------- | ----------------- |
| Everything Student can do                     | ✅                |
| Extended loan period (configured in settings) | ✅                |
| Priority reservation                          | ✅ (configurable) |

---

### LIBRARIAN

Library staff. Primary operational role.

| Permission                              | Access          |
| --------------------------------------- | --------------- |
| Everything Student/Teacher can do       | ✅              |
| Issue (loan out) book copies            | ✅              |
| Process returns                         | ✅              |
| View all active loans and overdue items | ✅              |
| Manage book catalog (add, edit)         | ✅              |
| Manage physical copies                  | ✅              |
| Upload digital materials for books      | ✅              |
| Manage invoices and acquisitions        | ✅              |
| Fulfill and cancel reservations         | ✅              |
| Access reports module                   | ✅              |
| View analytics dashboard                | ✅              |
| Manage user data                        | ❌ (Admin only) |
| Change user roles                       | ❌ (Admin only) |
| Access migration panel                  | ❌ (Admin only) |
| Access system settings                  | ❌ (Admin only) |
| Delete books (hard delete)              | ❌ (Admin only) |

---

### ANALYST

Read-only analytical role for staff who need reporting access.

| Permission                    | Access |
| ----------------------------- | ------ |
| View analytics dashboard      | ✅     |
| Generate and view all reports | ✅     |
| Export reports (CSV/JSON)     | ✅     |
| View circulation history      | ✅     |
| No write access anywhere      | ❌     |

---

### ADMIN

Full system access. University IT or library administration.

| Permission                          | Access |
| ----------------------------------- | ------ |
| Everything                          | ✅     |
| Add / edit / delete users           | ✅     |
| Assign and change user roles        | ✅     |
| Delete books (soft delete)          | ✅     |
| Manage system settings              | ✅     |
| Run and monitor migration pipeline  | ✅     |
| View audit logs                     | ✅     |
| Full analytics and reporting access | ✅     |

---

## Permission Matrix (API-Level)

| Endpoint Group             | Guest | Student | Teacher | Librarian | Analyst | Admin |
| -------------------------- | :---: | :-----: | :-----: | :-------: | :-----: | :---: |
| `GET /books` (catalog)     |  ✅   |   ✅    |   ✅    |    ✅     |   ✅    |  ✅   |
| `GET /books/:id`           |  ✅   |   ✅    |   ✅    |    ✅     |   ✅    |  ✅   |
| `POST /books`              |  ❌   |   ❌    |   ❌    |    ✅     |   ❌    |  ✅   |
| `PATCH /books/:id`         |  ❌   |   ❌    |   ❌    |    ✅     |   ❌    |  ✅   |
| `DELETE /books/:id`        |  ❌   |   ❌    |   ❌    |    ❌     |   ❌    |  ✅   |
| `GET /files/:id/view`      |  ❌   |   ✅    |   ✅    |    ✅     |   ❌    |  ✅   |
| `POST /files/upload`       |  ❌   |   ❌    |   ❌    |    ✅     |   ❌    |  ✅   |
| `POST /reservations`       |  ❌   |   ✅    |   ✅    |    ✅     |   ❌    |  ✅   |
| `GET /reservations` (all)  |  ❌   |   ❌    |   ❌    |    ✅     |   ❌    |  ✅   |
| `POST /circulation/loan`   |  ❌   |   ❌    |   ❌    |    ✅     |   ❌    |  ✅   |
| `POST /circulation/return` |  ❌   |   ❌    |   ❌    |    ✅     |   ❌    |  ✅   |
| `GET /reports/*`           |  ❌   |   ❌    |   ❌    |    ✅     |   ✅    |  ✅   |
| `GET /analytics/*`         |  ❌   |   ❌    |   ❌    |    ✅     |   ✅    |  ✅   |
| `GET /users` (all)         |  ❌   |   ❌    |   ❌    |    ❌     |   ❌    |  ✅   |
| `PATCH /users/:id/role`    |  ❌   |   ❌    |   ❌    |    ❌     |   ❌    |  ✅   |
| `POST /migration/batches`  |  ❌   |   ❌    |   ❌    |    ❌     |   ❌    |  ✅   |
| `GET /audit/logs`          |  ❌   |   ❌    |   ❌    |    ❌     |   ❌    |  ✅   |
| `GET /settings`            |  ❌   |   ❌    |   ❌    |    ❌     |   ❌    |  ✅   |

---

## Implementation Notes

### Backend (NestJS)

Access is enforced via two guards applied globally:

1. **`JwtAuthGuard`** — validates JWT token. Routes marked `@Public()` skip this guard (catalog, search, book details, login).
2. **`RolesGuard`** — checks the `@Roles()` decorator on controller methods. If no `@Roles()` decorator is present, any authenticated user passes.

```typescript
// Example usage in controller:
@Roles(UserRole.LIBRARIAN, UserRole.ADMIN)
@Patch(':id')
updateBook(@Param('id') id: string, @Body() dto: UpdateBookDto) { ... }
```

### Frontend

Route-level protection via `ProtectedRoute` component:

```tsx
<Route
  path="/admin/*"
  element={
    <ProtectedRoute roles={["ADMIN"]}>
      <AdminLayout />
    </ProtectedRoute>
  }
/>
```

Frontend protection is UX only. All security is enforced on the backend.

### LDAP Role Mapping

On first login, users are auto-provisioned with role `STUDENT` by default.
Admins then manually assign the correct role via the user management panel.

Future enhancement: Map LDAP group membership (AD groups) to system roles automatically.

---

## Digital Materials Access Summary

| User Type               | Can See File Exists | Can View Inline  | Can Download | Can Copy Text |
| ----------------------- | :-----------------: | :--------------: | :----------: | :-----------: |
| Guest (unauthenticated) |         ❌          |        ❌        |      ❌      |      ❌       |
| Student                 |         ✅          | ✅ (inline only) |      ❌      |      ❌       |
| Teacher                 |         ✅          | ✅ (inline only) |      ❌      |      ❌       |
| Librarian               |         ✅          | ✅ (inline only) |      ❌      |      ❌       |
| Admin                   |         ✅          | ✅ (inline only) |      ❌      |      ❌       |

No role is permitted to download files. This is enforced at the HTTP response header level, not just in the UI.
