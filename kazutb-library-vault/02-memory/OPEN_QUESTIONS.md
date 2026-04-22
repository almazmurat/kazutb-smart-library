# Open Questions — KazUTB Library Platform

> These are unresolved uncertainties that need answers before implementation.
> When a question is resolved, move it to [[DECISIONS]] and delete it from here.

## Format
Each entry: Priority (HIGH/MED/LOW) | Question | What blocks on this | Who should answer

---

- **HIGH** | How broad should the first admin shell release be: overview only, or overview plus news, feedback, and reporting in the same wave? | Implementation sequencing for the internal layer | Product owner + delivery lead
- **HIGH** | How much stewardship automation should be built before wider UI expansion: anomaly detection only, or bulk correction helpers as well? | Scope of the cleanup panel and librarian workflow depth | Library operations + admin
- **MED** | Which operational panels may CRM mirror through API without weakening the library as the authoritative domain system? | Integration contracts and boundary safety | Product owner + CRM team
- **MED** | When should transport hardening and broader security polish move from internal-LAN tolerance to production-grade enforcement? | Deployment readiness and integration planning | Admin + infrastructure owner
- **HIGH** | Should `/leadership` and `/rules` ship as standalone public routes in the next public wave, or be staged as sections inside existing `/about`/`/contacts` first and extracted later? | Public route decomposition and implementation sequencing | Product owner + delivery lead
- **HIGH** | What is the first release boundary for the distinct events module: list + detail only (`/events`, `/events/{id}`), or include calendar filters and date navigation in v1? | Phase 3 public sequencing and scope control | Product owner + delivery lead
- **MED** | Should location/map + room-level fund wayfinding (`1/200`, `1/202`, `1/203`) be integrated into `/contacts` or introduced as a dedicated public location surface? | Information architecture for contact/location layer | Product owner + library operations
- **MED** | Where should the "Latest Arrivals" block draw from in v1 (catalog ingest chronology, curated librarian picks, or hybrid)? | Homepage/discover implementation semantics | Product owner + library operations

- **LOW** | Should watcher summaries roll into daily notes later? | Future vault maintenance design | Manual session follow-up

- **HIGH** | Вопрос? | Что блокирует | Manual session follow-up

## 2026-04-21 — Architecture audit open questions

- **HIGH** | Should `/internal/*` → `/librarian/*` migration run in a single cycle with 301s, or in two cycles (dual-path first, remove `/internal/*` second)? | Phase 1 of [[DELIVERY_ROADMAP]] sequencing | Product owner + delivery lead
- **HIGH** | Should `/account` rename to `/dashboard` happen immediately with 301, or wait until member sub-routes exist? | Phase 2 of [[DELIVERY_ROADMAP]] | Product owner
- **HIGH** | Should admin users inherit every librarian route, or only selected librarian surfaces? | Middleware chain for `/librarian/*` | Product owner + library ops
- **MED** | Which admin module should be wired to real data first in Phase 6 (users, logs, news, feedback, reports, settings)? | Phase 6 ordering | Delivery lead
- **MED** | Should `/internal/ai-chat` survive the librarian shell migration as an experimental feature, or be removed? | Scope of new `/librarian/*` namespace | Product owner
- **HIGH** | Is the scientific repository module (PROJECT_CONTEXT §20) in scope immediately after Phase 2, or deferred one cycle? | Phase 4 timing of [[DELIVERY_ROADMAP]] | Product owner + research office
- **MED** | What depth of audit-log emission is required from mutating controllers before Phase 6 proceeds? | `AuditLogService` contract + retention | Admin + security
- **LOW** | Should the legacy `reader.blade.php` and `/book/{isbn}/read` route be removed now that `/digital-viewer/{id}` is canonical? | Legacy cleanup wave | Manual session follow-up

## Links
- [[PROJECT_CONTEXT]]
- [[DECISIONS]]
- [[CURRENT_STATE]]
- [[DELIVERY_ROADMAP]]


