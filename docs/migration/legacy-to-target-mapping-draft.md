# Legacy-to-Target Mapping Draft (With Confidence)

## Purpose

Draft mapping from legacy SQL structures to Smart Library target entities for migration planning.

Confidence labels:

- **H**: High confidence (artifact-backed)
- **M**: Medium confidence (likely, needs validation)
- **L**: Low confidence (assumption-level)

## Bibliographic Domain

| Legacy source                               | Target entity/field                      | Confidence | Notes                                                  |
| ------------------------------------------- | ---------------------------------------- | ---------- | ------------------------------------------------------ |
| `DOC.DOC_ID`                                | `Book.legacyDocId` (staging reference)   | H          | Stable key for lineage during migration.               |
| `DOC.ITEM`                                  | `Book.rawMarcPayload` (raw/staging only) | H          | Keep raw for traceability; parse to normalized fields. |
| `DOC_VIEW.title`                            | `Book.title`                             | H          | Flattened field visible in view export.                |
| `DOC_VIEW.author`, `DOC_VIEW.other_authors` | `Author`, `BookAuthor`                   | M          | Requires split and dedup logic.                        |
| `DOC_VIEW.isbn`                             | `Book.isbn`                              | H          | Needs checksum and formatting validation.              |
| `DOC_VIEW.text_language_code`               | `Book.languageCode`                      | M          | Normalize codes and aliases.                           |
| `DOC_VIEW.publisher`, `publication_place`   | `Publisher` + optional place fields      | M          | Standardize naming and city variants.                  |
| `DOC_VIEW.year` / `year_digits`             | `Book.publishYear`                       | H          | Resolve conflicts between string/int variants.         |
| `DOC_VIEW.keywords`, `subject`              | `Category` / subject tags                | M          | Controlled vocabulary strategy required.               |

## Inventory and Circulation Domain

| Legacy source                       | Target entity/field                                | Confidence | Notes                                              |
| ----------------------------------- | -------------------------------------------------- | ---------- | -------------------------------------------------- |
| `INV.*`                             | `BookCopy`                                         | H          | Core physical copy candidate.                      |
| `INV.DOC_ID` (if present/derivable) | `BookCopy.bookId`                                  | M          | Validate exact join path in full schema semantics. |
| `BOOKPOINTS.IDP`, `SHORTNAME`       | `LibraryBranch` / location mapping                 | M          | Requires explicit mapping matrix.                  |
| `BOOKSTATES.*`                      | `Loan` / `LoanEvent` / copy status timeline        | M          | Depends on `STATE` codebook confirmation.          |
| `RDRBP.*`                           | Reader-branch linkage or service point preferences | L          | Semantics to validate.                             |

## Reader Domain

| Legacy source         | Target entity/field               | Confidence | Notes                                 |
| --------------------- | --------------------------------- | ---------- | ------------------------------------- |
| `READERS.*`           | `User` (limited migrated profile) | M          | Migrate only policy-approved fields.  |
| `READERS` identifiers | `User.externalLegacyId`           | M          | Identity collision strategy required. |
| Reader status fields  | Role/eligibility flags            | L          | Must be approved by IAM/governance.   |

## Acquisition/Procurement Domain

| Legacy source                            | Target entity/field                      | Confidence | Notes                                    |
| ---------------------------------------- | ---------------------------------------- | ---------- | ---------------------------------------- |
| `ACQUORD`, `ACQUGOT`                     | `AcquisitionOrder`, `AcquisitionReceipt` | M          | Useful for history; optional in phase 1. |
| `ACQUSRC`, `ACQUCUSTOMERS`, `ACQUTENDER` | Reference entities                       | M          | Normalize organizations and code sets.   |

## Search and Derived Structures

| Legacy source                | Target handling                     | Confidence | Notes                                   |
| ---------------------------- | ----------------------------------- | ---------- | --------------------------------------- |
| `IDX*`, `IDX*X`              | Do not migrate as business entities | H          | Rebuild search indexes in target stack. |
| `DOCIDXX`, `MOIDX`, `MOIDXX` | Staging diagnostics only            | H          | Treat as legacy index internals.        |
| `DOC_VIEW`                   | Parse/QA assist only                | H          | Do not treat as sole canonical source.  |

## Explicit Non-Goals for First Migration Wave

- 1:1 transfer of legacy indexing tables.
- Blind import of all reader PII fields.
- Full historical replay without quality and policy gates.

## Open Mapping Questions

- Exact join path from `INV` to `DOC` in edge cases.
- Complete state code dictionary for `BOOKSTATES.STATE`.
- Final branch ownership derivation path (`BOOKPOINTS`, `LIBS*`, `SIGLAS*`).
- Minimal legally compliant reader profile subset.
