# Git Workflow Policy

## Purpose

This project follows a clean, review-friendly git workflow designed for iterative AI-assisted development.

## Rules

1. Never commit directly to `main`.
2. Create a feature branch from `main` for each task scope.
3. Use clear branch names, for example:
   - `feature/phase1-core-foundation`
   - `feature/search-fts-phase`
   - `feature/rbac-branch-isolation`
4. Make atomic commits grouped by one logical change.
5. Commit messages should be concise and changelog-friendly.
6. Keep commits small enough for easy revert and review.

## Recommended Commit Message Pattern

- `docs: add multilingual and branch ownership requirements`
- `backend: harden config and health checks`
- `db: add institution scope and library branch models`
- `users: add CRUD and audited role updates`
- `frontend: prepare i18n structure for kk ru en`

## Pull Request Guidelines

1. Open PR from feature branch into `main`.
2. Include summary of architectural decisions.
3. Include schema and endpoint impact section.
4. Include build and test results.
5. Keep PR scope bounded to one milestone whenever possible.
