# Open Questions — KazUTB Library Platform

> These are unresolved uncertainties that need answers before implementation.
> When a question is resolved, move it to [[DECISIONS]] and delete it from here.

## Format
Each entry: Priority (HIGH/MED/LOW) | Question | What blocks on this | Who should answer

---

## Phase 3 public cluster questions (aligned to 2026-04-22 decomposition)

### Cluster B — Informational institutional surfaces
- **HIGH** | Should `/leadership` and `/rules` ship as standalone routes, or be staged as sections inside `/about`/`/contacts` first and extracted later? | Cluster B.1–B.2 implementation scope | Product owner + delivery lead
- **MED** | Should location/map + room-level fund wayfinding (`1/200`, `1/202`, `1/203`) be integrated into `/contacts` or introduced as a dedicated `/location` surface? | Cluster B.3 IA decision | Product owner + library operations

### Cluster C — Events module
- **HIGH** | What is the v1 scope boundary for events: list + detail only (`/events`, `/events/{slug}`), or include calendar filters and date navigation in v1? | Cluster C.1–C.2 scope + Stitch spec | Product owner + delivery lead

### Cluster D — Homepage enhancement
- **MED** | Where should the "Latest Arrivals" block draw from in v1: catalog ingest chronology, curated librarian picks, or hybrid? | Cluster D.1 implementation semantics | Product owner + library operations

---

## Standing operational questions

- **HIGH** | How much stewardship automation should be built before wider UI expansion: anomaly detection only, or bulk correction helpers as well? | Scope of cleanup panel | Library operations + admin
- **MED** | Which operational panels may CRM mirror through API without weakening the library as the authoritative domain system? | Integration contracts | Product owner + CRM team
- **MED** | When should transport hardening and broader security polish move from internal-LAN tolerance to production-grade enforcement? | Deployment readiness | Admin + infrastructure owner

## Phase 6 + later questions

- **MED** | Which admin module should be wired to real data first in Phase 6 (users, logs, news, feedback, reports, settings)? | Phase 6 ordering | Delivery lead
- **MED** | Should `/internal/ai-chat` survive the librarian shell migration as an experimental feature, or be removed? | Scope of `/librarian/*` namespace | Product owner
- **HIGH** | Is the scientific repository module (PROJECT_CONTEXT §20) in scope immediately after Phase 3, or deferred one cycle? | Phase 4 timing | Product owner + research office
- **MED** | What depth of audit-log emission is required from mutating controllers before Phase 6 proceeds? | `AuditLogService` contract + retention | Admin + security
- **LOW** | Should the legacy `reader.blade.php` and `/book/{isbn}/read` route be removed now that `/digital-viewer/{id}` is canonical? | Legacy cleanup wave | Manual session follow-up
- **LOW** | Should watcher summaries roll into daily notes later? | Future vault maintenance design | Manual session follow-up

## Links
- [[PROJECT_CONTEXT]]
- [[DECISIONS]]
- [[CURRENT_STATE]]
- [[DELIVERY_ROADMAP]]

