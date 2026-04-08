# Tool Selection Justification

This file explains **why the actual tools in the repository were chosen** for Assignment 2, instead of treating them as random defaults.

| Tool | Why it was chosen here | Why it is defensible |
|---|---|---|
| PHPUnit | the repo already uses Laravel's native testing stack and `Tests\TestCase`, which makes session, JSON, routing, and controller tests straightforward and maintainable | no tool-switch tax, direct Laravel integration, easy CI execution |
| Playwright | the assignment needs browser-level proof for public flows such as homepage rendering, catalog controls, and login redirect behavior | real browser automation with traces, screenshots, and videos; stronger than unit-only UI claims |
| Laravel Pint | the project is Laravel-based and Pint is the ecosystem-default style gate | low-friction quality gate that is easy to run locally and in CI |
| GitHub Actions | the repository is hosted on GitHub and requires visible push/PR automation | native status checks, artifacts, and workflow version control make it easy to defend in a demo |
| Docker Compose | the local environment can vary, especially around PHP version support | gives a reproducible fallback when the host runtime is older than the repo requirement |

## Why not alternatives?
- **Pest** was not made the primary test runner because the repository is already structured around PHPUnit and Laravel's default test case pattern.
- **Cypress** was not selected for browser automation because Playwright gives strong headless CI support and richer trace artifacts for debugging.
- **External CI systems** such as Jenkins or GitLab CI were unnecessary because the project already lives on GitHub and GitHub Actions provides the required automation surface directly.

## Defense-ready explanation
A student can justify the stack in one sentence:

> *PHPUnit was used for backend and route/session automation because it fits Laravel natively, while Playwright was added for real-browser smoke coverage, and GitHub Actions enforces both in a version-controlled CI pipeline.*
