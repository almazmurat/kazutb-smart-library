# Data Quality and Profiling Report (Read-Only)

Date: 2026-03-19  
Database: `kazutb_library` (PostgreSQL)  
Schemas profiled: `core`, `review`  
Mode: analysis-only (no ingestion, no redesign, no data updates)

## Executive Summary

The reconstructed dataset is structurally complete and operationally usable for migration-forward work. Core entities and volume are consistent with earlier parity checks, and key foreign key coverage is high except for inherited source orphans.

Primary quality debt is concentrated in four areas:

1. ISBN quality and collision risk (missing + malformed + high duplicate groups).
2. Author linkage gaps on bibliographic records.
3. Reader contact sparsity (email almost entirely absent).
4. Legacy orphan inventory references (copies pointing to non-existing legacy document IDs).

Overall readiness assessment:

- `core.book_copies`: High readiness (except orphan subset).
- `core.documents`: Medium readiness (requires bibliographic cleanup policy).
- `core.legacy_readers`: Medium for circulation identity, low for communication workflows.
- `review.quality_issues`: High usefulness as triage queue; currently populated and consistent with observed defects.

## 1) High-Level Dataset Profile

| Area                                     |  Count |
| ---------------------------------------- | -----: |
| Documents (`core.documents`)             |  8,930 |
| Copies (`core.book_copies`)              | 49,620 |
| Readers (`core.legacy_readers`)          |  2,319 |
| Authors (`core.authors`)                 |  6,321 |
| Publishers (`core.publishers`)           |    774 |
| Subjects (`core.subjects`)               |    150 |
| Keywords (`core.keywords`)               |  5,121 |
| Quality issues (`review.quality_issues`) |  2,971 |
| Quality notes (`review.issue_notes`)     |      0 |

Interpretation:

- Bibliographic corpus and inventory are large enough for realistic migration rehearsal.
- The review layer is already rich enough to drive issue-based cleanup planning.

## 2) Quality Issue Distribution

### By issue code

| Issue code                    | Count |
| ----------------------------- | ----: |
| `probable_duplicate_isbn`     |   984 |
| `missing_isbn`                |   794 |
| `missing_author`              |   663 |
| `probable_duplicate_document` |   325 |
| `orphan_inventory_row`        |   170 |
| `missing_publication_year`    |    28 |
| `missing_title`               |     6 |
| `suspiciously_sparse_record`  |     1 |

### By severity

| Severity   | Count |
| ---------- | ----: |
| `CRITICAL` |     6 |
| `HIGH`     | 1,845 |
| `MEDIUM`   | 1,119 |
| `LOW`      |     1 |

Interpretation:

- Most backlog volume is not random noise; it clusters around identifier quality and bibliographic completeness.
- The very low `CRITICAL` count suggests hard blockers are limited in number and can be addressed first.

## 3) Missingness and Incompleteness

| Metric                                      | Count |
| ------------------------------------------- | ----: |
| Documents missing title                     |     6 |
| Documents missing ISBN                      |   794 |
| Documents missing publication year (`NULL`) |     0 |
| Documents missing author links              |   663 |
| Copies with missing linked `document_id`    |   170 |
| Copies missing inventory number             |     1 |
| Readers missing name                        |     0 |
| Readers missing legacy code                 |    10 |
| Readers missing email                       | 2,318 |
| Readers missing `bookpoints_raw`            |    16 |

Interpretation:

- Publication year missingness appears encoded mostly as sentinel values (`0`) rather than `NULL`.
- Reader identities are present, but contact metadata is effectively absent.

## 4) Dirty Data Categories (Quantified)

### Duplicates and collisions

| Metric                                   | Count |
| ---------------------------------------- | ----: |
| Duplicate ISBN groups                    | 1,010 |
| Documents in duplicate ISBN groups       | 2,278 |
| Duplicate title+year groups              |   423 |
| Documents in duplicate title+year groups |   912 |

Top duplicate ISBN groups (sample):

- `9965872880` -> 6 docs (`legacy_doc_id`: 5551, 5552, 5553, 8389, 8390, 8391)
- `9786010711198` -> 5 docs
- `9786010711273` -> 5 docs

### Orphans and broken references

| Metric                                                        | Count |
| ------------------------------------------------------------- | ----: |
| Copies with legacy document reference that cannot be resolved |   170 |
| Copies with `document_id IS NULL`                             |   170 |
| Copy event orphan links                                       |     0 |

Interpretation:

- The 170 unresolved copy-to-document rows are inherited source inconsistencies, preserved consistently through reconstruction.

### Format/consistency anomalies

| Metric                                      | Count |
| ------------------------------------------- | ----: |
| ISBN with bad normalized format             |   198 |
| Titles with mixed Latin/Cyrillic scripts    |   388 |
| Titles containing placeholder-like tokens   |    42 |
| Faculty value variants (near-duplicates)    |     4 |
| Department value variants (near-duplicates) |    15 |

## 5) Language Quality Heuristics

Language code distribution in `core.documents`:

- `rus`: 7,415
- `kaz`: 1,269
- `eng`: 218
- mojibake/nonstandard variant: 24
- `ger`: 1
- mixed compound values (`rus; kaz`, `kaz; rus`, etc.): small but non-zero

Heuristic mismatch counts:

- `rus` label but title appears Kazakh-script-heavy: 1,558
- `kaz` label but no Kazakh-script evidence: 473
- `eng/ger` label but title appears Cyrillic: 71
- `rus/kaz` label but Latin-only title: 39

Interpretation:

- Language code quality is serviceable for broad analytics but not sufficiently precise for language-specific discovery, routing, or policy enforcement.

## 6) Column-Level Profiling

### `core.documents`

| Column             | Nulls | Distinct | Notes                                                    |
| ------------------ | ----: | -------: | -------------------------------------------------------- |
| `title`            |     6 |    7,827 | Strong coverage; low null rate.                          |
| `isbn`             |   794 |    6,902 | Significant missingness and collision pressure.          |
| `publication_year` |     0 |       65 | Uses sentinel/non-null patterns; validate `0` semantics. |
| `language_code`    |     0 |        8 | Code normalization needed (compound/nonstandard values). |
| `faculty`          | 5,328 |        5 | Very sparse but low-cardinality where present.           |
| `department`       | 5,890 |       15 | Sparse taxonomy metadata.                                |
| `specialization`   | 5,733 |       67 | Sparse but moderately rich controlled-value surface.     |

Top-category profile (signal):

- `faculty`: 5,328 null; remaining values concentrated in a few large categories.
- `department`: 5,890 null; long-tail variants include mixed punctuation/compound labels.
- `specialization`: 5,733 null; one large umbrella value plus multiple coded specializations.

### `core.book_copies`

| Column             | Nulls | Distinct | Notes                                             |
| ------------------ | ----: | -------: | ------------------------------------------------- |
| `inventory_number` |     1 |   49,618 | Very strong identity coverage.                    |
| `legacy_doc_id`    |     0 |    8,951 | Full carryover from source inventory links.       |
| `state_code`       |     0 |        2 | Normalized low-cardinality operational state.     |
| `branch_id`        |     0 |        4 | Clean branch routing; no null branch assignments. |

### `core.legacy_readers`

| Column           | Nulls | Distinct | Notes                                                      |
| ---------------- | ----: | -------: | ---------------------------------------------------------- |
| `legacy_code`    |    10 |        5 | Needs canonicalization/format cleanup.                     |
| `full_name`      |     0 |    2,217 | Good identity text coverage.                               |
| `email`          | 2,318 |        1 | Not usable for email-based workflows.                      |
| `bookpoints_raw` |    16 |        7 | Mostly present; likely parse-ready to normalized relation. |

Pattern checks:

- ISBN normalized-valid counts: 7,060 (13-digit), 878 (10-digit), 198 invalid-format.
- Inventory patterns: 49,612 numeric-only; 49,612 alphanumeric-safe.
- Reader email regex-valid: 0.

## 7) Concrete Dirty vs Ideal Examples

These examples are from current data observations and are intended to be practical cleanup targets.

### A) Bibliographic identifier

- Dirty: `isbn='9965-642-77-'` (trailing hyphen, non-valid normalized form).
- Ideal: canonical normalized ISBN-10/13 in dedicated normalized field, raw preserved in source payload.

### B) Missing bibliographic title

- Dirty: records with empty `title` (6 rows total), e.g. `legacy_doc_id=5506`.
- Ideal: non-empty title required for discoverability; unresolved cases routed to human review queue.

### C) Inventory orphan

- Dirty: copy rows with valid `legacy_doc_id` but `document_id IS NULL` (170 rows).
- Ideal: every copy resolves to a canonical document, or is explicitly quarantined with orphan reason code.

### D) Reader contact

- Dirty: `email` empty for 2,318/2,319 readers.
- Ideal: email optional in core identity model, but communication channels represented via validated contacts table (email/phone/messenger) with consent/status attributes.

### E) Language labels

- Dirty: mixed/compound/nonstandard language codes (`Kaz`, `kaz; rus`, malformed variants).
- Ideal: controlled ISO-like code list (single canonical code + optional secondary-language relation).

## 8) Professional Target Model (What "Clean" Should Mean)

### Documents

Required quality bar:

- Stable document identity.
- Non-empty title.
- At least one author/contributor relation.
- Publication year normalized (`NULL` for unknown, no sentinel `0`).
- Canonical language code from controlled dictionary.

Recommended enrichment:

- Canonical ISBN entity with confidence level.
- Controlled taxonomy for faculty/department/specialization.
- Script/language coherence checks for multilingual search quality.

### Book copies

Required quality bar:

- Inventory number unique and non-empty.
- Resolved document FK or explicit quarantine state.
- Valid branch and storage sigla mappings.
- Operational state transitions auditability.

### Readers

Required quality bar:

- Stable legacy identifier and normalized full name.
- At least one valid communication channel when operationally needed.
- Bookpoint affiliations represented in normalized relation.

## 9) Cleanup Readiness Matrix

| Issue family                  | Auto-fixable                                               | Partially auto-fixable                                  | Human review required                           |
| ----------------------------- | ---------------------------------------------------------- | ------------------------------------------------------- | ----------------------------------------------- |
| ISBN normalization/format     | Yes (strip punctuation, normalize case, checksum classify) | Yes (infer from alternate fields/siblings)              | Yes (conflicting duplicates, edition ambiguity) |
| Missing ISBN                  | No                                                         | Yes (recover from duplicate clusters/catalog APIs)      | Yes                                             |
| Missing author links          | No                                                         | Yes (rule-based parse from statement-of-responsibility) | Yes                                             |
| Duplicate document candidates | No                                                         | Yes (fingerprint clustering + confidence)               | Yes (merge decisions)                           |
| Orphan inventory rows         | No                                                         | Limited (legacy crosswalk heuristics)                   | Yes                                             |
| Language code inconsistencies | Yes (dictionary normalization)                             | Yes (script heuristics)                                 | Yes (true bilingual/translated works)           |
| Reader email sparsity         | No                                                         | Limited (if external trusted source exists)             | Yes                                             |
| Taxonomy variant cleanup      | Yes (canonical dictionary + mapping table)                 | Yes                                                     | Yes (ambiguous domain terms)                    |

## 10) Practical Next Cleanup Order

1. Resolve `CRITICAL` + orphan copy backlog (`170` rows) with explicit disposition.
2. ISBN pipeline: normalize -> validate -> cluster duplicates -> triage high-frequency collisions.
3. Author-link recovery for `663` documents (semi-automated extraction + review).
4. Language code canonicalization and compound-code splitting.
5. Reader contact model decision (accept sparse email or enrich from trusted sources).

## Final Assessment

The reconstruction quality is strong enough to proceed with controlled cleanup and migration-hardening phases. The dataset is not "clean" yet, but its defects are measurable, well-clustered, and triageable through a combined automated + human-review workflow.
