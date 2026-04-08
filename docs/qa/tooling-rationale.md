# Tooling Rationale

This file explains why the repository uses its current QA toolchain and why that choice is appropriate for a production-style Laravel codebase.

| Tool | Why it was chosen here | Why it fits this repository |
|---|---|---|
| PHPUnit | the codebase already uses Laravel’s native testing stack and `Tests\TestCase` | direct framework integration, low maintenance overhead, and easy CI execution |
| Playwright | browser-level verification is needed for public reader flows such as homepage rendering, catalog controls, and login redirects | real browser automation with traces, screenshots, and consistent headless CI support |
| Laravel Pint | the project is Laravel-based and benefits from the ecosystem-default style gate | fast, low-friction formatting enforcement both locally and in CI |
| GitHub Actions | the repository is hosted on GitHub and needs visible push/PR automation | native status checks, artifacts, and version-controlled workflow definitions |
| Docker Compose | contributor environments can vary, especially around PHP version support | reproducible local fallback when the host runtime is older than the repository requirement |

## Why not alternatives?
- **Pest** was not made the primary runner because the existing suite is already structured cleanly around PHPUnit.
- **Cypress** was not selected because Playwright offers stronger headless CI support and richer debugging traces for this stack.
- **External CI systems** such as Jenkins or GitLab CI were unnecessary because GitHub Actions already provides the required verification surface where the repository lives.

## Summary
The current stack minimizes tool-switch overhead while preserving real backend, browser, style, and release verification.
