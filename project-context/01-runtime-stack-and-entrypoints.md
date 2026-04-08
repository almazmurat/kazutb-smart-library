# Runtime stack and entrypoints

## Runtime model
- Backend: Laravel 13
- UI: Blade views plus Vite-managed assets and React islands / SPA shell
- Database: PostgreSQL
- Runtime: Docker Compose via `docker-compose.yml`
- Main environment target: `http://10.0.1.8`

## Live runtime facts
- `app` serves the Laravel site on port `80`
- `frontend-dev` serves Vite/HMR on port `5173`
- `postgres` runs on port `5432`
- local/live-sync behavior depends on the repo being mounted into `/app`
- healthcheck target is `GET /api/v1/catalog-db?limit=1`

## Main public routes
- `/` — editorial homepage / library landing page
- `/catalog` — public catalog surface
- `/book/{isbn}` — book detail
- `/contacts` — about and contact page
- `/resources` — licensed resources and faculty support
- `/discover` — direction-based discovery page
- `/account` — authenticated reader cabinet
- `/login` — login page
- `/app/*` — SPA shell
- `/app/catalog` — URL-synced catalog filters over `/api/v1/catalog-db`
- `/for-teachers` — legacy path redirected to `/resources`

## Internal routes
- `/internal/dashboard`
- `/internal/review`
- `/internal/stewardship`
- `/internal/circulation`
- `/internal/ai-chat`

## Auth and integration runtime behavior
- authenticated library state lives in the server session under `library.user`
- CRM-backed login is configured in `config/services.php` → `external_auth`
- canonical reader catalog API is `GET /api/v1/catalog-db`
- internal AI assistant endpoints live under `/api/v1/internal/ai-assistant/*`
