# KazUTB Smart Library

Цифровая умная библиотека KazUTB — новая операционная платформа библиотеки университета, построенная на Laravel 13 + PostgreSQL + React.

**Stage**: Advanced prototype, frontend demo-ready, transitioning to operational platform.

---

## What this is

A real library platform, not a demo or interface refresh. It is intended to replace the legacy library environment and support:
- public catalog, search, and book detail UX (12 public routes, shared design system)
- teacher-facing academic resource discovery and syllabus support
- thematic discovery by knowledge areas with catalog deep-linking
- reader account and reservation flows
- internal librarian and staff workflows (circulation, review, stewardship)
- CRM integration boundary (reservation read/approve/reject)
- data quality stewardship workflows
- future reporting, digital materials, and analytics layers

See [`project-context/00-project-truth.md`](project-context/00-project-truth.md) for full product truth.

---

## Public routes

| Route | Description |
|-------|-------------|
| `/` | Homepage with discovery cards, catalog preview, hero |
| `/catalog` | DB-backed catalog search with filters (supports `?q=` and `?sort=` deep-links) |
| `/book/{isbn}` | Book detail page |
| `/login` | Library login (CRM-proxied auth) |
| `/account` | Reader personal cabinet (loans, reservations, profile) |
| `/for-teachers` | Teacher-facing landing: feature cards, syllabus workflow, FAQ |
| `/discover` | Thematic discovery: 9 knowledge areas with keyword chips → catalog |
| `/services` | Library services overview |
| `/resources` | Electronic resources and databases |
| `/about` | About the library |
| `/contacts` | Contact information |
| `/news` | News and events |

---

## Tech stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.3+, Laravel 13 |
| Database | PostgreSQL 18 |
| Frontend | Laravel Blade + React 19 (SPA at `/app/*`) |
| Bundler | Vite 8 |
| CSS | Tailwind CSS 4 |
| Auth | Session + CRM API (LDAP/AD proxy) + Sanctum |
| Deployment | Docker (php-fpm + Nginx + Supervisor) |
| CI | GitHub Actions (`.github/workflows/ci.yml`) |

---

## Quick start

```bash
# 1. Install dependencies
composer setup          # composer install + .env + key:generate + migrate + npm build

# 2. Start dev environment (server + queue + logs + Vite HMR)
composer dev

# App: http://localhost:8000
# Vite HMR: http://localhost:5173
```

### Docker

```bash
docker compose up --build -d
docker compose exec -T app php artisan migrate
```

---

## Key commands

```bash
# Tests
composer test                          # all tests
composer test:internal                 # circulation + copy + review
composer test:reservation-core         # reservation + circulation
composer test:integration-reservations # CRM integration tests
composer test:stewardship              # stewardship tests

# Lint / format
./vendor/bin/pint                      # format
./vendor/bin/pint --test               # check only

# Dev checks
composer dev:check                     # environment check
composer dev:session-start             # pre-session checklist
```

---

## Where truth lives

| What | Where |
|------|-------|
| Project truth and domain ownership | [`project-context/00-project-truth.md`](project-context/00-project-truth.md) |
| Current platform stage | [`project-context/01-current-stage.md`](project-context/01-current-stage.md) |
| Active roadmap | [`project-context/02-active-roadmap.md`](project-context/02-active-roadmap.md) |
| API contracts and boundaries | [`project-context/03-api-contracts.md`](project-context/03-api-contracts.md) |
| Known risks | [`project-context/04-known-risks.md`](project-context/04-known-risks.md) |
| Agent and working rules | [`project-context/05-agent-working-rules.md`](project-context/05-agent-working-rules.md) |
| Current execution focus | [`project-context/06-current-focus.md`](project-context/06-current-focus.md) |
| Deep product context | [`project-context/98-product-master-context.md`](project-context/98-product-master-context.md) |

---

## For Copilot / AI agents

**Always read first**: [`AGENT_START_HERE.md`](AGENT_START_HERE.md)

Agent startup order is defined there. Do not skip it.

Repo structure and normalization rules: [`docs/developer/REPO_NORMALIZATION_PLAN.md`](docs/developer/REPO_NORMALIZATION_PLAN.md)

Developer workflow: [`docs/developer/AI_WORKFLOW.md`](docs/developer/AI_WORKFLOW.md)

Prompt templates:
- CLI prompts: [`prompts/`](prompts/)
- VS Code Copilot Chat: [`.github/prompts/`](.github/prompts/)

---

## Integration notes

- CRM is the **auth provider** and integration client — not the domain owner.
- Library platform owns the domain model, reader UX, and internal workflows.
- CRM integration boundary: `/api/integration/v1` (reservation read + approve/reject).
- Scope is intentionally frozen — do not expand without explicit decision.

See [`docs/integration-api-contract.json`](docs/integration-api-contract.json) for the full integration API spec.
