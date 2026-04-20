# TECH_STACK
> Derived from [[PROJECT_CONTEXT]] §31

| Layer | Technology |
|---|---|
| Backend | Laravel |
| Frontend | Blade templates with HTML/CSS/JS |
| Database | PostgreSQL |
| Authentication | CRM bearer-token validation against AD |
| Storage | Local or S3-compatible protected storage |
| Search | PostgreSQL full-text search now, Elasticsearch later |
| Queue | Laravel Queue |
| Cache | Redis |

## Engineering note
The architecture is production-oriented now and should remain compatible with future search, mobile, and advanced digital-viewer upgrades.

## Links
- [[PROJECT_CONTEXT]]
- [[API_STRATEGY]]
- [[FILE_STORAGE_MODEL]]
