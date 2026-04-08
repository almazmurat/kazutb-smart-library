# Quality Gates

## Primary local gate
Run:

```bash
composer qa:ci
```

This wrapper executes `scripts/dev/run-ci-gates.sh`, which performs:
1. Laravel configuration reset for a clean testing context
2. targeted `Pint` checks on the hardened critical-path files
3. critical-path PHPUnit feature/API regression tests
4. frontend production build verification with Vite

## Environment fallback behavior
The QA wrapper detects whether the host has **PHP 8.4+** available.
- if yes, it runs directly on the host
- if not, it falls back to the repository Docker runtime automatically

This makes the gate usable on development machines where the local PHP version is older than the Composer platform requirement.

## Browser smoke verification
Install the browser once locally:

```bash
npm run test:e2e:install
```

Then run:

```bash
npm run test:e2e
```

The Playwright configuration now falls back to the Docker-served application when host PHP is below the supported version.

## Coverage threshold enforcement
CI also runs:

```bash
php scripts/dev/check-coverage-threshold.php build/test-results/clover.xml 20
```

This enforces a minimum backend feature coverage threshold for the generated Clover report.
