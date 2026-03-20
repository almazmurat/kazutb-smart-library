# Phase 1 Cleanup Plan - Highest-Priority Data Quality Issues

Date: 2026-03-20  
Scope: planning/workflow design only (no ingestion, no destructive updates)  
Source baseline: reconstructed PostgreSQL state + profiling report

## Scope Boundaries

Included issue families only:

1. Orphan inventory rows
2. ISBN normalization and duplicate ISBN groups
3. Missing author links
4. Language code normalization and suspected language mismatches

Explicitly excluded in this phase:

- Architecture redesign
- Bulk destructive cleanup in core tables
- Any mutation of raw source mirrors

## Operating Principles for Phase 1

- Non-destructive by default: write proposed fixes into staging tables first.
- Human-in-the-loop where ambiguity exists.
- Keep source traceability for every proposal:
  - `legacy_doc_id`, `legacy_inv_id`, source payload snapshots/hashes, run ID, rule ID.
- Promote only approved rows to a controlled patch step.
- Preserve originals in `raw.*` indefinitely; do not rewrite raw mirrors.

## Current Baseline (From Profiling)

- Orphan copy rows (`core.book_copies.document_id IS NULL`): 170
- Duplicate ISBN groups: 1,010
- Documents in duplicate ISBN groups: 2,278
- Missing ISBN: 794
- ISBN bad-format bucket: 198
- Documents missing author links: 663
- Language mismatch heuristics:
  - `rus` with Kazakh-heavy script: 1,558
  - `kaz` without Kazakh-script evidence: 473
  - `eng/ger` with Cyrillic title: 71
  - `rus/kaz` with Latin-only title: 39
  - plus non-canonical code variants (e.g., `Kaz`, `kaz; rus`, `rus; kaz`)

## Shared SQL Workflow Pattern (All Families)

## 1) Register cleanup run

Create one run record before any extraction:

```sql
INSERT INTO migration.import_batches (id, source_system, source_database, status, notes, metrics)
VALUES (gen_random_uuid(), 'cleanup-phase1-plan', 'kazutb_library', 'IN_PROGRESS',
        'Phase 1 cleanup planning + candidate staging', '{}'::jsonb)
RETURNING id;
```

Use returned `batch_id` in every staging/review row.

## 2) Extract candidates into staging (read-only from core)

Never edit core directly in discovery step. Insert candidate rows into dedicated `migration.cleanup_p1_*` tables.

## 3) Automated scoring/proposals

Populate `proposed_action`, `confidence_score`, `rule_code`, `auto_eligible`.

## 4) Human review queue

Route non-auto-eligible or low-confidence rows to `review.cleanup_p1_decisions`.

## 5) Controlled patch script (later execution step)

Only apply rows that are explicitly approved and still pass guard checks.

## 6) Audit and rollback safety

Before any eventual update, write `before` snapshots to patch log table. Rollback is performed by replaying `before_payload`.

---

## Issue Family 1 - Orphan Inventory Rows

### Exact problem definition

A row in `core.book_copies` has a valid `legacy_doc_id` but unresolved canonical link (`document_id IS NULL`).

### Affected row count

- 170 rows

### Example rows

Sample from current state:

- `legacy_inv_id=39`, `legacy_doc_id=5`, `inventory_number=91667`, `document_id=NULL`
- `legacy_inv_id=42885`, `legacy_doc_id=8820`, `inventory_number=125764`, `document_id=NULL`
- `legacy_inv_id=44103`, `legacy_doc_id=9116`, `inventory_number=120250`, `document_id=NULL`

### Proposed cleanup strategy

1. Build candidate mapping from orphan `legacy_doc_id` to `core.documents.id` using deterministic keys.
2. Mark rows as:
   - `AUTO_LINK` when exactly one canonical match exists.
   - `REVIEW_LINK` when multiple matches exist.
   - `UNRESOLVED` when no match exists.
3. Keep unresolved rows in quarantine state for circulation policy decisions.

### Safe automation

- Fully automatic when one and only one target `core.documents` row exists for the orphan `legacy_doc_id`.

### Human review needed

- One-to-many matches (candidate collisions).
- No candidate matches (requires business decision: suppress copy, hold as orphan, or create placeholder document policy later).

### Recommended staging/review tables

```sql
CREATE TABLE IF NOT EXISTS migration.cleanup_p1_orphan_copy_candidates (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  batch_id uuid NOT NULL,
  copy_id uuid NOT NULL,
  legacy_inv_id bigint,
  legacy_doc_id bigint,
  candidate_document_id uuid,
  candidate_count int NOT NULL,
  proposed_action text NOT NULL, -- AUTO_LINK / REVIEW_LINK / UNRESOLVED
  confidence_score numeric(5,2) NOT NULL,
  rule_code text NOT NULL,
  source_snapshot jsonb NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS review.cleanup_p1_decisions (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  batch_id uuid NOT NULL,
  issue_family text NOT NULL,
  target_pk text NOT NULL,
  proposed_action text NOT NULL,
  reviewer_action text,
  reviewer_note text,
  reviewer_id text,
  decided_at timestamptz,
  status text NOT NULL DEFAULT 'PENDING' -- PENDING/APPROVED/REJECTED
);
```

### Rollback-safe execution approach

When applying approved links later:

1. Insert current row snapshot into patch log.
2. Update only rows where `document_id IS NULL` at apply-time.
3. Keep idempotent guard: skip rows already linked by another approved run.

---

## Issue Family 2 - ISBN Normalization and Duplicate ISBN Groups

### Exact problem definition

Two related defects:

1. ISBN formatting/normalization inconsistency (`198` rows in bad-format bucket).
2. Logical collisions where one normalized ISBN maps to multiple documents (`1,010` groups, `2,278` documents).

### Affected row counts

- Bad-format ISBN rows: 198
- Duplicate ISBN groups: 1,010
- Documents in duplicate groups: 2,278
- Additional context: missing ISBN rows 794 (not primary target in this family, but impacts collision resolution confidence)

### Example rows

Malformed/irregular samples:

- `legacy_doc_id=3`, `isbn=988-604-06-1345-0`, normalized: `9886040613450`
- `legacy_doc_id=8`, `isbn=978-1-4715-7388-0`, normalized: `9781471573880`

Duplicate group samples:

- ISBN `9965872880` appears in 6 docs: `5551, 5552, 5553, 8389, 8390, 8391`
- ISBN `9786010711198` appears in 5 docs
- ISBN `9786010711273` appears in 5 docs

### Proposed cleanup strategy

Split into two tracks:

Track A (Normalization):

- Compute canonical `isbn_norm` (strip punctuation, uppercase, checksum category).
- Preserve original value (`isbn_raw`) for traceability.
- Assign quality class: `VALID_10`, `VALID_13`, `INVALID_CHECKSUM`, `INVALID_PATTERN`.

Track B (Duplicate groups):

- Build collision clusters by `isbn_norm`.
- For each cluster calculate evidence profile (title similarity, year match, author overlap, publisher overlap, copy counts).
- Propose action per cluster:
  - `AUTO_MARK_SAME_WORK` only for very high-confidence exact matches.
  - `REVIEW_REQUIRED` for all ambiguous groups.

### Safe automation

- Canonical normalization is safe and deterministic.
- Auto-merge is NOT safe in this phase.
- Auto-mark as “candidate duplicate set” is safe.

### Human review needed

- Any merge or canonical “winner” selection.
- Conflict between same ISBN and clearly different works/editions.

### Recommended staging/review tables

```sql
CREATE TABLE IF NOT EXISTS migration.cleanup_p1_isbn_candidates (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  batch_id uuid NOT NULL,
  document_id uuid NOT NULL,
  legacy_doc_id bigint,
  isbn_raw text,
  isbn_norm text,
  isbn_quality_class text NOT NULL,
  is_auto_eligible boolean NOT NULL,
  rule_code text NOT NULL,
  source_snapshot jsonb NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS migration.cleanup_p1_isbn_duplicate_groups (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  batch_id uuid NOT NULL,
  isbn_norm text NOT NULL,
  document_ids uuid[] NOT NULL,
  legacy_doc_ids bigint[] NOT NULL,
  group_size int NOT NULL,
  confidence_score numeric(5,2) NOT NULL,
  proposed_action text NOT NULL, -- CANDIDATE_SET / REVIEW_REQUIRED
  evidence jsonb NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);
```

### Rollback-safe execution approach

- Do not overwrite `core.documents.isbn` immediately.
- Write normalized ISBN proposals into staging first.
- If later promoted, keep `before/after` audit in patch log and leave `raw.legacy_doc*` unchanged.

---

## Issue Family 3 - Missing Author Links

### Exact problem definition

Documents in `core.documents` with no row in `core.document_authors`.

### Affected row count

- 663 documents

### Example rows

Sample rows lacking author links:

- `legacy_doc_id=4`, `isbn=NULL`, `language_code=rus`, `publication_year=2022`
- `legacy_doc_id=7`, `isbn=NULL`, `language_code=eng`, `publication_year=2018`
- `legacy_doc_id=12`, `isbn=978-5-699-45558-4`, `language_code=rus`, `publication_year=2011`

### Proposed cleanup strategy

1. Extract candidate author strings from available bibliographic text fields/source payload markers.
2. Normalize person names (case/spacing/punctuation/transliteration-aware cleanup).
3. Match against `core.authors` dictionary with confidence bands.
4. Stage link proposals with role (`PRIMARY_AUTHOR`, `CO_AUTHOR`, `EDITOR` when inferable).

### Safe automation

- Auto-link only where one high-confidence unique author match exists.
- Auto-create no new author entities in Phase 1 (avoid vocabulary pollution).

### Human review needed

- Multi-candidate author matches.
- Unmatched candidate names needing dictionary expansion.
- Role ambiguity (author vs editor vs compiler).

### Recommended staging/review tables

```sql
CREATE TABLE IF NOT EXISTS migration.cleanup_p1_author_link_candidates (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  batch_id uuid NOT NULL,
  document_id uuid NOT NULL,
  legacy_doc_id bigint,
  parsed_author_text text,
  candidate_author_id uuid,
  candidate_author_name text,
  role_code text,
  confidence_score numeric(5,2) NOT NULL,
  is_auto_eligible boolean NOT NULL,
  rule_code text NOT NULL,
  source_snapshot jsonb NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);
```

### Rollback-safe execution approach

- Apply only approved rows as inserts into `core.document_authors`.
- Maintain patch log with composite key (`document_id`,`author_id`,`role_code`) and unique run token.
- Rollback by deleting inserted links from a given approved run.

---

## Issue Family 4 - Language Code Normalization and Suspected Mismatches

### Exact problem definition

`core.documents.language_code` has non-canonical values and probable label/content mismatches.

Observed mismatch metrics:

- `rus` but Kazakh-heavy script: 1,558
- `kaz` without Kazakh-script evidence: 473
- `eng/ger` but Cyrillic title: 71
- `rus/kaz` but Latin-only title: 39
- Non-canonical code variants exist (e.g., `Kaz`, compound values like `kaz; rus`)

### Affected row counts

- At minimum, all rows in mismatch buckets above plus rows with non-canonical codes.

### Example rows

Non-canonical code examples:

- `legacy_doc_id=1787`, `language_code=Kaz`
- `legacy_doc_id=4472`, `language_code=kaz; rus`
- `legacy_doc_id=5302`, `language_code=rus; kaz`
- `legacy_doc_id=16556`, `language_code=kaz;eng;rus`

### Proposed cleanup strategy

1. Define canonical code dictionary for this corpus (`rus`, `kaz`, `eng`, `ger`, `und`).
2. Parse and normalize existing language strings into:
   - primary canonical code
   - optional secondary languages array
   - normalization flags
3. Run script-based heuristic checks and assign confidence.
4. Auto-correct only deterministic mappings (`Kaz` -> `kaz`, case and delimiter normalization).
5. Route semantic mismatches to human review.

### Safe automation

- Case normalization and delimiter cleanup.
- Canonical mapping for exact known variants.
- Splitting compound values into normalized arrays.

### Human review needed

- True semantic mismatch between declared code and title script/content.
- Bilingual and transliterated works where script-only rules are insufficient.

### Recommended staging/review tables

```sql
CREATE TABLE IF NOT EXISTS migration.cleanup_p1_language_candidates (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  batch_id uuid NOT NULL,
  document_id uuid NOT NULL,
  legacy_doc_id bigint,
  language_code_raw text,
  language_code_norm text,
  secondary_languages text[],
  mismatch_flag boolean NOT NULL,
  mismatch_reason text,
  confidence_score numeric(5,2) NOT NULL,
  is_auto_eligible boolean NOT NULL,
  rule_code text NOT NULL,
  source_snapshot jsonb NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);
```

### Rollback-safe execution approach

- Apply only approved, deterministic code updates first.
- Keep previous value in patch log.
- Defer uncertain records to review queue.

---

## Recommended Cleanup Order (Phase 1)

1. Orphan inventory rows (highest operational integrity risk; low volume, high impact).
2. ISBN normalization + duplicate group staging (largest bibliographic risk and largest downstream effect).
3. Missing author links (search relevance and catalog quality impact).
4. Language code normalization + mismatch triage (important, but semantically ambiguous and review-heavy).

## Quick Wins vs Hard Cases

Quick wins:

- Deterministic orphan relinks with unique target match.
- ISBN canonical normalization (format-level only).
- Deterministic language code canonicalization (`Kaz` -> `kaz`, delimiter cleanup).

Harder cases:

- Duplicate ISBN groups that are edition-sensitive or metadata-sparse.
- Missing author links where candidate text is noisy/ambiguous.
- Language semantic mismatches for bilingual/transliterated titles.

## Expected Risks

- False-positive auto-linking if deterministic guard conditions are weak.
- Over-normalization (losing meaningful compound language semantics).
- Premature duplicate consolidation causing irreversible bibliographic conflation.

Mitigation:

- Strict auto-eligibility thresholds.
- Mandatory human approval for ambiguous cases.
- Full `before/after` patch logging and replayable rollback.

## What to Fix in Core vs Preserve in Raw

Fix in core (approved patches only):

- `core.book_copies.document_id` for approved orphan relinks.
- Normalized ISBN values (if/when promoted) in core-facing fields.
- Missing `core.document_authors` links from approved candidate set.
- Canonical `core.documents.language_code` updates for deterministic mappings.

Preserve in raw only (no mutation):

- All original source payloads and raw text variants.
- Legacy inconsistencies needed for forensic traceability.
- Any uncertain values pending review.

## Source Traceability Requirements

Every candidate/proposed patch should carry:

- `batch_id`
- source keys (`legacy_doc_id`, `legacy_inv_id`)
- target PK (`document_id`, `copy_id`)
- rule metadata (`rule_code`, `confidence_score`, `proposed_action`)
- immutable source snapshot (`source_snapshot` jsonb)
- timestamps and reviewer identity (if reviewed)

## Non-Destructive Rollout Sequence

1. Create staging and review tables.
2. Populate candidate sets from read-only core queries.
3. Classify auto-eligible vs review-required.
4. Complete human review for ambiguous rows.
5. Run small, guarded patch batches (if approved in next execution phase).
6. Validate post-patch metrics against baseline.
7. Keep rollback scripts ready and tested per batch.

## Phase 1 Completion Criteria (Planning Deliverable)

Planning is complete when:

- Candidate extraction SQL is defined for all 4 issue families.
- Auto vs manual boundaries are explicitly documented.
- Review queues are defined and auditable.
- Patch and rollback procedures are specified and idempotent.
- No raw-table mutation is required.
