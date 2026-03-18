# Legacy DB Analysis (Artifact-Based)

## Scope

This analysis is based only on local discovery artifacts under `docs/legacy-db-discovery/`:

- `old-db-schema.sql`
- `columns_inventory.csv`
- `table_row_counts.csv`
- `foreign_keys.csv`
- `dbo_DOC_VIEW.csv`
- `notes.md`

## 1) Confirmed Facts

### Source platform

- Legacy database name is `marc` (`USE [marc]` in schema export).
- Source engine is Microsoft SQL Server (schema format and object syntax).
- Schema export contains `168` tables and `1` view (`DOC_VIEW`).
- Backup artifact exists: `marc_full.bak`.

### High-signal entities and volumes

From `table_row_counts.csv`:

- `DOC`: `8,930`
- `INV`: `49,620`
- `READERS`: `2,319`
- `MARCSTAT`: `389,672`
- `DOCIDXX`: `6,415,740`

Index link density is high:

- Multiple `IDX*X` bridge/link tables are near `8,930` rows each.
- Aggregate rows across `IDX*X` entries in the row-count artifact: `403,917`.

### Structural patterns

From schema and FK artifacts:

- Bibliographic core appears around `DOC`, `DOC_VIEW`, `DOC_EDIT`, `DOCIDXX`.
- Search/index subsystem is extensive (`IDX*`, `IDX*X`, `MOIDX`, `MOBJECT`, `MOIDXX`).
- Reader/circulation-related entities exist: `READERS`, `RDRBP`, `INV`, `BOOKSTATES`, `BOOKPOINTS`.
- Acquisition-related entities exist: `ACQUORD`, `ACQUGOT`, `ACQUSRC`, `ACQUCUSTOMERS`, `ACQUTENDER`.
- Library segmentation-related entities exist: `LIBS`, `LIBS_BOOKPOINTS`, `LIBS_SIGLAS`, `LIBS_EVENTS`, `LIBS_EXHIBITS*`.

### Data shape indicators

From `columns_inventory.csv`:

- `DOC.ITEM` is stored as large text (`ntext`) and likely carries MARC-like payload.
- `DOC_VIEW` exposes flattened/derived attributes such as title, author, ISBN, language, year, subject-like dimensions.
- `BOOKSTATES` includes `RDR_ID`, `INV_ID`, `DOC_ID`, `STATE`, and date fields (`CHSDATE`, `RETDATE`).
- `READERS` includes a broad set of profile attributes in schema; row counts are non-trivial.

### Sample quality indicators

From `dbo_DOC_VIEW.csv` samples:

- Multilingual content is present (Kazakh, Russian, English mixed records).
- Text normalization issues are present (inconsistent punctuation/spacing, mixed transliteration, duplicate field fragments).
- File path-like values (e.g., local `C:\...` links) appear in flattened output and must be governed in migration.

## 2) High-Confidence Inferences

These are evidence-backed but still require implementation-time verification.

- `DOC` is likely the canonical bibliographic record source; `DOC_VIEW` appears to be a projection for querying/reporting.
- `INV` likely represents physical inventory units (copies/items), given size relationship (`INV` >> `DOC`).
- `IDX*` and `IDX*X` tables are primarily search/index support and should not be migrated 1:1 into the target domain model.
- `DOCIDXX` likely stores token/index-to-document associations and should be treated as derived/search infrastructure.
- `BOOKPOINTS` likely models branch/point-of-service locations used by `BOOKSTATES` and `RDRBP`.
- `BOOKSTATES` likely captures status transitions/events for document/copy state at a service point.

## 3) Assumptions (Explicit, Pending Validation)

- Assumption A1: `BOOKSTATES.STATE` code semantics can be deterministically mapped to target circulation statuses.
- Assumption A2: `READERS` records are migration-eligible as identities (subject to legal/retention policy and dedup rules).
- Assumption A3: `DOC_VIEW` can be used as a migration aid for parsing QA, but `DOC` remains source-of-truth.
- Assumption A4: `MARCSTAT` is historical/statistical and not required as first-wave transactional migration payload.

## 4) Migration-Relevant Risks

- Legacy text encodings and multilingual normalization will produce mapping ambiguity for author/title fields.
- Index-derived tables can cause accidental duplication if treated as business entities.
- Status/date semantics in circulation-like tables (`BOOKSTATES`) may not be self-describing.
- Branch ownership must be reconstructed from `BOOKPOINTS`/`LIBS*` links and validated against modern scope rules.
- Potentially sensitive reader attributes require policy-gated migration.

## 5) Immediate Validation Tasks Before Bulk Transform

- Confirm business meaning of `BOOKSTATES.STATE` and date fields with domain experts.
- Establish deterministic copy identity strategy (`INV` + any stable inventory identifiers).
- Validate branch mapping path from legacy tables to modern branch enums.
- Define PII retention/minimization policy for `READERS` migration set.
- Lock first-wave entity boundaries (migrate vs derive vs archive-only).

## Confidence Legend

- **Fact**: Directly confirmed in artifact(s).
- **High-confidence inference**: Strong multi-artifact signal; still needs execution validation.
- **Assumption**: Declared placeholder pending stakeholder or test confirmation.
