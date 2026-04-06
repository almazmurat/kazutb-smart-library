Manual verification requirement:

At the end of the task, provide a strict human verification checklist for manual browser testing on the running environment.

The checklist must be practical and specific.

Include these sections:

## 1. Files changed
List the exact files that were created, updated, moved, or deleted.

## 2. Routes/pages affected
List the exact routes/pages impacted by the changes.

Use explicit URLs where possible, based on the running environment:
- `http://10.0.1.8/`
- `http://10.0.1.8/catalog`
- `http://10.0.1.8/login`
- `http://10.0.1.8/account`
- other exact URLs if relevant

## 3. Manual browser verification checklist
For each affected route/page, provide:
- exact URL to open
- exact actions to perform
- expected visible result
- expected error state if relevant
- expected auth behavior if relevant

Format each check like:

- URL:
- Actions:
- Expected result:
- Notes:

## 4. API/manual request checks if relevant
If the task affects APIs or data flows, include:
- exact endpoint
- method
- example request if needed
- what response/behavior to expect
- whether it can be tested through browser UI or needs curl/Postman

## 5. Runtime/build/deploy steps if needed
If the human operator must rebuild, restart, migrate, seed, clear cache, or run any command before testing, list the exact commands in order.

Example:
- `docker compose up -d --build`
- `docker compose restart`
- `php artisan migrate`
- `php artisan config:clear`

Only include commands that are actually needed.

## 6. Known environment limitations
State clearly:
- what could not be verified automatically
- what depends on local environment state
- what may fail because of missing services, auth dependencies, or container limitations

## 7. Smoke-check summary
End with a short "minimum manual smoke test" list:
- homepage
- catalog
- book detail
- login
- account
- any task-specific page

Important rules:
- Do not give vague advice like “check the UI”.
- Do not omit URLs.
- Do not omit expected results.
- Be concrete enough that the human can test quickly without reading the whole diff.