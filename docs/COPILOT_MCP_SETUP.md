# Minimal MCP Setup For This Repository

## Goal
Keep MCP usage small and practical: improve context quality, repo operations, and framework/doc accuracy without tool sprawl.

## Required Baseline
- GitHub Copilot Chat/CLI in this repository.
- Repository-level instructions from .github/copilot-instructions.md.

## High-Value MCP Servers
1. GitHub MCP (built-in for Copilot CLI where available)
- Use for: issues, pull requests, review comments, labels, and status checks.
- Why: keeps workflow and review context close to code changes.

2. Context7 MCP (optional, recommended)
- Use for: up-to-date Laravel, PHPUnit, Vite, and package documentation.
- Why: avoids stale memory and reduces framework API mistakes.

## Later (Only If Needed)
- Database MCP: useful when repeatedly validating schema/query behavior across migrations and stewardship workflows.
- API contract MCP: useful if integration contract test surface grows.
- Internal docs MCP: useful if project-context and docs become much larger and hard to navigate manually.

## Practical Setup Steps
1. Confirm Copilot CLI auth is active.
2. Keep GitHub integration enabled in Copilot CLI.
3. Add Context7 MCP only if developers frequently need fresh framework docs during implementation.
4. Keep server list small; remove unused MCP servers quarterly.

## Safety Rules
- Do not expose secrets in MCP configs.
- Prefer user-level MCP config for personal tools.
- Add repo-local MCP config only when team-wide reproducibility is required and secrets are not embedded.

## Suggested Working Pattern
- Start with repository context files.
- Use GitHub MCP for issue/PR state.
- Use Context7 MCP only when documentation certainty is needed.
- Finish with focused tests and clean commit boundaries.
