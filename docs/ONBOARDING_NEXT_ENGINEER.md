# Onboarding — Next Engineer

This note is the short repo-focused onboarding guide for the next developer.

## 1. Read the core entry docs
Start here:
- [README.md](../README.md)
- [docs/qa/README.md](qa/README.md)
- [docs/design-exports/canonical-design-map.md](design-exports/canonical-design-map.md) if design context is needed

## 2. Set up the environment
```sh
cp .env.example .env
composer install
npm install
```

Fill local environment values as needed in `.env`.

## 3. Start the recommended local stack
```sh
docker compose up --build -d app frontend-dev
```

## 4. Run the baseline verification commands
```sh
composer qa:ci
npm run test:e2e:install
npm run test:e2e
```

## 5. Know the repository shape
- `app/`, `routes/`, `config/` — application and runtime logic
- `resources/` and `public/` — UI and public assets
- `tests/` — backend and browser verification
- `docs/qa/` — retained QA and evidence documentation
- `scripts/dev/` — local QA and support tooling

## 6. Working rules
- keep changes scoped and verifiable
- prefer repo truth over stale notes or chat summaries
- do not commit secrets or machine-local credentials
- verify relevant behavior before opening or merging changes

