# Digital Smart Library KazUTB

A modern university library system replacing the legacy MARC SQL platform.
Includes a public web catalog, admin panel, REST API, data migration pipeline, analytics dashboard, and role-based access control.

## Project Status

> Foundation phase — architecture is defined, structure is scaffolded, iterative feature implementation in progress.

## Repository Structure

```
kazutb-smart-library/
├── backend/          # NestJS REST API (TypeScript, Prisma, PostgreSQL)
├── frontend/         # React SPA (TypeScript, Vite, Tailwind, shadcn/ui)
├── migration/        # Data migration pipeline from MARC SQL
│   ├── raw/          # Unmodified export snapshots from MARC SQL
│   ├── clean/        # Cleaned and deduplicated data
│   ├── normalized/   # Normalized data ready for PostgreSQL import
│   ├── scripts/      # Export, clean, transform, import scripts
│   ├── logs/         # Migration run logs
│   └── checksums/    # Integrity verification files
├── docs/             # Architecture, domain model, roadmap, API docs
├── scripts/          # Dev utility scripts (seed, reset, check-env)
└── infrastructure/   # DevOps reference notes for future deployment
```

## Tech Stack

| Layer        | Technology                                                      |
| ------------ | --------------------------------------------------------------- |
| Frontend     | React 18, TypeScript, Vite, Tailwind, shadcn/ui, TanStack Query |
| Backend      | Node.js, NestJS, TypeScript, Prisma ORM                         |
| Database     | PostgreSQL                                                      |
| Search       | PostgreSQL FTS + trigram similarity                             |
| Auth         | LDAP / Active Directory (university accounts)                   |
| API          | REST API (versioned, `/api/v1/`)                                |
| Architecture | Modular monolith                                                |

## Product Requirements Baseline

- Interface language support must include `kk`, `ru`, and `en`.
- UI tone and text style must remain formal, academic, and institution-appropriate.
- Library ownership must be segmented by institution scope and branch responsibility:
  - Institution scope: `UNIVERSITY`, `COLLEGE`
  - Branch units: `ECONOMIC_LIBRARY`, `TECHNOLOGICAL_LIBRARY`, `COLLEGE_LIBRARY`
- Librarians from one branch must not manage records owned by another branch.

## Development Workflow Policy

- Direct work on `main` is prohibited.
- All changes are created through feature branches from `main`.
- Each logical step must be committed atomically with a short changelog-friendly message.
- Keep git history readable and rollback-friendly.

## Quick Start

### Prerequisites

- Node.js 20+
- PostgreSQL 15+
- Access to university LDAP (or dev mock mode)

### Backend

```bash
cd backend
cp .env.example .env          # fill in your values
npm install
npx prisma migrate dev
npm run start:dev
```

Minimal variables required for backend startup:

- `DATABASE_URL`
- `JWT_SECRET`

See `backend/.env.example` for the complete development template.

### Frontend

```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

## Documentation

- [Architecture Overview](docs/architecture/overview.md)
- [Tech Stack Decisions](docs/architecture/tech-stack.md)
- [Module Map](docs/architecture/module-map.md)
- [Catalog Core Domain Decisions](docs/architecture/catalog-core-domain.md)
- [Public Catalog UI Alignment](docs/architecture/public-catalog-ui.md)
- [Premium Institutional Design System Polish](docs/architecture/design-system-polish.md)
- [Data Flow & Migration Concept](docs/architecture/data-flow.md)
- [Multilingual and Segmentation Baseline](docs/architecture/multilingual-and-segmentation.md)
- [Frontend i18n Guidelines](docs/architecture/frontend-i18n-guidelines.md)
- [Domain Model](docs/database/domain-model.md)
- [Roles & Access Control](docs/roles-and-access.md)
- [Implementation Roadmap](docs/roadmap.md)
- [MVP Demo Preparation](docs/presentation/mvp-demo-preparation.md)
- [UI Design System Demo Notes](docs/presentation/ui-design-system-demo-notes.md)
- [Migration Pipeline](docs/migration/concept.md)
- [Legacy DB Analysis (Artifact-Based)](docs/migration/legacy-db-analysis.md)
- [Legacy-to-Target Mapping Draft](docs/migration/legacy-to-target-mapping-draft.md)
- [Migration Readiness Plan](docs/migration/migration-readiness-plan.md)
- [Data Quality Taxonomy](docs/migration/data-quality-taxonomy.md)
- [Librarian Data Quality Workflow](docs/migration/librarian-data-quality-workbench-workflow.md)
- [Data Quality Workbench Spec](docs/migration/data-quality-workbench-spec.md)
- [Git Workflow Policy](docs/development/git-workflow.md)

## User Roles

`Guest` → `Student / Teacher` → `Librarian` → `Analyst` → `Admin`

Authentication is exclusively via university Active Directory / LDAP.
Self-registration is not supported.

## License

See [LICENSE](LICENSE).
