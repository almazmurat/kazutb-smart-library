# Digital Library

A production-oriented digital library platform built with **Laravel**, **PostgreSQL**, **Blade/React/Vite**, and **Docker Compose**.

Digital Library is not just a landing site or a catalog skin. It is the library application layer for public discovery, reader accounts, staff operations, digital materials, and future reporting/analytics workflows.

---

## Project overview

The platform combines:
- public catalog and book-detail discovery
- search and subject navigation with growing **UDC-driven** workflows
- reader account and reservation flows
- teacher shortlist / syllabus-support workflows
- internal review, stewardship, and circulation surfaces
- licensed external resource access and controlled digital material handling
- an auth/integration boundary to an external CRM/identity provider

## Major capabilities

- **Reader-facing public shell** across homepage, catalog, resources, contacts, and discovery pages
- **Catalog search API** with URL-synced filters for query, language, year range, sort, and availability
- **Account area** for authenticated reader summaries, loans, and reservations
- **Protected internal staff routes** for dashboard, review, stewardship, circulation, and internal support tools
- **Faculty support flows** consolidated into `/resources`, `/shortlist`, and `/account`
- **Integration-safe auth boundary** where CRM handles authentication without owning library business logic

---

## Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.4+ |
| Frontend | Blade, React, Vite |
| Database | PostgreSQL |
| Runtime | Docker Compose |
| Testing | PHPUnit feature/API tests, Playwright smoke tests |
| CI/CD | GitHub Actions |

---

## Architecture summary

- **Library platform owns** catalog, discovery, holdings/copies logic, accounts, reservations, stewardship, internal operations, and reporting-compatible library behavior.
- **CRM owns** authentication/integration APIs and must **not** connect directly to the library database.
- **PostgreSQL** is the canonical application data store.
- **Blade + React/Vite** are used together: Blade for public-facing pages and React for richer SPA surfaces under `/app/*`.

For more detail, review the live runtime code in `routes/`, `app/Services/Library/`, and the repository-facing verification materials in `docs/qa/`.

---

## Local setup

### Requirements
- PHP **8.4+**
- Composer
- Node.js **20.19+** (or Docker with `node:22`)
- npm
- Docker + Docker Compose
- PostgreSQL 16+

### 1) Configure environment
```bash
cp .env.example .env
```

Update local values in `.env` as needed, especially:
- `DB_*`
- `POSTGRES_*`
- `EXTERNAL_AUTH_LOGIN_URL`
- `API_KEY_21ST` (optional; only needed for the internal 21st integration)

### 2) Install dependencies
```bash
composer install
npm install
```

### 3) Start with Docker (recommended)
```bash
docker compose up --build -d app frontend-dev
```

Open the app at:
- `http://localhost`

### 4) Live-edit workflow
With Docker live sync enabled:
- changes under `resources/views/*`, `routes/*`, `app/*`, and `public/css/*` appear on refresh
- changes under `resources/js/*` and `resources/css/*` update through the Vite dev server

To restart the app stack:
```bash
docker compose up -d app frontend-dev
```

---

## Testing and QA

### Run the main automated checks
```bash
composer qa:ci
```

This runs:
- Laravel Pint style checks
- critical-path PHPUnit feature/API regression tests
- frontend production build verification

### Run the full Laravel test suite
```bash
composer test
```

### Run browser smoke tests
```bash
npm run test:e2e:install
npm run test:e2e
```

### Push readiness check
Before pushing changes, run:
```bash
composer qa:ci
```

---

## CI/CD

GitHub Actions is configured to:
- run on **push**, **pull request**, and manual dispatch
- scan for committed secrets
- run backend quality gates and regression tests
- generate PHPUnit/JUnit and coverage artifacts
- build the frontend
- run Playwright smoke checks against the public reader flow
- package release-ready source artifacts for manual download/review

See:
- `.github/workflows/ci.yml`
- `.github/workflows/release-package.yml`
- `docs/qa/`

---

## Repository structure

```text
app/                 Laravel application code
config/              runtime and integration configuration
database/            migrations, factories, seeders
resources/           Blade views, React/Vite frontend sources
routes/              web and API routes
tests/               PHPUnit feature/API tests + Playwright smoke tests
scripts/dev/         local QA, workflow, and maintenance automation
public/              public assets and entrypoint
docs/qa/             QA automation, CI/CD, verification reports, and evidence indexes
```

---

## Current project status

The repository is in an **active hardening and product-delivery phase**. Current verified areas include:
- homepage and public shell modernization
- critical catalog discovery behavior
- account/reader auth boundaries
- protected internal staff route access
- CI-ready regression automation and publication hardening

### Major next areas
- richer catalog and book-detail UX
- deeper metadata correction workflows for librarians/admins
- UDC-centered discovery expansion
- controlled digital material access
- reporting and analytics

---

## Auth boundary note

The CRM remains an **authentication/integration boundary**. It is used for login and related integration APIs, but it does not own the library product model and must not connect directly to the library database.

---

## QA and verification

Repository-facing QA and automation materials live in:
- `docs/qa/README.md`
- `docs/qa/test-scope.md`
- `docs/qa/quality-gates.md`
- `docs/qa/ci-cd.md`
- `docs/qa/evidence-index.md`
- `evidence/verification/`

---

## Contribution workflow

1. implement the required change on a focused branch
2. verify it locally with the project QA commands
3. run the QA gates
4. open a pull request

---

## Security and publication note

Do **not** commit `.env`, API keys, tokens, or local database credentials. Public-safe defaults live in `.env.example`, and CI includes a secret-scanning gate.

