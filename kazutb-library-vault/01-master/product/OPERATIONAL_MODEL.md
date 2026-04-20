# OPERATIONAL_MODEL
> Derived from [[PROJECT_CONTEXT]] §2

## Core model
The library platform is the primary full-domain operational system. It owns guest, member, librarian, and admin experiences; library business logic; the PostgreSQL data model; and the full API layer.

## What CRM owns
CRM acts as:
- authentication provider via LDAP/AD validation
- a parallel university ecosystem
- a consumer of library APIs

## What CRM does not own
CRM is not where library logic lives, does not connect directly to the library database, and does not replace the internal library panels.

## API boundary rule
The library provides REST APIs for integrations, but must always maintain equivalent first-class UI inside the library product itself.

## Links
- [[PROJECT_CONTEXT]]
- [[API_STRATEGY]]
- [[AUTH_MODEL]]
