# Domain Model — KazUTB Smart Library

## Entity Overview

The domain is organized into six functional groups:

1. **Identity** — Users and roles
2. **Bibliography** — Books, authors, categories, publishers
3. **Inventory** — Physical copies, acquisitions, invoices
4. **Digital Materials** — Protected electronic files
5. **Circulation** — Reservations and loans
6. **Operations** — Audit logs, migration tracking, system settings

Additional cross-cutting domain requirement:

- **Segmentation** — Institution scope and branch ownership for data isolation.

---

## Entity Map

```
┌──────────────────────────────────────────────────────────────────┐
│  IDENTITY                                                        │
│                                                                  │
│  User ──────── Role (enum)                                       │
│    universityId (LDAP login)                                     │
│    fullName                                                      │
│    email                                                         │
└──────────────────────────────────────────────────────────────────┘
                    │                │
                    │ owns           │ issues/receives
                    ▼                ▼
┌──────────────┐  ┌──────────────────────────────────────────────┐
│  Reservation │  │  BIBLIOGRAPHY                                │
│  ───────────-│  │                                              │
│  status      │  │  Book ──────────────── Author (M:N)         │
│  reservedAt  │  │    title               via BookAuthor        │
│  expiresAt   │  │    isbn                                      │
└──────┬───────┘  │    udc / bbk                                 │
       │          │    publishYear                               │
       │          │    language         Publisher (M:1)          │
       │          │    keywords[]       ───────────              │
       │          │    searchVector     name, city, country      │
       │          │                                              │
       │          │  Book ──────────────── Category (M:N)        │
       │          │                        via BookCategory       │
       │          │                        (hierarchical tree)    │
       │          └──────────────────────────────────────────────┘
       │                        │                 │
       │                        │ has             │ has
       │                        ▼                 ▼
       │          ┌─────────────────┐  ┌──────────────────────┐
       └─────────►│  BookCopy       │  │  BookFile            │
                  │  (INVENTORY)    │  │  (DIGITAL MATERIALS) │
                  │  ─────────────  │  │  ──────────────────  │
                  │  inventoryNumber│  │  fileType            │
                  │  fund           │  │  storagePath (hidden)│
                  │  status         │  │  fileHash (SHA-256)  │
                  │  condition      │  │  accessLevel         │
                  │  acquisitionDate│  └──────────────────────┘
                  │  Invoice (M:1)  │
                  └────────┬────────┘
                           │ involved in
                           ▼
                  ┌────────────────────────────────┐
                  │  CIRCULATION                   │
                  │                                │
                  │  Loan                          │
                  │    user, copy                  │
                  │    loanedAt, dueDate           │
                  │    returnedAt, status          │
                  └────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  ACQUISITIONS                                                    │
│                                                                  │
│  Invoice ──────────────── BookCopy (1:N)                         │
│    invoiceNumber                                                 │
│    supplier, invoiceDate, totalAmount                            │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  OPERATIONS                                                      │
│                                                                  │
│  AuditLog — append-only record of all significant actions       │
│  MigrationBatch ────── MigrationLog (1:N)                        │
│  SystemSetting — key/value configuration store                   │
└──────────────────────────────────────────────────────────────────┘
```

---

## Entity Definitions

### InstitutionScope

| Field    | Type    | Notes                   |
| -------- | ------- | ----------------------- |
| id       | UUID    | Primary key             |
| code     | enum    | `UNIVERSITY`, `COLLEGE` |
| name     | String  | Scope display name      |
| isActive | Boolean | Scope active flag       |

### LibraryBranch

| Field    | Type    | Notes                                                          |
| -------- | ------- | -------------------------------------------------------------- |
| id       | UUID    | Primary key                                                    |
| code     | enum    | `ECONOMIC_LIBRARY`, `TECHNOLOGICAL_LIBRARY`, `COLLEGE_LIBRARY` |
| name     | String  | Branch display name                                            |
| scopeId  | UUID    | FK → InstitutionScope                                          |
| isActive | Boolean | Branch active flag                                             |

Branch ownership is designed to be linked to users and library records for future branch isolation enforcement.

---

### User

| Field        | Type          | Notes                               |
| ------------ | ------------- | ----------------------------------- |
| id           | UUID          | Primary key                         |
| universityId | String unique | LDAP `sAMAccountName`               |
| email        | String unique | From LDAP `mail`                    |
| fullName     | String        | From LDAP `displayName`             |
| role         | Role enum     | Assigned by Admin; default STUDENT  |
| isActive     | Boolean       | Can be deactivated without deletion |
| lastLoginAt  | DateTime?     | Updated on each successful login    |

**Role enum:** `GUEST, STUDENT, TEACHER, LIBRARIAN, ADMIN, ANALYST`

Note: Guest is the implicit role for unauthenticated access. Authenticated users are minimum STUDENT.

---

### Book

| Field           | Type           | Notes                                   |
| --------------- | -------------- | --------------------------------------- |
| id              | UUID           | Primary key                             |
| title           | String         | Required                                |
| subtitle        | String?        | Optional subtitle                       |
| isbn            | String? unique | ISBN-10 or ISBN-13                      |
| issn            | String?        | For serials/periodicals                 |
| udc             | String?        | Universal Decimal Classification        |
| bbk             | String?        | BBK Russian library classification      |
| publishYear     | Integer?       | Year of publication                     |
| edition         | String?        | Edition text (e.g., "2nd ed.")          |
| language        | String?        | ISO 639-1 code (e.g., "ru", "kk", "en") |
| pageCount       | Integer?       |                                         |
| description     | String?        | Annotation / abstract                   |
| keywords        | String[]       | Free-form search tags                   |
| publisherId     | UUID?          | FK → Publisher                          |
| libraryBranchId | UUID           | FK → LibraryBranch (required ownership) |
| searchVector    | tsvector       | Maintained by DB trigger for FTS        |
| isActive        | Boolean        | Soft delete flag                        |

---

### BookCopy (Physical Copy / Inventory Item)

| Field           | Type            | Notes                                                     |
| --------------- | --------------- | --------------------------------------------------------- |
| id              | UUID            | Primary key                                               |
| bookId          | UUID            | FK → Book                                                 |
| inventoryNumber | String unique   | Library inventory barcode                                 |
| fund            | String?         | Library section/fund (e.g., "Main", "Reading Room")       |
| status          | CopyStatus enum | `AVAILABLE, LOANED, RESERVED, LOST, DAMAGED, WRITTEN_OFF` |
| condition       | String?         | Physical condition notes                                  |
| acquisitionDate | DateTime?       | Date received by library                                  |
| invoiceId       | UUID?           | FK → Invoice                                              |
| libraryBranchId | UUID            | FK → LibraryBranch (required ownership)                   |

Current `CopyStatus` in schema includes `ARCHIVED` for lifecycle closure.

---

### Author

| Field    | Type    | Notes                   |
| -------- | ------- | ----------------------- |
| id       | UUID    | Primary key             |
| fullName | String  | Reusable authority name |
| isActive | Boolean | Soft deactivate support |

---

### Category

| Field    | Type    | Notes                        |
| -------- | ------- | ---------------------------- |
| id       | UUID    | Primary key                  |
| name     | String  | Reusable normalized category |
| code     | String? | Optional institutional code  |
| parentId | UUID?   | Hierarchical parent category |
| isActive | Boolean | Soft deactivate support      |

---

### BookFile (Protected Digital Material)

| Field       | Type                 | Notes                                              |
| ----------- | -------------------- | -------------------------------------------------- |
| id          | UUID                 | Primary key                                        |
| bookId      | UUID                 | FK → Book                                          |
| fileType    | String               | `pdf`, `epub`, `djvu`                              |
| storagePath | String               | Internal server path; NEVER exposed via API        |
| fileHash    | String               | SHA-256 of file content for integrity verification |
| fileSizeKb  | Integer?             |                                                    |
| accessLevel | FileAccessLevel enum | `NONE, METADATA, VIEW_ONLY`                        |

**FileAccessLevel:**

- `NONE` — not accessible to any user
- `METADATA` — only existence is visible (no content)
- `VIEW_ONLY` — inline streaming allowed for authenticated university users; no download

---

### Reservation

| Field      | Type              | Notes                                           |
| ---------- | ----------------- | ----------------------------------------------- |
| id         | UUID              | Primary key                                     |
| userId     | UUID              | FK → User                                       |
| bookId     | UUID              | FK → Book                                       |
| copyId     | UUID?             | FK → BookCopy (assigned when ready)             |
| status     | ReservationStatus | `PENDING, READY, FULFILLED, CANCELLED, EXPIRED` |
| reservedAt | DateTime          | When reservation was created                    |
| expiresAt  | DateTime?         | When reservation expires if not picked up       |

---

### Loan

| Field      | Type       | Notes                             |
| ---------- | ---------- | --------------------------------- |
| id         | UUID       | Primary key                       |
| userId     | UUID       | FK → User (borrower)              |
| copyId     | UUID       | FK → BookCopy                     |
| issuedBy   | String?    | Librarian user ID                 |
| loanedAt   | DateTime   | Issue date                        |
| dueDate    | DateTime   | Return deadline                   |
| returnedAt | DateTime?  | Actual return date                |
| status     | LoanStatus | `ACTIVE, RETURNED, OVERDUE, LOST` |

---

### Invoice (Acquisition Record)

| Field         | Type           | Notes                    |
| ------------- | -------------- | ------------------------ |
| id            | UUID           | Primary key              |
| invoiceNumber | String unique  | Official document number |
| supplier      | String         | Supplier/vendor name     |
| invoiceDate   | DateTime       | Date of invoice          |
| totalAmount   | Decimal(12,2)? | KZT amount               |
| currency      | String         | Default `KZT`            |

One Invoice → many BookCopies (the books received in that delivery).

---

### MigrationBatch

| Field                   | Type                 | Notes                                              |
| ----------------------- | -------------------- | -------------------------------------------------- |
| id                      | UUID                 | Primary key                                        |
| batchName               | String unique        | e.g., `2026-03-17_full-export`                     |
| status                  | MigrationBatchStatus | `PENDING, IN_PROGRESS, COMPLETED, FAILED, PARTIAL` |
| totalRecords            | Integer?             | Records in source file                             |
| importedRecords         | Integer?             | Successfully imported                              |
| failedRecords           | Integer?             | Records that failed                                |
| checksum                | String?              | SHA-256 of normalized input file                   |
| startedAt / completedAt | DateTime?            | Execution timing                                   |

---

## Key Relationships

| Relationship                          | Cardinality            | Notes                                           |
| ------------------------------------- | ---------------------- | ----------------------------------------------- |
| Book ↔ Author                         | M:N via `BookAuthor`   | Author has `role` (author/editor/translator)    |
| Book ↔ Category                       | M:N via `BookCategory` | Categories are hierarchical (self-referencing)  |
| Book → Publisher                      | M:1                    | Many books per publisher                        |
| Book → BookCopy                       | 1:N                    | One title, many physical copies                 |
| Book → BookFile                       | 1:N                    | One title, potentially multiple digital formats |
| BookCopy → Invoice                    | M:1                    | Many copies per received shipment               |
| User → Reservation                    | 1:N                    | User can have multiple reservations             |
| User → Loan                           | 1:N                    | User's full loan history                        |
| MigrationBatch → MigrationLog         | 1:N                    | Per-record import log                           |
| InstitutionScope → LibraryBranch      | 1:N                    | Scope-level partitioning                        |
| LibraryBranch → User                  | 1:N                    | User responsibility zone                        |
| LibraryBranch → Book/BookCopy/Invoice | 1:N                    | Record ownership zone                           |

---

## Data Zones for Migration

| Zone       | Location                    | Purpose                                      |
| ---------- | --------------------------- | -------------------------------------------- |
| Raw        | `migration/raw/`            | Unmodified MARC SQL export, archived forever |
| Clean      | `migration/clean/`          | Deduplicated, encoding-normalized            |
| Normalized | `migration/normalized/`     | Mapped to target schema, import-ready        |
| Production | PostgreSQL `kazutb_library` | Live system database                         |
