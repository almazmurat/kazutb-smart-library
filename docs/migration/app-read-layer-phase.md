# App Read Layer For First Product Screens

Date: 2026-03-20  
Status: implemented on top of existing app schema  
Mode: non-destructive read-layer extension

## 1) Goal

Add the first stable app-facing read layer so the web application can power:

- catalog search
- search results
- book detail
- copy availability by campus and service point
- librarian review queue

This pass extends the populated `app` schema without rerunning legacy ingestion and without changing `raw`, `core`, or `review` source data.

## 2) Read Objects Added

### `app.copy_location_facts_v`

Copy-level fact view with:

- document linkage
- campus / institution / service-point context
- storage sigla
- availability heuristic
- review/problem/orphan flags

Use this as the base fact source for copy and location queries.

### `app.catalog_search_mv`

One row per document for search and results pages.

Includes:

- normalized and raw title fields
- publisher and publication year
- author / subject / keyword aggregates
- ISBN raw + normalized
- language
- total / available / review / orphan copy counts
- campus and service-point arrays
- document and copy review counts
- searchable text + `tsvector`

### `app.document_availability_by_location_v`

Aggregated per-document availability by institution, campus, and service point.

### `app.location_inventory_summary_v`

Overall inventory and availability totals by location, including problem and orphan counts.

### `app.catalog_filter_facets_v`

Facet-style rows for:

- language
- campus
- service point
- availability buckets

### `app.document_detail_v`

Single-document detail view with:

- main metadata
- title variants
- authors / subjects / keywords JSON
- quality flags
- review tasks
- copy summary
- campus and service-point distribution JSON

### `app.review_queue_v`

Unified librarian issue queue across document, copy, and reader flags/tasks with bibliographic and location context.

## 3) PostgreSQL Search Readiness

Added indexes for first-screen usage:

- GIN on `app.catalog_search_mv.search_vector`
- trigram GIN on `app.catalog_search_mv.searchable_text`
- trigram GIN on title and primary author
- GIN on campus and service-point arrays
- btree indexes for language, ISBN, availability counts, and review flags
- supporting btree indexes on copy/review base tables used by the views

## 4) Availability Heuristic

For this first read layer, a copy is treated as app-available when:

- it is linked to a document
- `state_code = 1`
- location mapping is confirmed
- the copy does not itself need review

This is intentionally demo-friendly and can later be replaced with richer circulation-state logic.

## 5) Build Command

From `backend/`:

```bash
npm run build:app-read-layer
```

## 6) Example Queries

### Catalog search

```sql
WITH params AS (
	SELECT 'сөздік'::text AS q
)
SELECT
	legacy_doc_id,
	title_display,
	primary_author_display,
	isbn_normalized,
	language_code,
	total_copy_count,
	available_copy_count,
	campus_codes,
	has_open_review
FROM app.catalog_search_mv csm, params
WHERE csm.search_vector @@ websearch_to_tsquery('simple', params.q)
	 OR csm.searchable_text % params.q
	 OR csm.title_display ILIKE '%' || params.q || '%'
ORDER BY
	ts_rank_cd(csm.search_vector, websearch_to_tsquery('simple', params.q)) DESC,
	similarity(csm.searchable_text, params.q) DESC,
	csm.available_copy_count DESC,
	csm.legacy_doc_id
LIMIT 20;
```

### Catalog search with author + campus filter

```sql
WITH params AS (
	SELECT 'Оспанова'::text AS q
)
SELECT
	legacy_doc_id,
	title_display,
	primary_author_display,
	isbn_normalized,
	available_copy_count,
	campus_codes,
	service_point_codes
FROM app.catalog_search_mv csm, params
WHERE (
		csm.search_vector @@ websearch_to_tsquery('simple', params.q)
		OR csm.searchable_text % params.q
		OR csm.primary_author_display ILIKE '%' || params.q || '%'
	)
	AND csm.campus_codes @> ARRAY['UNIVERSITY_ECONOMIC']::text[]
ORDER BY csm.available_copy_count DESC, csm.legacy_doc_id
LIMIT 20;
```

### Book detail

```sql
SELECT
	legacy_doc_id,
	title_display,
	publisher_name,
	publication_year,
	language_code,
	isbn_raw,
	isbn_normalized,
	authors_json,
	copy_summary_json,
	campus_distribution_json,
	service_point_distribution_json,
	document_quality_flags_json,
	copy_quality_flags_json,
	review_tasks_json
FROM app.document_detail_v
WHERE legacy_doc_id = 6967;
```

### Campus availability

```sql
SELECT
	campus_code,
	campus_name,
	total_copy_count,
	available_copy_count,
	review_copy_count,
	problem_copy_count,
	orphan_copy_count,
	distinct_document_count
FROM app.location_inventory_summary_v
ORDER BY total_copy_count DESC;
```

### Librarian issue queue

```sql
SELECT
	entity_type,
	issue_code,
	severity,
	task_priority,
	legacy_doc_id,
	title_display,
	legacy_inv_id,
	inventory_number_normalized,
	legacy_reader_id,
	campus_codes,
	service_point_codes
FROM app.review_queue_v
WHERE severity IN ('HIGH', 'MEDIUM')
ORDER BY severity DESC, entity_type, legacy_doc_id NULLS LAST, legacy_inv_id NULLS LAST
LIMIT 50;
```

## 7) Live Validation Metrics

Build output after execution:

- `app.catalog_search_mv`: 8930 rows
- `app.document_detail_v`: 8930 rows
- `app.document_availability_by_location_v`: 9087 rows
- `app.location_inventory_summary_v`: 4 rows
- `app.review_queue_v`: 4153 rows
- `app.catalog_filter_facets_v`: 15 rows
- searchable documents: 8929
- documents with available copies: 8790
- documents with open review: 1498

Observed language facets:

- `rus`: 7415 documents, 37004 copies, 37000 available
- `kaz`: 1293 documents, 10771 copies, 10771 available
- `eng`: 218 documents, 1618 copies, 1618 available
- `und`: 3 documents, 52 copies, 52 available
- `ger`: 1 document, 5 copies, 5 available

Observed campus totals:

- `UNIVERSITY_CENTRAL`: 33879 copies, 33710 available, 166 problem/orphan, 5622 documents
- `UNIVERSITY_ECONOMIC`: 7506 copies, 7502 available, 4 problem/orphan, 1569 documents
- `UNIVERSITY_TECHNOLOGICAL`: 6084 copies, 6083 available, 1 problem, 1478 documents
- `COLLEGE_MAIN`: 2151 copies, 2151 available, 0 problem, 418 documents

## 8) Source Safety

This layer only adds or rebuilds read objects and helper indexes in `app`.

Source layers remain intact:

- `raw`
- `core`
- `review`

The populated `app` source tables remain the backing data for the new read layer.

Validated counts remained unchanged after the read-layer build:

- `raw.doc`: 8930
- `raw.inv`: 49620
- `raw.readers`: 2319
- `core.documents`: 8930
- `core.book_copies`: 49620
- `core.legacy_readers`: 2319
- `review.quality_issues`: 2971
- `app.documents`: 8930
- `app.book_copies`: 49620
- `app.readers`: 2319
