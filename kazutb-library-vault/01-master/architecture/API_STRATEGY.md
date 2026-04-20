# API_STRATEGY
> Derived from [[PROJECT_CONTEXT]] §2, §30, and §31.4

## Boundary principle
The library provides public REST APIs. CRM and other university systems may consume them, but the library always keeps a full equivalent internal UI.

## Design principles
- RESTful JSON responses
- pagination, filtering, and sorting on list endpoints
- consistent error format: `{ "error": "...", "message": "...", "code": "..." }`
- all write endpoints return the updated resource
- every write action must create an audit entry

## Integration safety
- no direct CRM access to the library database
- protected requests use server-validated token authentication
- token validation can be cached with a TTL, but authority still comes from the auth boundary

## Links
- [[PROJECT_CONTEXT]]
- [[AUTH_MODEL]]
- [[AUDIT_LOG]]
