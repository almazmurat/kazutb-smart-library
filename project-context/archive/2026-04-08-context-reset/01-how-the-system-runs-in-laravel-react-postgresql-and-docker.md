# How the system runs in Laravel, React, PostgreSQL, and Docker

## Runtime stack
- Backend: Laravel 13
- UI: Blade views plus Vite-managed assets and React islands / SPA shell
- Database: PostgreSQL
- Container runtime: Docker Compose (`docker-compose.yml`)

## Important runtime facts
- `app` container serves the site on port `80`
- `frontend-dev` serves the Vite live-reload endpoint on port `5173`
- `postgres` runs on port `5432`
- default app URL is `http://10.0.1.8`
- Docker live-sync mode bind-mounts the repo into `/app`; Blade/PHP/public CSS changes appear on refresh, while SPA JS/CSS changes flow through Vite HMR
- healthcheck targets `GET /api/v1/catalog-db?limit=1`

## Main user-facing routes
- `/` — landing page
- `/catalog` — catalog view
- `/book/{isbn}` — book detail
- `/contacts` — library-about and contact page
- `/resources` — electronic resources and faculty-support page
- `/discover` — direction-based discovery page
- `/account` — personal cabinet for authenticated reader
- `/login` — login page
- `/app/*` — React router shell
- `/app/catalog` — SPA catalog surface with URL-synced reader filters
- `/for-teachers` — legacy route that now redirects to `/resources`

## Internal/staff routes
- `/internal/dashboard`
- `/internal/review`
- `/internal/stewardship`
- `/internal/circulation`
- `/internal/ai-chat`

## Auth/runtime behavior
- authenticated user state lives in server session under `library.user`
- CRM-backed login is configured through `config/services.php` → `external_auth`
- the main public Blade routes support lightweight locale switching with `?lang=kk|ru|en`
- canonical reader catalog API is `GET /api/v1/catalog-db` and currently supports `q`, `language`, `year_from`, `year_to`, `available_only`, and `sort`
- internal AI assistant uses protected Laravel endpoints under `/api/v1/internal/ai-assistant/*`
