# Architecture Overview — KazUTB Smart Library

## Vision

The KazUTB Digital Smart Library is a modern, university-grade library management platform that replaces the legacy MARC SQL system. It provides a centralized, secure, and scalable environment for managing bibliographic data, digital materials, and library operations.

The system is designed as a **commercial-quality institutional product** that:

- Delivers a modern web interface accessible to students, teachers, and library staff
- Provides a complete admin panel for librarians and administrators
- Supports a full data migration pipeline from the legacy MARC SQL system
- Implements strict access controls and copyright protections for digital materials
- Generates institutional reports required for library and accounting compliance
- Supports multilingual interface architecture (`kk`, `ru`, `en`)
- Enforces institutional segmentation between university and college library responsibilities

---

## System Architecture

The system is a **modular monolith** — a single deployable application with well-defined internal module boundaries. This approach was chosen for:

- Faster initial development
- Simpler operational model for university deployment
- Clear path to microservices in future if scale requires it

```
┌─────────────────────────────────────────────────────────────────┐
│                        USERS                                    │
│   Guest │ Student │ Teacher │ Librarian │ Analyst │ Admin       │
└────────────────────────┬────────────────────────────────────────┘
                         │ HTTPS
┌────────────────────────▼────────────────────────────────────────┐
│                   FRONTEND (React SPA)                          │
│   Public Catalog │ Search │ Book Details │ User Cabinet        │
│   Librarian Workspace │ Admin Panel │ Analytics │ Reports       │
└────────────────────────┬────────────────────────────────────────┘
                         │ REST API (JSON over HTTPS)
┌────────────────────────▼────────────────────────────────────────┐
│                   BACKEND (NestJS)                              │
│                                                                 │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌───────────────────┐ │
│  │   Auth   │ │  Books   │ │  Search  │ │   Circulation     │ │
│  └──────────┘ └──────────┘ └──────────┘ └───────────────────┘ │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌───────────────────┐ │
│  │  Users   │ │  Files   │ │ Reports  │ │    Analytics      │ │
│  └──────────┘ └──────────┘ └──────────┘ └───────────────────┘ │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌───────────────────┐ │
│  │Migration │ │  Audit   │ │ Settings │ │   Reservations    │ │
│  └──────────┘ └──────────┘ └──────────┘ └───────────────────┘ │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │               Common Infrastructure                      │   │
│  │  JWT Guards │ RBAC Guards │ Validation │ Logging │ Audit │   │
│  └─────────────────────────────────────────────────────────┘   │
└────────────────────────┬────────────────────────────────────────┘
              ┌──────────┴──────────┐
              │                     │
┌─────────────▼────────┐  ┌────────▼──────────────────────────┐
│   PostgreSQL         │  │   File Storage                    │
│   Primary Database   │  │   Protected digital materials     │
│   - Library catalog  │  │   (no direct public access)       │
│   - Users & roles    │  │   PDFs, scans, e-books            │
│   - Circulation      │  └───────────────────────────────────┘
│   - Audit logs       │
│   - Migration state  │
└──────────────────────┘
              │
┌─────────────▼────────────────────────────────────────────────┐
│   University LDAP / Active Directory                         │
│   Authentication ONLY — no user sync to external systems     │
└──────────────────────────────────────────────────────────────┘
```

---

## Module Boundaries

Each backend module is self-contained with its own:

- Controller (HTTP endpoints)
- Service (business logic)
- DTOs (input validation)
- Prisma queries (data access)

Modules communicate only through clearly defined service interfaces. No module imports another module's repository layer directly.

### Cross-Cutting Product Constraints

- UX and content style are formal and academic, suitable for university operations.
- Every business object that belongs to the library domain must be designed for future scope isolation.
- Scope isolation baseline:
    - Institution scope: `UNIVERSITY` vs `COLLEGE`
    - Branch ownership: `ECONOMIC_LIBRARY`, `TECHNOLOGICAL_LIBRARY`, `COLLEGE_LIBRARY`
    - Librarian operations are restricted by branch ownership boundaries.

### Module Dependency Rules

```
auth         → users, prisma
users        → roles, prisma
books        → authors, categories, prisma
search       → books (read-only queries)
reservations → books, users, circulation, prisma
circulation  → books, users, prisma
files        → books, prisma (storage logic isolated)
migration    → prisma (write-heavy, isolated pipeline)
reports      → prisma (read-only aggregations)
analytics    → prisma (read-only aggregations)
audit        → prisma (append-only)
```

---

## Key Design Decisions

| Decision     | Choice                      | Reason                                          |
| ------------ | --------------------------- | ----------------------------------------------- |
| Architecture | Modular monolith            | Speed, simplicity, university server deployment |
| Auth         | LDAP/AD only                | University policy; no self-registration         |
| API style    | REST                        | Universal, simple, well-understood              |
| ORM          | Prisma                      | Type-safe, excellent DX, migration support      |
| Search       | PostgreSQL FTS + trigram    | No extra infra; sufficient for library scale    |
| File access  | Server-proxied view-only    | Copyright compliance; no raw file exposure      |
| Reporting    | DB aggregations + templates | Full control, accounting-grade accuracy         |

---

## Deployment Context

The system runs on a **university-owned on-premises server**, not in the cloud. This means:

- No external cloud dependencies required
- LDAP integration connects to internal AD
- File storage is local disk (with backup)
- No containerization required for initial deployment (DevOps handles separately)

See [infrastructure/notes.md](../../infrastructure/notes.md) for DevOps handoff notes.
