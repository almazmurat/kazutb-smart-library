# App Text Correction Assist Phase

Date: 2026-03-20  
Status: implemented and executed on current app dataset

## Goal

Add a safe, explainable, auditable correction-suggestion layer for high-confidence text normalization that:

- auto-applies only very high-confidence, low-risk and reversible fixes
- stores suggestions for medium-confidence fixes
- flags low-confidence/risky findings
- keeps preserved `raw`/`core` source data intact

## Decision Policy

Three correction classes are enforced:

1. `auto_apply`

- deterministic and low-risk fixes only
- reversible via run-level rollback
- applied only to app-facing fields in `app.*`

2. `suggest_only`

- likely useful but requires librarian confirmation
- never silently updates display value

3. `flag_only`

- suspicious/noisy patterns that are too risky to auto-correct
- captured for librarian triage only

Confidence bands:

- `auto_apply`: ~0.98-0.995
- `suggest_only`: ~0.85-0.90
- `flag_only`: <=0.35

## Targeted Fields (Phase 1)

Focused app-facing text fields only:

- document title fields:
  - `app.documents.title_display`
  - `app.documents.title_normalized`
  - `app.documents.subtitle_normalized`
- `app.authors.display_name`
- `app.publishers.display_name`
- `app.subjects.display_subject`
- `app.keywords.display_keyword`
- reader names (review-oriented only):
  - `app.readers.full_name_normalized` (`suggest_only`/`flag_only`; no auto-write)

No broad semantic rewriting is attempted.

## Implemented Rules

Implemented safe rules:

- `trim_whitespace`
- `collapse_spaces`
- `normalize_punctuation_spacing` (conservative punctuation spacing)
- `remove_placeholder_junk`
- `kazakh_known_token_map` (high-confidence token map, e.g. `Казак -> Қазақ`, `Адебиет -> Әдебиет`)
- `kazakh_soft_pattern` (medium confidence, suggestion-only)

## Storage Model

Added:

- `app.text_correction_suggestions` table
- `app.text_correction_queue_v` view

Stored attributes include:

- entity type/id and field
- source/raw value, current value, suggested value
- correction rule key
- confidence
- action class (`auto_apply`, `suggest_only`, `flag_only`)
- `auto_applied`, `applied_at`
- review workflow fields (`reviewed`, `review_status`, reviewer info, notes)
- JSON `details` with rule/policy metadata
- run linkage via `normalization_run_id`

## Execution and Safety

Runner:

- `backend/scripts/legacy-reconstruction/build-app-text-corrections.js`

Schema:

- `backend/scripts/legacy-reconstruction/app-text-correction-layer.sql`

NPM command:

- `npm run build:app-text-corrections`

Safety properties:

- idempotent upserts into suggestion table
- auto-updates are conditional (`WHERE current_value = expected_old_value`)
- run-level rollback support:
  - `node scripts/legacy-reconstruction/build-app-text-corrections.js --rollback-run <runId>`

## Practical Output (Final Run)

Final run ID:

- `49aa4e11-0d97-4d27-ab5a-66e741b3d325`

Counts by action class:

- `auto_apply`: 2
- `suggest_only`: 1738
- `flag_only`: 0

Top rule categories:

- `normalize_punctuation_spacing`: 1738
- `kazakh_known_token_map`: 2

Auto-applied row updates:

- 2

## Sample High-Confidence Kazakh Auto-Corrections

- publisher `Казак университет` -> `Қазақ университет` (`auto_apply`, 0.98)
- publisher `Казак унииверситеті` -> `Қазақ унииверситеті` (`auto_apply`, 0.98)

## Sample Suggestion-Only Cases (Not Auto-Applied)

- `Бейсенова Г.И.,Есенкулова З.З.` -> `Бейсенова Г.И., Есенкулова З.З.`
- `Махамбет Өтемісұлы ( 1803 - 1846 )` -> `Махамбет Өтемісұлы (1803 - 1846)`
- `Анимация в туризме:Учебное пособие` -> `Анимация в туризме: Учебное пособие`

These were intentionally left for librarian confirmation due medium-confidence punctuation normalization policy.

## Commands

From `backend/`:

```bash
npm run build:app-text-corrections
```

Rollback a specific run:

```bash
node scripts/legacy-reconstruction/build-app-text-corrections.js --rollback-run <runId>
```

## Raw/Core Integrity

The correction workflow writes only to `app.*` structures and does not update `raw.*` or `core.*` tables.

- preserved source values are kept via `source_raw_value` and `current_value` in suggestions
- app updates are reversible via run-level rollback
