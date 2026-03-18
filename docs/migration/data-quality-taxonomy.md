# Data Quality Taxonomy for Legacy Migration

## Purpose

Define a shared severity model and operational handling rules for migration quality issues.

## Severity Levels

## `CRITICAL`

- Definition: Blocks safe import or causes referential/identity corruption.
- Examples:
  - Missing canonical record key.
  - Broken mandatory foreign key for target entity.
  - Invalid structural payload that cannot be parsed.
- Detection: Automated validation (hard fail).
- Fixability: Usually requires source correction or explicit transformation rule.
- Review: Mandatory dual review (data engineer + librarian/domain owner).

## `HIGH`

- Definition: Import may complete but produces materially incorrect business behavior.
- Examples:
  - Copy-to-title mismatch.
  - Ambiguous branch assignment.
  - Invalid status mapping for circulation events.
- Detection: Automated rules + targeted manual review.
- Fixability: Rule refinement or curated correction set.
- Review: Mandatory domain review before batch promotion.

## `MEDIUM`

- Definition: Does not block import but degrades discoverability or reporting quality.
- Examples:
  - Inconsistent language code forms.
  - Author normalization conflicts.
  - Subject/category vocabulary drift.
- Detection: Rule-based checks and sampling.
- Fixability: Usually script-fixable with approved dictionaries.
- Review: Single reviewer with periodic audit.

## `LOW`

- Definition: Cosmetic/informational quality concerns with limited operational impact.
- Examples:
  - Extra whitespace, punctuation variance.
  - Non-critical transliteration inconsistencies.
- Detection: Heuristic checks.
- Fixability: Optional cleanup in later passes.
- Review: Optional; batch may proceed.

## Issue Classes

- `IDENTITY`: duplicate or missing identifiers.
- `REFERENTIAL`: missing/broken parent-child links.
- `SEMANTIC`: field meaning conflicts or unknown status codes.
- `FORMAT`: invalid encodings, malformed dates, invalid ISBN.
- `GOVERNANCE`: PII policy or scope/ownership violations.
- `DERIVED`: inconsistencies in computed/flattened fields.

## Required Issue Metadata

Each issue record should capture:

- `issue_id`
- `batch_id`
- `legacy_table`
- `legacy_record_key`
- `field_name`
- `severity`
- `issue_class`
- `detection_rule`
- `auto_fixable` (`true/false`)
- `proposed_fix`
- `status` (`new|in_review|approved|rejected|fixed`)
- `reviewer`
- `created_at`, `updated_at`

## Handling Policy

- `CRITICAL` and `HIGH` unresolved issues block batch promotion.
- `MEDIUM` issues require explicit acceptance threshold.
- `LOW` issues may be deferred with backlog tracking.
- All overrides must be auditable and reviewer-attributed.

## Promotion Rule

Batch can move from `clean` to `normalized` and then to import only if:

- no unresolved `CRITICAL`
- no unresolved `HIGH`
- `MEDIUM` volume within accepted threshold
- governance checks passed
