# Domain boundaries and integrations

## The library platform owns
- catalog, search, discovery, and UDC-centered navigation behavior
- reader-facing UX and session-aware account behavior
- teacher literature-selection and shortlist workflows for syllabi / educational programs
- stewardship, circulation, metadata correction, and future librarian/admin surfaces inside the new platform
- digital-material presentation, external-resource presentation, and access-rule enforcement
- PostgreSQL library data semantics and app-side services
- reporting-compatible library workflows where audit/compliance matters

## CRM owns
- external authentication / LDAP-backed login API
- CRM-side admin experiences that remain outside the library product
- consuming approved library integration endpoints over HTTP

## Legacy-system reality
- some real library processes still continue in the old system while the Laravel platform grows
- migration is therefore incremental, and planning must assume partial overlap, reconciliation, and operator retraining

## Hard boundary rule
CRM must not connect directly to the library database. All cross-system behavior goes through explicit API or service boundaries.

## Tooling is not runtime ownership
21st tooling, MCP servers, and Obsidian improve delivery and support work, but they do not own product truth or runtime behavior.
