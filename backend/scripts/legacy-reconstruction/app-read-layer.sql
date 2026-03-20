CREATE EXTENSION IF NOT EXISTS pg_trgm;

CREATE SCHEMA IF NOT EXISTS app;

CREATE INDEX IF NOT EXISTS idx_app_book_copies_branch_state_review
  ON app.book_copies(branch_id, state_code, needs_review);

CREATE INDEX IF NOT EXISTS idx_app_book_copies_campus_state_review
  ON app.book_copies(campus_id, state_code, needs_review);

CREATE INDEX IF NOT EXISTS idx_app_book_copies_location_status
  ON app.book_copies(location_mapping_status);

CREATE INDEX IF NOT EXISTS idx_app_document_authors_document_sort
  ON app.document_authors(document_id, is_primary, sort_order);

CREATE INDEX IF NOT EXISTS idx_app_document_subjects_document
  ON app.document_subjects(document_id);

CREATE INDEX IF NOT EXISTS idx_app_document_keywords_document
  ON app.document_keywords(document_id);

CREATE INDEX IF NOT EXISTS idx_app_quality_flags_issue_status
  ON app.data_quality_flags(issue_code, severity, status);

CREATE INDEX IF NOT EXISTS idx_app_review_tasks_entity_status
  ON app.review_tasks(entity_type, entity_id, status, priority);

DROP VIEW IF EXISTS app.review_queue_v CASCADE;
DROP VIEW IF EXISTS app.document_detail_v CASCADE;
DROP VIEW IF EXISTS app.catalog_filter_facets_v CASCADE;
DROP VIEW IF EXISTS app.location_inventory_summary_v CASCADE;
DROP VIEW IF EXISTS app.document_availability_by_location_v CASCADE;
DROP MATERIALIZED VIEW IF EXISTS app.catalog_search_mv CASCADE;
DROP VIEW IF EXISTS app.copy_location_facts_v CASCADE;

CREATE VIEW app.copy_location_facts_v AS
SELECT
  bc.id AS copy_id,
  bc.core_copy_id,
  bc.legacy_inv_id,
  bc.legacy_doc_id,
  bc.document_id,
  bc.inventory_number_raw,
  bc.inventory_number_normalized,
  bc.branch_hint_raw,
  bc.branch_hint_normalized,
  bc.state_code,
  CASE
    WHEN bc.state_code = 1 THEN 'AVAILABLE'
    WHEN bc.state_code = 2 THEN 'UNAVAILABLE'
    ELSE 'UNKNOWN'
  END AS state_label,
  bc.needs_review,
  bc.review_reason_codes,
  bc.location_mapping_status,
  bc.location_mapping_confidence,
  bc.branch_id AS service_point_id,
  sp.code AS service_point_code,
  sp.name AS service_point_name,
  sp.mapping_status AS service_point_mapping_status,
  bc.sigla_id,
  sg.shortname AS sigla_code,
  sg.mapping_status AS sigla_mapping_status,
  bc.institution_unit_id,
  iu.code AS institution_unit_code,
  iu.name AS institution_unit_name,
  bc.campus_id,
  cp.code AS campus_code,
  cp.name AS campus_name,
  (bc.document_id IS NULL) AS is_orphan_copy,
  (
    bc.document_id IS NOT NULL
    AND coalesce(bc.state_code, 1) = 1
    AND bc.location_mapping_status = 'CONFIRMED'
    AND NOT bc.needs_review
  ) AS is_available,
  (
    bc.document_id IS NULL
    OR bc.needs_review
    OR bc.location_mapping_status <> 'CONFIRMED'
  ) AS is_problem_copy
FROM app.book_copies bc
LEFT JOIN app.branches sp ON sp.id = bc.branch_id
LEFT JOIN app.siglas sg ON sg.id = bc.sigla_id
LEFT JOIN app.institution_units iu ON iu.id = bc.institution_unit_id
LEFT JOIN app.campuses cp ON cp.id = bc.campus_id;

CREATE MATERIALIZED VIEW app.catalog_search_mv AS
WITH author_rows AS (
  SELECT
    da.document_id,
    a.id AS author_id,
    a.display_name AS author_name,
    da.author_role,
    da.sort_order,
    da.is_primary,
    CASE WHEN da.is_primary THEN 0 ELSE 1 END AS sort_bucket
  FROM app.document_authors da
  JOIN app.authors a ON a.id = da.author_id
), author_agg AS (
  SELECT
    ar.document_id,
    jsonb_agg(
      jsonb_build_object(
        'id', ar.author_id,
        'name', ar.author_name,
        'role', ar.author_role,
        'isPrimary', ar.is_primary,
        'sortOrder', ar.sort_order
      )
      ORDER BY ar.sort_bucket, ar.sort_order, ar.author_name
    ) AS authors_json,
    array_agg(ar.author_name ORDER BY ar.sort_bucket, ar.sort_order, ar.author_name) AS author_names,
    (array_agg(ar.author_name ORDER BY ar.sort_bucket, ar.sort_order, ar.author_name))[1] AS primary_author_display,
    array_to_string(array_agg(ar.author_name ORDER BY ar.sort_bucket, ar.sort_order, ar.author_name), ' ') AS author_search_text
  FROM author_rows ar
  GROUP BY ar.document_id
), subject_rows AS (
  SELECT
    ds.document_id,
    s.id AS subject_id,
    s.display_subject AS subject_name,
    ds.source_kind
  FROM app.document_subjects ds
  JOIN app.subjects s ON s.id = ds.subject_id
), subject_agg AS (
  SELECT
    sr.document_id,
    jsonb_agg(
      jsonb_build_object(
        'id', sr.subject_id,
        'label', sr.subject_name,
        'sourceKind', sr.source_kind
      )
      ORDER BY sr.subject_name
    ) AS subjects_json,
    array_agg(sr.subject_name ORDER BY sr.subject_name) AS subject_names,
    array_to_string(array_agg(sr.subject_name ORDER BY sr.subject_name), ' ') AS subject_search_text
  FROM subject_rows sr
  GROUP BY sr.document_id
), keyword_rows AS (
  SELECT
    dk.document_id,
    k.id AS keyword_id,
    k.display_keyword AS keyword_name
  FROM app.document_keywords dk
  JOIN app.keywords k ON k.id = dk.keyword_id
), keyword_agg AS (
  SELECT
    kr.document_id,
    jsonb_agg(
      jsonb_build_object(
        'id', kr.keyword_id,
        'label', kr.keyword_name
      )
      ORDER BY kr.keyword_name
    ) AS keywords_json,
    array_agg(kr.keyword_name ORDER BY kr.keyword_name) AS keyword_names,
    array_to_string(array_agg(kr.keyword_name ORDER BY kr.keyword_name), ' ') AS keyword_search_text
  FROM keyword_rows kr
  GROUP BY kr.document_id
), title_agg AS (
  SELECT
    dt.document_id,
    jsonb_agg(
      jsonb_build_object(
        'kind', dt.title_kind,
        'raw', dt.value_raw,
        'normalized', dt.value_normalized,
        'display', dt.value_display,
        'isPrimary', dt.is_primary
      )
      ORDER BY dt.is_primary DESC, dt.title_kind, dt.value_display
    ) AS title_variants_json,
    count(*)::int AS title_variant_count
  FROM app.document_titles dt
  GROUP BY dt.document_id
), copy_agg AS (
  SELECT
    clf.document_id,
    count(*)::int AS total_copy_count,
    count(*) FILTER (WHERE clf.is_available)::int AS available_copy_count,
    count(*) FILTER (WHERE NOT clf.is_available)::int AS unavailable_copy_count,
    count(*) FILTER (WHERE clf.needs_review)::int AS review_copy_count,
    count(*) FILTER (WHERE clf.is_problem_copy)::int AS problem_copy_count,
    count(*) FILTER (WHERE clf.is_orphan_copy)::int AS orphan_copy_count,
    count(DISTINCT clf.campus_id)::int AS campus_count,
    count(DISTINCT clf.service_point_id)::int AS service_point_count,
    ARRAY(
      SELECT DISTINCT c2.campus_code
      FROM app.copy_location_facts_v c2
      WHERE c2.document_id = clf.document_id AND c2.campus_code IS NOT NULL
      ORDER BY c2.campus_code
    ) AS campus_codes,
    ARRAY(
      SELECT DISTINCT c2.campus_name
      FROM app.copy_location_facts_v c2
      WHERE c2.document_id = clf.document_id AND c2.campus_name IS NOT NULL
      ORDER BY c2.campus_name
    ) AS campus_names,
    ARRAY(
      SELECT DISTINCT c2.service_point_code
      FROM app.copy_location_facts_v c2
      WHERE c2.document_id = clf.document_id AND c2.service_point_code IS NOT NULL
      ORDER BY c2.service_point_code
    ) AS service_point_codes,
    ARRAY(
      SELECT DISTINCT c2.service_point_name
      FROM app.copy_location_facts_v c2
      WHERE c2.document_id = clf.document_id AND c2.service_point_name IS NOT NULL
      ORDER BY c2.service_point_name
    ) AS service_point_names,
    ARRAY(
      SELECT DISTINCT c2.institution_unit_code
      FROM app.copy_location_facts_v c2
      WHERE c2.document_id = clf.document_id AND c2.institution_unit_code IS NOT NULL
      ORDER BY c2.institution_unit_code
    ) AS institution_unit_codes
  FROM app.copy_location_facts_v clf
  WHERE clf.document_id IS NOT NULL
  GROUP BY clf.document_id
), review_flag_union AS (
  SELECT
    d.id AS document_id,
    dqf.id AS flag_id,
    dqf.issue_code,
    dqf.severity,
    'document'::text AS source_entity_type
  FROM app.documents d
  JOIN app.data_quality_flags dqf
    ON dqf.entity_type = 'document'
   AND dqf.entity_id = d.id
   AND dqf.status = 'OPEN'

  UNION ALL

  SELECT
    bc.document_id,
    dqf.id AS flag_id,
    dqf.issue_code,
    dqf.severity,
    'book_copy'::text AS source_entity_type
  FROM app.book_copies bc
  JOIN app.data_quality_flags dqf
    ON dqf.entity_type = 'book_copy'
   AND dqf.entity_id = bc.id
   AND dqf.status = 'OPEN'
  WHERE bc.document_id IS NOT NULL
), review_flag_agg AS (
  SELECT
    rfu.document_id,
    count(*) FILTER (WHERE rfu.source_entity_type = 'document')::int AS document_flag_count,
    count(*) FILTER (WHERE rfu.source_entity_type = 'book_copy')::int AS copy_flag_count,
    ARRAY(
      SELECT DISTINCT r2.issue_code
      FROM review_flag_union r2
      WHERE r2.document_id = rfu.document_id
      ORDER BY r2.issue_code
    ) AS review_issue_codes,
    CASE max(
      CASE rfu.severity
        WHEN 'CRITICAL' THEN 4
        WHEN 'HIGH' THEN 3
        WHEN 'MEDIUM' THEN 2
        WHEN 'LOW' THEN 1
        ELSE 0
      END
    )
      WHEN 4 THEN 'CRITICAL'
      WHEN 3 THEN 'HIGH'
      WHEN 2 THEN 'MEDIUM'
      WHEN 1 THEN 'LOW'
      ELSE NULL
    END AS highest_review_severity
  FROM review_flag_union rfu
  GROUP BY rfu.document_id
), review_task_union AS (
  SELECT
    d.id AS document_id,
    rt.id AS task_id
  FROM app.documents d
  JOIN app.review_tasks rt
    ON rt.entity_type = 'document'
   AND rt.entity_id = d.id
   AND rt.status = 'OPEN'

  UNION ALL

  SELECT
    bc.document_id,
    rt.id AS task_id
  FROM app.book_copies bc
  JOIN app.review_tasks rt
    ON rt.entity_type = 'book_copy'
   AND rt.entity_id = bc.id
   AND rt.status = 'OPEN'
  WHERE bc.document_id IS NOT NULL
), review_task_agg AS (
  SELECT
    rtu.document_id,
    count(*)::int AS open_task_count
  FROM review_task_union rtu
  GROUP BY rtu.document_id
)
SELECT
  d.id AS document_id,
  d.core_document_id,
  d.legacy_doc_id,
  d.control_number,
  d.title_raw,
  d.title_normalized,
  d.title_display,
  d.subtitle_raw,
  d.subtitle_normalized,
  d.publication_place_raw,
  d.publication_place_normalized,
  d.publication_year,
  d.isbn_raw,
  d.isbn_normalized,
  d.isbn_is_valid,
  d.language_raw,
  d.language_code,
  d.publisher_id,
  p.display_name AS publisher_name,
  d.faculty_normalized,
  d.department_normalized,
  d.specialization_normalized,
  d.literature_type_normalized,
  d.needs_review AS document_needs_review,
  d.review_reason_codes AS document_review_reason_codes,
  coalesce(ta.title_variants_json, '[]'::jsonb) AS title_variants_json,
  coalesce(ta.title_variant_count, 0) AS title_variant_count,
  coalesce(aa.authors_json, '[]'::jsonb) AS authors_json,
  coalesce(aa.author_names, ARRAY[]::text[]) AS author_names,
  aa.primary_author_display,
  coalesce(sa.subjects_json, '[]'::jsonb) AS subjects_json,
  coalesce(sa.subject_names, ARRAY[]::text[]) AS subject_names,
  coalesce(ka.keywords_json, '[]'::jsonb) AS keywords_json,
  coalesce(ka.keyword_names, ARRAY[]::text[]) AS keyword_names,
  coalesce(ca.total_copy_count, 0) AS total_copy_count,
  coalesce(ca.available_copy_count, 0) AS available_copy_count,
  coalesce(ca.unavailable_copy_count, 0) AS unavailable_copy_count,
  coalesce(ca.review_copy_count, 0) AS review_copy_count,
  coalesce(ca.problem_copy_count, 0) AS problem_copy_count,
  coalesce(ca.orphan_copy_count, 0) AS orphan_copy_count,
  coalesce(ca.campus_count, 0) AS campus_count,
  coalesce(ca.service_point_count, 0) AS service_point_count,
  coalesce(ca.institution_unit_codes, ARRAY[]::text[]) AS institution_unit_codes,
  coalesce(ca.campus_codes, ARRAY[]::text[]) AS campus_codes,
  coalesce(ca.campus_names, ARRAY[]::text[]) AS campus_names,
  coalesce(ca.service_point_codes, ARRAY[]::text[]) AS service_point_codes,
  coalesce(ca.service_point_names, ARRAY[]::text[]) AS service_point_names,
  coalesce(rfa.document_flag_count, 0) AS document_flag_count,
  coalesce(rfa.copy_flag_count, 0) AS copy_flag_count,
  coalesce(rta.open_task_count, 0) AS open_task_count,
  (coalesce(rfa.document_flag_count, 0) + coalesce(rfa.copy_flag_count, 0) + coalesce(rta.open_task_count, 0) > 0) AS has_open_review,
  rfa.highest_review_severity,
  coalesce(rfa.review_issue_codes, ARRAY[]::text[]) AS review_issue_codes,
  trim(concat_ws(' ',
    d.title_display,
    d.title_raw,
    d.subtitle_raw,
    aa.author_search_text,
    sa.subject_search_text,
    ka.keyword_search_text,
    d.isbn_raw,
    d.isbn_normalized,
    p.display_name
  )) AS searchable_text,
  to_tsvector(
    'simple',
    trim(concat_ws(' ',
      d.title_display,
      d.title_raw,
      d.subtitle_raw,
      aa.author_search_text,
      sa.subject_search_text,
      ka.keyword_search_text,
      d.isbn_raw,
      d.isbn_normalized,
      p.display_name
    ))
  ) AS search_vector
FROM app.documents d
LEFT JOIN app.publishers p ON p.id = d.publisher_id
LEFT JOIN title_agg ta ON ta.document_id = d.id
LEFT JOIN author_agg aa ON aa.document_id = d.id
LEFT JOIN subject_agg sa ON sa.document_id = d.id
LEFT JOIN keyword_agg ka ON ka.document_id = d.id
LEFT JOIN copy_agg ca ON ca.document_id = d.id
LEFT JOIN review_flag_agg rfa ON rfa.document_id = d.id
LEFT JOIN review_task_agg rta ON rta.document_id = d.id;

CREATE UNIQUE INDEX idx_app_catalog_search_mv_document_id
  ON app.catalog_search_mv(document_id);

CREATE INDEX idx_app_catalog_search_mv_isbn_normalized
  ON app.catalog_search_mv(isbn_normalized);

CREATE INDEX idx_app_catalog_search_mv_language_code
  ON app.catalog_search_mv(language_code);

CREATE INDEX idx_app_catalog_search_mv_available_copy_count
  ON app.catalog_search_mv(available_copy_count);

CREATE INDEX idx_app_catalog_search_mv_has_open_review
  ON app.catalog_search_mv(has_open_review, highest_review_severity);

CREATE INDEX idx_app_catalog_search_mv_search_vector
  ON app.catalog_search_mv USING GIN(search_vector);

CREATE INDEX idx_app_catalog_search_mv_searchable_text_trgm
  ON app.catalog_search_mv USING GIN(searchable_text gin_trgm_ops);

CREATE INDEX idx_app_catalog_search_mv_title_display_trgm
  ON app.catalog_search_mv USING GIN(title_display gin_trgm_ops);

CREATE INDEX idx_app_catalog_search_mv_primary_author_trgm
  ON app.catalog_search_mv USING GIN(primary_author_display gin_trgm_ops);

CREATE INDEX idx_app_catalog_search_mv_campus_codes
  ON app.catalog_search_mv USING GIN(campus_codes);

CREATE INDEX idx_app_catalog_search_mv_service_point_codes
  ON app.catalog_search_mv USING GIN(service_point_codes);

CREATE VIEW app.document_availability_by_location_v AS
SELECT
  d.id AS document_id,
  d.legacy_doc_id,
  d.title_display,
  clf.institution_unit_id,
  clf.institution_unit_code,
  clf.institution_unit_name,
  clf.campus_id,
  clf.campus_code,
  clf.campus_name,
  clf.service_point_id,
  clf.service_point_code,
  clf.service_point_name,
  count(*)::int AS total_copy_count,
  count(*) FILTER (WHERE clf.is_available)::int AS available_copy_count,
  count(*) FILTER (WHERE NOT clf.is_available)::int AS unavailable_copy_count,
  count(*) FILTER (WHERE clf.needs_review)::int AS review_copy_count,
  count(*) FILTER (WHERE clf.is_problem_copy)::int AS problem_copy_count,
  count(*) FILTER (WHERE clf.is_orphan_copy)::int AS orphan_copy_count,
  array_agg(clf.copy_id ORDER BY clf.legacy_inv_id) AS copy_ids,
  array_agg(clf.legacy_inv_id ORDER BY clf.legacy_inv_id) AS legacy_inv_ids
FROM app.copy_location_facts_v clf
JOIN app.documents d ON d.id = clf.document_id
GROUP BY
  d.id,
  d.legacy_doc_id,
  d.title_display,
  clf.institution_unit_id,
  clf.institution_unit_code,
  clf.institution_unit_name,
  clf.campus_id,
  clf.campus_code,
  clf.campus_name,
  clf.service_point_id,
  clf.service_point_code,
  clf.service_point_name;

CREATE VIEW app.location_inventory_summary_v AS
SELECT
  clf.institution_unit_id,
  clf.institution_unit_code,
  clf.institution_unit_name,
  clf.campus_id,
  clf.campus_code,
  clf.campus_name,
  clf.service_point_id,
  clf.service_point_code,
  clf.service_point_name,
  count(*)::int AS total_copy_count,
  count(*) FILTER (WHERE clf.is_available)::int AS available_copy_count,
  count(*) FILTER (WHERE NOT clf.is_available)::int AS unavailable_copy_count,
  count(*) FILTER (WHERE clf.needs_review)::int AS review_copy_count,
  count(*) FILTER (WHERE clf.is_problem_copy)::int AS problem_copy_count,
  count(*) FILTER (WHERE clf.is_orphan_copy)::int AS orphan_copy_count,
  count(DISTINCT clf.document_id)::int AS distinct_document_count
FROM app.copy_location_facts_v clf
GROUP BY
  clf.institution_unit_id,
  clf.institution_unit_code,
  clf.institution_unit_name,
  clf.campus_id,
  clf.campus_code,
  clf.campus_name,
  clf.service_point_id,
  clf.service_point_code,
  clf.service_point_name;

CREATE VIEW app.catalog_filter_facets_v AS
SELECT
  'language'::text AS facet_type,
  coalesce(csm.language_code, 'und') AS facet_value,
  coalesce(csm.language_code, 'und') AS facet_label,
  count(*)::int AS document_count,
  sum(csm.total_copy_count)::int AS total_copy_count,
  sum(csm.available_copy_count)::int AS available_copy_count,
  count(*) FILTER (WHERE csm.has_open_review)::int AS review_document_count
FROM app.catalog_search_mv csm
GROUP BY coalesce(csm.language_code, 'und')

UNION ALL

SELECT
  'campus'::text AS facet_type,
  dav.campus_code AS facet_value,
  dav.campus_name AS facet_label,
  count(DISTINCT dav.document_id)::int AS document_count,
  sum(dav.total_copy_count)::int AS total_copy_count,
  sum(dav.available_copy_count)::int AS available_copy_count,
  count(DISTINCT dav.document_id) FILTER (WHERE dav.review_copy_count > 0 OR dav.problem_copy_count > 0)::int AS review_document_count
FROM app.document_availability_by_location_v dav
WHERE dav.campus_code IS NOT NULL
GROUP BY dav.campus_code, dav.campus_name

UNION ALL

SELECT
  'service_point'::text AS facet_type,
  dav.service_point_code AS facet_value,
  dav.service_point_name AS facet_label,
  count(DISTINCT dav.document_id)::int AS document_count,
  sum(dav.total_copy_count)::int AS total_copy_count,
  sum(dav.available_copy_count)::int AS available_copy_count,
  count(DISTINCT dav.document_id) FILTER (WHERE dav.review_copy_count > 0 OR dav.problem_copy_count > 0)::int AS review_document_count
FROM app.document_availability_by_location_v dav
WHERE dav.service_point_code IS NOT NULL
GROUP BY dav.service_point_code, dav.service_point_name

UNION ALL

SELECT
  'availability'::text AS facet_type,
  facet_value,
  facet_label,
  document_count,
  total_copy_count,
  available_copy_count,
  review_document_count
FROM (
  SELECT
    'available_now'::text AS facet_value,
    'Has available copies'::text AS facet_label,
    count(*) FILTER (WHERE csm.available_copy_count > 0)::int AS document_count,
    sum(csm.total_copy_count) FILTER (WHERE csm.available_copy_count > 0)::int AS total_copy_count,
    sum(csm.available_copy_count) FILTER (WHERE csm.available_copy_count > 0)::int AS available_copy_count,
    count(*) FILTER (WHERE csm.available_copy_count > 0 AND csm.has_open_review)::int AS review_document_count
  FROM app.catalog_search_mv csm

  UNION ALL

  SELECT
    'review_required'::text AS facet_value,
    'Needs review'::text AS facet_label,
    count(*) FILTER (WHERE csm.has_open_review)::int AS document_count,
    sum(csm.total_copy_count) FILTER (WHERE csm.has_open_review)::int AS total_copy_count,
    sum(csm.available_copy_count) FILTER (WHERE csm.has_open_review)::int AS available_copy_count,
    count(*) FILTER (WHERE csm.has_open_review)::int AS review_document_count
  FROM app.catalog_search_mv csm
)
availability_facets;

CREATE VIEW app.document_detail_v AS
SELECT
  d.id AS document_id,
  d.core_document_id,
  d.legacy_doc_id,
  d.control_number,
  d.title_display,
  d.title_normalized,
  d.title_raw,
  d.subtitle_raw,
  d.subtitle_normalized,
  d.publication_place_raw,
  d.publication_place_normalized,
  d.publication_year,
  d.language_raw,
  d.language_code,
  d.isbn_raw,
  d.isbn_normalized,
  d.isbn_is_valid,
  d.faculty_raw,
  d.faculty_normalized,
  d.department_raw,
  d.department_normalized,
  d.specialization_raw,
  d.specialization_normalized,
  d.literature_type_raw,
  d.literature_type_normalized,
  d.raw_marc,
  d.needs_review AS document_needs_review,
  d.review_reason_codes AS document_review_reason_codes,
  p.id AS publisher_id,
  p.display_name AS publisher_name,
  coalesce((
    SELECT jsonb_agg(author_row.author_json ORDER BY author_row.sort_bucket, author_row.sort_order, author_row.author_name)
    FROM (
      SELECT
        a.display_name AS author_name,
        da.sort_order,
        CASE WHEN da.is_primary THEN 0 ELSE 1 END AS sort_bucket,
        jsonb_build_object(
          'id', a.id,
          'name', a.display_name,
          'role', da.author_role,
          'isPrimary', da.is_primary,
          'sortOrder', da.sort_order
        ) AS author_json
      FROM app.document_authors da
      JOIN app.authors a ON a.id = da.author_id
      WHERE da.document_id = d.id
    ) author_row
  ), '[]'::jsonb) AS authors_json,
  coalesce((
    SELECT jsonb_agg(subject_row.subject_json ORDER BY subject_row.subject_name)
    FROM (
      SELECT
        s.display_subject AS subject_name,
        jsonb_build_object(
          'id', s.id,
          'label', s.display_subject,
          'sourceKind', ds.source_kind
        ) AS subject_json
      FROM app.document_subjects ds
      JOIN app.subjects s ON s.id = ds.subject_id
      WHERE ds.document_id = d.id
    ) subject_row
  ), '[]'::jsonb) AS subjects_json,
  coalesce((
    SELECT jsonb_agg(keyword_row.keyword_json ORDER BY keyword_row.keyword_name)
    FROM (
      SELECT
        k.display_keyword AS keyword_name,
        jsonb_build_object(
          'id', k.id,
          'label', k.display_keyword
        ) AS keyword_json
      FROM app.document_keywords dk
      JOIN app.keywords k ON k.id = dk.keyword_id
      WHERE dk.document_id = d.id
    ) keyword_row
  ), '[]'::jsonb) AS keywords_json,
  coalesce((
    SELECT jsonb_agg(title_row.title_json ORDER BY title_row.is_primary DESC, title_row.title_kind, title_row.value_display)
    FROM (
      SELECT
        dt.is_primary,
        dt.title_kind,
        dt.value_display,
        jsonb_build_object(
          'kind', dt.title_kind,
          'raw', dt.value_raw,
          'normalized', dt.value_normalized,
          'display', dt.value_display,
          'isPrimary', dt.is_primary
        ) AS title_json
      FROM app.document_titles dt
      WHERE dt.document_id = d.id
    ) title_row
  ), '[]'::jsonb) AS title_variants_json,
  coalesce((
    SELECT jsonb_agg(flag_row.flag_json ORDER BY flag_row.severity_rank DESC, flag_row.issue_code)
    FROM (
      SELECT
        dqf.issue_code,
        CASE dqf.severity
          WHEN 'CRITICAL' THEN 4
          WHEN 'HIGH' THEN 3
          WHEN 'MEDIUM' THEN 2
          WHEN 'LOW' THEN 1
          ELSE 0
        END AS severity_rank,
        jsonb_build_object(
          'id', dqf.id,
          'issueCode', dqf.issue_code,
          'severity', dqf.severity,
          'status', dqf.status,
          'rawValue', dqf.raw_value,
          'normalizedValue', dqf.normalized_value,
          'suggestedValue', dqf.suggested_value,
          'details', dqf.details
        ) AS flag_json
      FROM app.data_quality_flags dqf
      WHERE dqf.entity_type = 'document' AND dqf.entity_id = d.id
    ) flag_row
  ), '[]'::jsonb) AS document_quality_flags_json,
  coalesce((
    SELECT jsonb_agg(flag_row.flag_json ORDER BY flag_row.severity_rank DESC, flag_row.issue_code, flag_row.legacy_inv_id)
    FROM (
      SELECT
        bc.legacy_inv_id,
        dqf.issue_code,
        CASE dqf.severity
          WHEN 'CRITICAL' THEN 4
          WHEN 'HIGH' THEN 3
          WHEN 'MEDIUM' THEN 2
          WHEN 'LOW' THEN 1
          ELSE 0
        END AS severity_rank,
        jsonb_build_object(
          'id', dqf.id,
          'copyId', bc.id,
          'legacyInvId', bc.legacy_inv_id,
          'issueCode', dqf.issue_code,
          'severity', dqf.severity,
          'status', dqf.status,
          'rawValue', dqf.raw_value,
          'normalizedValue', dqf.normalized_value,
          'suggestedValue', dqf.suggested_value,
          'details', dqf.details
        ) AS flag_json
      FROM app.book_copies bc
      JOIN app.data_quality_flags dqf
        ON dqf.entity_type = 'book_copy'
       AND dqf.entity_id = bc.id
      WHERE bc.document_id = d.id
    ) flag_row
  ), '[]'::jsonb) AS copy_quality_flags_json,
  coalesce((
    SELECT jsonb_agg(task_row.task_json ORDER BY task_row.priority_rank DESC, task_row.title)
    FROM (
      SELECT
        rt.title,
        CASE rt.priority
          WHEN 'CRITICAL' THEN 4
          WHEN 'HIGH' THEN 3
          WHEN 'MEDIUM' THEN 2
          WHEN 'LOW' THEN 1
          ELSE 0
        END AS priority_rank,
        jsonb_build_object(
          'id', rt.id,
          'entityType', rt.entity_type,
          'entityId', rt.entity_id,
          'taskType', rt.task_type,
          'priority', rt.priority,
          'status', rt.status,
          'title', rt.title,
          'description', rt.description,
          'rawValue', rt.raw_value,
          'normalizedValue', rt.normalized_value,
          'suggestedValue', rt.suggested_value,
          'relatedFlagId', rt.related_flag_id
        ) AS task_json
      FROM app.review_tasks rt
      WHERE (rt.entity_type = 'document' AND rt.entity_id = d.id)
         OR (rt.entity_type = 'book_copy' AND EXISTS (
              SELECT 1
              FROM app.book_copies bc2
              WHERE bc2.id = rt.entity_id AND bc2.document_id = d.id
            ))
    ) task_row
  ), '[]'::jsonb) AS review_tasks_json,
  jsonb_build_object(
    'totalCopies', coalesce((SELECT count(*)::int FROM app.copy_location_facts_v clf WHERE clf.document_id = d.id), 0),
    'availableCopies', coalesce((SELECT count(*)::int FROM app.copy_location_facts_v clf WHERE clf.document_id = d.id AND clf.is_available), 0),
    'unavailableCopies', coalesce((SELECT count(*)::int FROM app.copy_location_facts_v clf WHERE clf.document_id = d.id AND NOT clf.is_available), 0),
    'reviewCopies', coalesce((SELECT count(*)::int FROM app.copy_location_facts_v clf WHERE clf.document_id = d.id AND clf.needs_review), 0),
    'problemCopies', coalesce((SELECT count(*)::int FROM app.copy_location_facts_v clf WHERE clf.document_id = d.id AND clf.is_problem_copy), 0),
    'orphanCopies', coalesce((SELECT count(*)::int FROM app.copy_location_facts_v clf WHERE clf.document_id = d.id AND clf.is_orphan_copy), 0)
  ) AS copy_summary_json,
  coalesce((
    SELECT jsonb_agg(campus_row.campus_json ORDER BY campus_row.campus_name)
    FROM (
      SELECT
        dav.campus_name,
        jsonb_build_object(
          'campusId', dav.campus_id,
          'campusCode', dav.campus_code,
          'campusName', dav.campus_name,
          'institutionUnitCode', dav.institution_unit_code,
          'totalCopies', dav.total_copy_count,
          'availableCopies', dav.available_copy_count,
          'unavailableCopies', dav.unavailable_copy_count,
          'reviewCopies', dav.review_copy_count,
          'problemCopies', dav.problem_copy_count
        ) AS campus_json
      FROM app.document_availability_by_location_v dav
      WHERE dav.document_id = d.id
      GROUP BY
        dav.campus_id,
        dav.campus_code,
        dav.campus_name,
        dav.institution_unit_code,
        dav.total_copy_count,
        dav.available_copy_count,
        dav.unavailable_copy_count,
        dav.review_copy_count,
        dav.problem_copy_count
    ) campus_row
  ), '[]'::jsonb) AS campus_distribution_json,
  coalesce((
    SELECT jsonb_agg(service_row.service_json ORDER BY service_row.campus_name, service_row.service_point_name)
    FROM (
      SELECT
        dav.campus_name,
        dav.service_point_name,
        jsonb_build_object(
          'campusCode', dav.campus_code,
          'campusName', dav.campus_name,
          'servicePointId', dav.service_point_id,
          'servicePointCode', dav.service_point_code,
          'servicePointName', dav.service_point_name,
          'totalCopies', dav.total_copy_count,
          'availableCopies', dav.available_copy_count,
          'unavailableCopies', dav.unavailable_copy_count,
          'reviewCopies', dav.review_copy_count,
          'problemCopies', dav.problem_copy_count,
          'legacyInvIds', dav.legacy_inv_ids
        ) AS service_json
      FROM app.document_availability_by_location_v dav
      WHERE dav.document_id = d.id
    ) service_row
  ), '[]'::jsonb) AS service_point_distribution_json
FROM app.documents d
LEFT JOIN app.publishers p ON p.id = d.publisher_id;

CREATE VIEW app.review_queue_v AS
WITH reader_location AS (
  SELECT
    rsp.reader_id,
    ARRAY(
      SELECT DISTINCT c.code
      FROM app.reader_service_points rsp2
      LEFT JOIN app.campuses c ON c.id = rsp2.campus_id
      WHERE rsp2.reader_id = rsp.reader_id AND c.code IS NOT NULL
      ORDER BY c.code
    ) AS campus_codes,
    ARRAY(
      SELECT DISTINCT b.code
      FROM app.reader_service_points rsp2
      LEFT JOIN app.branches b ON b.id = rsp2.service_point_id
      WHERE rsp2.reader_id = rsp.reader_id AND b.code IS NOT NULL
      ORDER BY b.code
    ) AS service_point_codes,
    ARRAY(
      SELECT DISTINCT b.name
      FROM app.reader_service_points rsp2
      LEFT JOIN app.branches b ON b.id = rsp2.service_point_id
      WHERE rsp2.reader_id = rsp.reader_id AND b.name IS NOT NULL
      ORDER BY b.name
    ) AS service_point_names
  FROM app.reader_service_points rsp
  GROUP BY rsp.reader_id
)
SELECT
  dqf.id AS flag_id,
  rt.id AS task_id,
  dqf.entity_type,
  dqf.entity_id,
  dqf.issue_code,
  dqf.severity,
  dqf.status AS flag_status,
  dqf.raw_value,
  dqf.normalized_value,
  dqf.suggested_value,
  dqf.confidence_score,
  dqf.details,
  rt.task_type,
  rt.priority AS task_priority,
  rt.status AS task_status,
  rt.title AS task_title,
  rt.description AS task_description,
  rt.assigned_to,
  dqf.created_at AS flagged_at,
  rt.created_at AS task_created_at,
  coalesce(doc.id, copy_doc.id) AS document_id,
  coalesce(doc.legacy_doc_id, copy_doc.legacy_doc_id) AS legacy_doc_id,
  coalesce(doc.title_display, copy_doc.title_display) AS title_display,
  coalesce(doc.isbn_normalized, copy_doc.isbn_normalized) AS isbn_normalized,
  coalesce(doc.language_code, copy_doc.language_code) AS language_code,
  copy_row.copy_id,
  copy_row.legacy_inv_id,
  copy_row.inventory_number_normalized,
  reader_row.reader_id,
  reader_row.legacy_reader_id,
  reader_row.full_name_normalized,
  coalesce(copy_row.institution_unit_code, NULL) AS institution_unit_code,
  CASE
    WHEN dqf.entity_type = 'document' THEN coalesce(csm.campus_codes, ARRAY[]::text[])
    WHEN dqf.entity_type = 'book_copy' THEN coalesce(ARRAY[copy_row.campus_code], ARRAY[]::text[])
    WHEN dqf.entity_type = 'reader' THEN coalesce(rl.campus_codes, ARRAY[]::text[])
    ELSE ARRAY[]::text[]
  END AS campus_codes,
  CASE
    WHEN dqf.entity_type = 'document' THEN coalesce(csm.service_point_codes, ARRAY[]::text[])
    WHEN dqf.entity_type = 'book_copy' THEN coalesce(ARRAY[copy_row.service_point_code], ARRAY[]::text[])
    WHEN dqf.entity_type = 'reader' THEN coalesce(rl.service_point_codes, ARRAY[]::text[])
    ELSE ARRAY[]::text[]
  END AS service_point_codes,
  CASE
    WHEN dqf.entity_type = 'reader' THEN coalesce(rl.service_point_names, ARRAY[]::text[])
    ELSE ARRAY[]::text[]
  END AS reader_service_point_names
FROM app.data_quality_flags dqf
LEFT JOIN app.review_tasks rt ON rt.related_flag_id = dqf.id
LEFT JOIN app.documents doc
  ON dqf.entity_type = 'document'
 AND dqf.entity_id = doc.id
LEFT JOIN app.catalog_search_mv csm
  ON csm.document_id = doc.id
LEFT JOIN (
  SELECT
    clf.copy_id,
    clf.document_id,
    clf.legacy_inv_id,
    clf.inventory_number_normalized,
    clf.institution_unit_code,
    clf.campus_code,
    clf.service_point_code
  FROM app.copy_location_facts_v clf
) copy_row
  ON dqf.entity_type = 'book_copy'
 AND dqf.entity_id = copy_row.copy_id
LEFT JOIN app.documents copy_doc
  ON copy_doc.id = copy_row.document_id
LEFT JOIN app.catalog_search_mv copy_csm
  ON copy_csm.document_id = copy_doc.id
LEFT JOIN (
  SELECT
    r.id AS reader_id,
    r.legacy_reader_id,
    r.full_name_normalized
  FROM app.readers r
) reader_row
  ON dqf.entity_type = 'reader'
 AND dqf.entity_id = reader_row.reader_id
LEFT JOIN reader_location rl
  ON rl.reader_id = reader_row.reader_id
WHERE dqf.status = 'OPEN';