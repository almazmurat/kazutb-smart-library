# KazUTB Digital Smart Library

A production-oriented digital library platform built with Laravel, PostgreSQL, Blade/React/Vite, and Docker Compose.

The project is intended to be a real university library system for public discovery, authenticated reader workflows, staff operations, controlled digital materials, and reporting-compatible administration.

---

## Product overview

The repository currently covers:
- public catalog and book-detail discovery
- reader account and reservation flows
- teacher-oriented discovery and shortlist workflows
- internal review, stewardship, and circulation surfaces
- controlled digital-material handling and licensed external resources
- a bounded CRM authentication and integration layer

## Architecture summary

- the library platform owns domain behavior and operational workflows
- the CRM remains an auth and integration boundary, not the domain owner
- PostgreSQL is the primary application data store
- Blade and Vite/React are used together for public and richer application surfaces

---

## Technology stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Frontend | Blade, React, Vite |
| Database | PostgreSQL |
| Runtime | Docker Compose |
| Testing | PHPUnit feature/API tests, Playwright smoke tests |
| CI/CD | GitHub Actions |

---

## Local setup

### Requirements
- PHP 8.3+
- Composer
- Node.js 20+
- npm
- Docker and Docker Compose

### 1) Configure environment
```bash
cp .env.example .env
```

### 2) Install dependencies
```bash
composer install
npm install
```

### 3) Start the recommended local stack
```bash
docker compose up --build -d app frontend-dev
```

The application is then available at:
- http://localhost

### 4) Useful restart command
```bash
docker compose up -d app frontend-dev
```

---

## Verification and QA

Primary verification commands:

```bash
composer qa:ci
composer test
npm run test:e2e:install
npm run test:e2e
```

Use the QA documentation surface here:
- [docs/qa/README.md](docs/qa/README.md)
- [docs/qa/qa-test-strategy-document.md](docs/qa/qa-test-strategy-document.md)
- [docs/qa/quality-gates.md](docs/qa/quality-gates.md)
- [docs/qa/ci-cd.md](docs/qa/ci-cd.md)
- [docs/qa/evidence-index.md](docs/qa/evidence-index.md)

---

## Obsidian vault memory sync

This repo now includes a vault sync helper that can grow your Obsidian graph with linked Markdown nodes from Copilot chat activity and repository changes.

Useful commands:

```bash
composer dev:session-snapshot
composer dev:vault-sync
composer dev:vault-watch
composer dev:install-vault-hooks
```

Optional environment variables in your local setup:

```bash
OBSIDIAN_VAULT_ROOT=/path/to/kazutb-library-vault
OBSIDIAN_SYNC_INTERVAL=10
```

Behavior summary:
- captures recent Copilot user/assistant transcript content
- creates timestamped notes under the vault inbox
- links new notes to master hubs such as MASTER_CONTEXT, CURRENT_STATE, DECISIONS, NEXT_ACTIONS, SESSION_MEMORY, and TASK_LOG
- can run continuously as a watcher and also after commits, merges, and checkouts via git hooks
- the installed Git hooks also append commit and checkout events into the project vault memory so the second brain stays aligned with repository activity
- commits with keywords such as feat, fix, auth, rbac, migration, schema, breaking, or decision are also mirrored into the vault decision log automatically
- infrastructure-sensitive changes in migrations, routes, models, controllers, and views also prepend a Last changed state block in the vault snapshot

If the real vault path is not mounted on the Linux host, the sync falls back to a local mirror under `artifacts/obsidian/vault-mirror`.

---

## Repository structure

```text
app/                 Laravel application code
config/              runtime and integration configuration
database/            migrations, factories, seeders
resources/           Blade views and frontend assets
routes/              web and API routes
tests/               PHPUnit and Playwright coverage
scripts/dev/         local QA and maintenance helpers
public/              public assets and entrypoint
docs/qa/             retained QA and verification docs
```

---

## Current delivery focus

The current reset has already landed its anchor surfaces: the public homepage, the library-hosted secure access flow, and the member dashboard. The next delivery depth is internal staff experience, catalog tightening, and controlled access refinement.

The repo is in an active hardening and delivery phase, with ongoing work around:
- catalog and discovery UX
- staff-side stewardship depth
- digital-material access control
- reporting and analytics readiness

---

## Security note

Do not commit `.env`, API keys, tokens, or local database credentials. Use `.env.example` for safe defaults and local setup guidance.

