# KazUTB Smart Library – Copilot Instructions

## Project identity
This repository is the future core library platform for KazUTB.
It is not a demo, not just a frontend shell, and not just a replacement UI for an old system.

The goal is to build a real, modern, operational library platform that can eventually replace the old library environment.

## Core domain truth
- Library domain logic stays in the library system.
- CRM is an integration/admin/auth client, not the owner of library domain truth.
- The library system keeps its own backend, database, logic, APIs, and operational workflows.
- The library system must remain capable of supporting its own admin and librarian workflows, even if CRM also builds its own panels using library APIs.
- Do not reduce this system to “only a reader frontend” unless explicitly directed.

## Authentication and CRM truth
- Login UX remains inside the library system.
- CRM provides LDAP / AD-backed authentication through API.
- The library sends credentials to CRM API and receives token/user data back.
- There is no redirect to CRM UI during login.
- CRM must not connect directly to the library database.
- Library APIs are the integration boundary for CRM-side functionality.

## Current execution priorities
Default priority order:
1. backend and database
2. operational library workflows
3. data quality / stewardship workflows
4. internal admin and librarian capabilities
5. CRM-facing API hardening and publication
6. frontend expansion
7. AI/recommendation/advanced features
8. non-functional polishing

## Data and stewardship truth
- Data has already been migrated to PostgreSQL.
- Data quality work is not finished.
- Manual cleanup plus AI-assisted correction is a real project strategy.
- Preserve data integrity, auditability, reporting compatibility, and library meaning.
- Do not silently broaden mutation scope on critical entities.

## Scope discipline
- Do not drift into unrelated frontend, AI/chat, or speculative product work unless explicitly asked.
- Do not turn domain workflows into generic CRUD without justification.
- Prefer narrow, safe, verifiable implementation slices.
- Internal-only workflows can evolve before CRM-facing publication.
- CRM-facing API expansion must be conservative and explicit.

## Domain constraints to respect
- Preserve catalog, document, copy, reservation, circulation, and reporting integrity.
- Respect the real structure of the fund:
  - university vs college
  - economic vs technological
  - physical library points / branches
- Respect external licensed resources and digital material restrictions.
- Do not treat all records as one flat undifferentiated pool.

## Digital materials constraints
- Controlled viewer and restricted access are required for protected digital materials.
- Do not assume unrestricted download is allowed.
- Access rules depend on role, authorization, and license constraints.

## Coding expectations
Before major work:
- read AGENT_START_HERE.md
- read relevant project-context files
- read the product master context file

Prefer:
- existing project service/controller/middleware/test patterns
- narrow operationally useful slices
- explicit error handling
- audit-aware mutation logic
- route + test + runtime verification when practical

## Verification expectations
- Add targeted tests for meaningful backend changes.
- Prefer real container/runtime verification where practical.
- State environment limitations honestly.
- Do not claim verification that did not actually happen.

## Internal vs CRM-facing
- Internal operational workflows may be built first.
- CRM-facing APIs should be published only when contract and governance are mature enough.
- Do not casually expose risky mutation surfaces to CRM.

## Commit discipline
- Keep commits atomic and scoped.
- Do not mix unrelated refactors into an operational backend step.
- Update docs when behavior meaningfully changes.

## Required source-of-truth reads
For any non-trivial backend or architectural task, read:
- AGENT_START_HERE.md
- project-context/00-project-truth.md
- project-context/01-current-stage.md
- project-context/06-current-focus.md
- project-context/98-product-master-context.md

If convenience conflicts with domain truth, follow the project-context files.