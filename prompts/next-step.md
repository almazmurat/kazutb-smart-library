Start by reading:
- `AGENT_START_HERE.md`
- `.github/copilot-instructions.md`
- `project-context/00-project-truth.md`
- `project-context/01-current-stage.md`
- `project-context/06-current-focus.md`
- `project-context/98-product-master-context.md`
- `README.md`

Context:
The public site now has:
- Shared design system (shell.css) with variables, button states, utility classes
- Premium dark footer with branded layout
- 12 public routes including:
  - /for-teachers — dedicated teacher landing page with feature cards, syllabus workflow, FAQ
  - /discover — UDK-oriented thematic discovery with 9 knowledge areas and keyword deep-links to catalog
- Catalog supports URL deep-linking (?q= and ?sort= params)
- Homepage discovery cards wired to correct destinations (/for-teachers, /discover, /catalog)
- Auth navbar fixed: all pages use server-side session for consistent login/logout state
- CTA normalization across all shared-layout pages

What is ready for next steps:
- Backend operational workflows (circulation, reservations, returns)
- CRM-facing API hardening
- Data quality / stewardship workflows
- Internal admin and librarian capabilities
- Catalog search improvements (full-text, filters, relevance)
- Student-facing account features (reading history, favorites, recommendations)
- UDK data ingestion and real classification-based filtering (when data is available)

Refer to project-context/02-active-roadmap.md for priority ordering.
The frontend demo layer is now strong enough for leadership review.
Next work should focus on backend substance and operational depth.
