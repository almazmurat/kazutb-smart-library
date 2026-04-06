Start by reading:
- `AGENT_START_HERE.md`
- `.github/copilot-instructions.md`
- `project-context/00-project-truth.md`
- `project-context/01-current-stage.md`
- `project-context/02-active-roadmap.md`
- `project-context/03-api-contracts.md`
- `project-context/04-known-risks.md`
- `project-context/05-agent-working-rules.md`
- `project-context/06-current-focus.md`
- `project-context/98-product-master-context.md`
- `README.md`
- `docs/developer/TARGET_OPERATING_MODEL.md`
- `docs/developer/RBAC_AND_CRM_BOUNDARY.md`
- `docs/developer/API_PUBLICATION_POLICY.md`
- `resources/views/for-teachers.blade.php`
- `resources/views/discover.blade.php`
- relevant catalog/search/book files
- relevant database/schema/migration/model files
- any current docs or notes related to UDK, metadata cleanup, barcode workflows, catalog structure, and thematic discovery

Context:
A teacher-facing UX layer and a first UDK-oriented thematic discovery layer have already been implemented.
However, the current UDK-oriented discovery is still only a product-facing discovery aid, not a real data-backed UDK model.

The old library data environment is currently undergoing:
- UDK enrichment,
- metadata cleanup,
- barcode assignment/normalization,
- bibliographic data correction.

This creates the right moment to prepare the real UDK/metadata support path for the new system.

Important:
- Do not build a huge speculative taxonomy engine in one step.
- Do not pretend the database already has complete UDK support if it does not.
- Do not break existing catalog, reader, or teacher-facing flows.
- Stay strict, repository-grounded, and data-aware.

Task:
Perform a strict UDK and metadata readiness audit, and prepare the first real implementation-ready path for UDK-backed discovery in the new library system.

Primary goals:
1. Understand what UDK-related support already exists in the codebase and data model.
2. Identify what metadata structures are already present or missing for real UDK-backed discovery.
3. Define the safest next implementation path from UX-level thematic discovery to actual data-backed UDK support.
4. If a very small safe implementation is clearly justified, make it; otherwise produce precise implementation-ready docs and mappings.

What to do:

## 1. Audit current UDK/data support
Inspect:
- models
- migrations
- database schema references
- import/data handling code
- catalog search/book detail logic
- tests
- docs related to metadata or bibliographic structure

Identify:
- whether UDK fields already exist anywhere,
- whether bibliographic/classification fields are modeled,
- whether catalog search can realistically support UDK-aware filtering later,
- where metadata gaps exist.

## 2. Audit bibliographic and copy-level metadata readiness
Map the current/new system’s ability to represent:
- bibliographic record
- document
- copy/item
- inventory number
- barcode
- storage sigla
- metadata fields relevant to classification/discovery
- local vs external resource distinction

Be explicit about what is:
- already represented
- partially represented
- missing
- unclear / needs source-data confirmation

## 3. Relate UDK to current product surfaces
Analyze how future real UDK support could connect to:
- `/discover`
- `/catalog`
- book detail pages
- teacher-facing resource selection
- future syllabus-oriented discovery

Clarify what should be:
- pure UX layer,
- search/filter layer,
- metadata layer,
- import/migration layer.

## 4. Define the safest implementation path
Produce a practical staged plan such as:
- Stage 1: support/display UDK metadata where available
- Stage 2: support high-level UDK grouping/filtering
- Stage 3: support thematic discovery backed by actual UDK mappings
- Stage 4: support richer teacher/course resource discovery

This plan must be implementation-ready, not vague.

## 5. Create/update concrete deliverables
Create or update only the files that are truly justified, such as:
- `docs/developer/UDK_AND_METADATA_READINESS_AUDIT.md`
- `docs/developer/UDK_DISCOVERY_IMPLEMENTATION_PLAN.md`
- `docs/developer/BIBLIOGRAPHIC_AND_COPY_MODEL_MAP.md`

If a tiny safe code/schema change is obviously useful and low-risk, you may implement it.
Otherwise stay focused on audit + implementation path.

## 6. Verification
Verify all factual/code claims against the actual repository contents.
Be honest about what is inferred vs directly confirmed.

Output format:
1. Executive summary
2. Current UDK/data support found
3. Bibliographic/copy metadata readiness
4. How UDK should connect to current product surfaces
5. Recommended staged implementation path
6. Files created/updated
7. Verification performed
8. Remaining unknowns / source-data requirements
9. Next best step

Definition of done:
- There is a clear repository-grounded picture of real UDK readiness.
- The path from current thematic discovery UX to real UDK-backed discovery is explicit.
- Metadata/classification gaps are identified clearly.
- The next implementation step becomes concrete and safe.