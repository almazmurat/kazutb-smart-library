# Tech Stack Decisions — KazUTB Smart Library

## Final Selected Stack

### Frontend

| Technology             | Version         | Purpose                                                               |
| ---------------------- | --------------- | --------------------------------------------------------------------- |
| React                  | 18              | UI framework                                                          |
| TypeScript             | 5               | Type safety across the entire frontend                                |
| Vite                   | 5               | Build tool and dev server (fast HMR)                                  |
| Tailwind CSS           | 3               | Utility-first styling                                                 |
| shadcn/ui              | latest          | Accessible, customizable component library (built on Radix UI)        |
| React Router           | 6               | Client-side routing                                                   |
| TanStack Query         | 5               | Server state management, caching, background refetch                  |
| TanStack Table         | 8               | Feature-rich tables for admin/librarian views                         |
| React Hook Form        | 7               | Form state and validation                                             |
| Zod                    | 3               | Schema validation (shared with backend DTOs)                          |
| Axios                  | 1               | HTTP client with interceptors for auth token handling                 |
| Lightweight i18n layer | internal module | Locale configuration and text dictionary loading for `kk`, `ru`, `en` |

**Rationale:** React is mandated by project requirements. Vite provides the fastest development experience. Tailwind + shadcn/ui enables rapid, consistent UI development without writing much CSS. TanStack Query replaces Redux for server state — much simpler model for a data-heavy application.

Multilingual baseline in this phase is architecture readiness, not full translation rollout.

---

### Backend

| Technology        | Version | Purpose                                              |
| ----------------- | ------- | ---------------------------------------------------- |
| Node.js           | 20 LTS  | Runtime                                              |
| NestJS            | 10      | Framework (DI, modules, guards, pipes, interceptors) |
| TypeScript        | 5       | Type safety                                          |
| Prisma ORM        | 5       | Database access, schema management, migrations       |
| Passport.js       | —       | Auth strategy management (JWT + LDAP)                |
| passport-jwt      | —       | JWT strategy                                         |
| passport-ldapauth | —       | LDAP/AD strategy                                     |
| class-validator   | —       | DTO validation                                       |
| class-transformer | —       | Request/response transformation                      |
| @nestjs/swagger   | —       | Auto-generated OpenAPI/Swagger docs                  |
| @nestjs/config    | —       | Environment configuration management                 |
| @nestjs/throttler | —       | Rate limiting                                        |

**Rationale:** NestJS was chosen for its strong conventions, built-in DI container, and excellent TypeScript support. It produces maintainable, enterprise-structured code. Prisma provides the best DX for type-safe database access with PostgreSQL.

---

### Database

| Technology             | Purpose                                    |
| ---------------------- | ------------------------------------------ |
| PostgreSQL 15          | Primary relational database                |
| `pg_trgm` extension    | Trigram similarity search (fuzzy matching) |
| `tsvector` / `tsquery` | Full-text search                           |
| `uuid-ossp` extension  | UUID primary keys                          |

**Rationale:** PostgreSQL is the strongest open-source relational DB. It handles structured data, transactions, full-text search, and complex reporting queries within a single engine — no need for a separate Elasticsearch or search service at library scale.

---

### Authentication

| Technology                    | Purpose                                              |
| ----------------------------- | ---------------------------------------------------- |
| LDAP / Active Directory       | University user authentication                       |
| JWT (access + refresh tokens) | Stateless session management after LDAP verification |
| `passport-ldapauth`           | LDAP strategy implementation                         |

**Important:** Authentication is exclusively through the university's Active Directory. Users cannot register themselves. Credentials are never stored in the application database — only university accounts processed through LDAPs.

In development, a `LDAP_DEV_MOCK` flag enables local testing without a real AD connection.

---

### File Storage

| Approach               | Details                                                          |
| ---------------------- | ---------------------------------------------------------------- |
| Local filesystem       | Protected directory not accessible via public URL                |
| Proxy endpoint         | Backend streams file to browser via `/api/v1/files/:id/view`     |
| Access control         | JWT verification + role check on every file request              |
| Copyright restrictions | Download, copy, text selection disabled via viewer configuration |

No S3 or cloud storage in the initial phase. Files are stored on the university server alongside the application. If scale requires it, the storage layer can be migrated to S3-compatible storage without changing the API contract.

---

### Not Used (and why)

| Excluded      | Reason                                                                     |
| ------------- | -------------------------------------------------------------------------- |
| GraphQL       | REST is sufficient; adds complexity without benefit at this scale          |
| Redis         | No session store needed (JWT is stateless); can be added for caching later |
| Elasticsearch | PostgreSQL FTS is sufficient for a university library catalog              |
| Docker / K8s  | DevOps scope; not in current phase                                         |
| Next.js / SSR | Not required; React SPA is sufficient for this application                 |
| Microservices | Premature; modular monolith is the right starting point                    |
| MongoDB       | Structured library data maps naturally to relational model                 |
