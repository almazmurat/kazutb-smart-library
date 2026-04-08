# What the library owns vs what CRM and 21st own

## Library platform owns
- catalog, search, book discovery behavior, and UDC-centered thematic navigation
- reader account behavior and reader-facing UX
- teacher literature discovery / shortlist workflows
- circulation, reservations, and stewardship workflows
- librarian/admin operational surfaces, reporting, and library APIs
- digital-material and external-resource presentation rules
- PostgreSQL data semantics and app-side services

## Operational data truth
- inventory number must not be duplicated across books/copies
- barcode must not be duplicated across books/copies
- this uniqueness is **global across the whole system/database**, not scoped only to a fund or branch, because university and college records live in one shared PostgreSQL environment
- if a librarian tries to reuse an existing inventory number or barcode, the system should clearly say the value is already assigned and reject the save
- operator attribution (which librarian changed data) is part of audit/stewardship truth
- KSU / invoice-based acquisition reporting remains a real library compliance requirement

## CRM owns
- external authentication flow / LDAP-backed login API
- CRM-side admin and operational UI on its own side
- consuming approved library integration endpoints

## Explicit rule
CRM must not connect directly to the library database.
All integration must happen through HTTP API boundaries.

## 21st / MCP / Obsidian own
These are tooling layers, not runtime domain owners:
- `@21st-sdk/*` and internal AI chat are optional staff tooling
- MCP servers are developer/operator tools
- Obsidian is the long-term memory and knowledge graph

## Why this matters
This boundary prevents future sessions from drifting into “CRM owns the product” or “tooling files are the source of runtime truth”.
