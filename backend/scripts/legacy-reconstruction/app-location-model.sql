CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE SCHEMA IF NOT EXISTS app;

CREATE TABLE IF NOT EXISTS app.institution_units (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  code text NOT NULL UNIQUE,
  name text NOT NULL,
  unit_kind text NOT NULL,
  is_active boolean NOT NULL DEFAULT true,
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.campuses (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  institution_unit_id uuid NOT NULL REFERENCES app.institution_units(id) ON DELETE CASCADE,
  code text NOT NULL UNIQUE,
  name text NOT NULL,
  campus_kind text NOT NULL,
  mapping_status text NOT NULL DEFAULT 'CONFIRMED',
  needs_review boolean NOT NULL DEFAULT false,
  review_reason_codes text[] NOT NULL DEFAULT ARRAY[]::text[],
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

ALTER TABLE app.branches
  ADD COLUMN IF NOT EXISTS institution_unit_id uuid REFERENCES app.institution_units(id) ON DELETE SET NULL,
  ADD COLUMN IF NOT EXISTS campus_id uuid REFERENCES app.campuses(id) ON DELETE SET NULL,
  ADD COLUMN IF NOT EXISTS branch_kind text NOT NULL DEFAULT 'SERVICE_POINT',
  ADD COLUMN IF NOT EXISTS legacy_location_code text,
  ADD COLUMN IF NOT EXISTS mapping_status text NOT NULL DEFAULT 'UNMAPPED',
  ADD COLUMN IF NOT EXISTS mapping_confidence numeric(5,2),
  ADD COLUMN IF NOT EXISTS mapping_method text,
  ADD COLUMN IF NOT EXISTS location_notes text;

ALTER TABLE app.siglas
  ADD COLUMN IF NOT EXISTS institution_unit_id uuid REFERENCES app.institution_units(id) ON DELETE SET NULL,
  ADD COLUMN IF NOT EXISTS campus_id uuid REFERENCES app.campuses(id) ON DELETE SET NULL,
  ADD COLUMN IF NOT EXISTS mapping_status text NOT NULL DEFAULT 'UNMAPPED',
  ADD COLUMN IF NOT EXISTS mapping_confidence numeric(5,2),
  ADD COLUMN IF NOT EXISTS mapping_method text,
  ADD COLUMN IF NOT EXISTS location_notes text;

ALTER TABLE app.book_copies
  ADD COLUMN IF NOT EXISTS institution_unit_id uuid REFERENCES app.institution_units(id) ON DELETE SET NULL,
  ADD COLUMN IF NOT EXISTS campus_id uuid REFERENCES app.campuses(id) ON DELETE SET NULL,
  ADD COLUMN IF NOT EXISTS location_mapping_status text NOT NULL DEFAULT 'UNMAPPED',
  ADD COLUMN IF NOT EXISTS location_mapping_confidence numeric(5,2);

CREATE TABLE IF NOT EXISTS app.reader_service_points (
  reader_id uuid NOT NULL REFERENCES app.readers(id) ON DELETE CASCADE,
  service_point_id uuid NOT NULL REFERENCES app.branches(id) ON DELETE CASCADE,
  institution_unit_id uuid REFERENCES app.institution_units(id) ON DELETE SET NULL,
  campus_id uuid REFERENCES app.campuses(id) ON DELETE SET NULL,
  source_kind text NOT NULL,
  mapping_status text NOT NULL DEFAULT 'CONFIRMED',
  mapping_confidence numeric(5,2),
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  needs_review boolean NOT NULL DEFAULT false,
  review_reason_codes text[] NOT NULL DEFAULT ARRAY[]::text[],
  created_at timestamptz NOT NULL DEFAULT now(),
  PRIMARY KEY (reader_id, service_point_id, source_kind)
);

CREATE TABLE IF NOT EXISTS app.location_mapping_candidates (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  entity_type text NOT NULL,
  entity_id uuid NOT NULL,
  signal_type text NOT NULL,
  signal_value text,
  suggested_institution_unit_id uuid REFERENCES app.institution_units(id) ON DELETE SET NULL,
  suggested_campus_id uuid REFERENCES app.campuses(id) ON DELETE SET NULL,
  suggested_service_point_id uuid REFERENCES app.branches(id) ON DELETE SET NULL,
  suggested_sigla_id uuid REFERENCES app.siglas(id) ON DELETE SET NULL,
  confidence_score numeric(5,2),
  mapping_method text,
  status text NOT NULL DEFAULT 'SUGGESTED',
  details jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now(),
  UNIQUE (entity_type, entity_id, signal_type, signal_value)
);

CREATE INDEX IF NOT EXISTS idx_app_branches_campus_id ON app.branches(campus_id);
CREATE INDEX IF NOT EXISTS idx_app_siglas_campus_id ON app.siglas(campus_id);
CREATE INDEX IF NOT EXISTS idx_app_book_copies_campus_id ON app.book_copies(campus_id);
CREATE INDEX IF NOT EXISTS idx_app_reader_service_points_campus_id ON app.reader_service_points(campus_id);
CREATE INDEX IF NOT EXISTS idx_app_location_mapping_candidates_status ON app.location_mapping_candidates(status, signal_type);

INSERT INTO app.institution_units (code, name, unit_kind)
VALUES
  ('COLLEGE', 'KazUTB College', 'COLLEGE'),
  ('UNIVERSITY', 'KazUTB University', 'UNIVERSITY')
ON CONFLICT (code) DO UPDATE SET
  name = EXCLUDED.name,
  unit_kind = EXCLUDED.unit_kind,
  updated_at = now();

WITH units AS (
  SELECT code, id FROM app.institution_units
)
INSERT INTO app.campuses (institution_unit_id, code, name, campus_kind, mapping_status, needs_review, review_reason_codes)
SELECT u.id, x.code, x.name, x.campus_kind, x.mapping_status, x.needs_review, x.review_reason_codes
FROM units u
JOIN (
  VALUES
    ('COLLEGE', 'COLLEGE_MAIN', 'College Main Library Area', 'COLLEGE_SITE', 'CONFIRMED', false, ARRAY[]::text[]),
    ('UNIVERSITY', 'UNIVERSITY_ECONOMIC', 'University Economic Location', 'UNIVERSITY_SITE', 'CONFIRMED', false, ARRAY[]::text[]),
    ('UNIVERSITY', 'UNIVERSITY_TECHNOLOGICAL', 'University Technological Location', 'UNIVERSITY_SITE', 'CONFIRMED', false, ARRAY[]::text[]),
    ('UNIVERSITY', 'UNIVERSITY_CENTRAL', 'University Central / Shared Library Location', 'UNIVERSITY_SITE', 'CONFIRMED', false, ARRAY[]::text[])
) AS x(unit_code, code, name, campus_kind, mapping_status, needs_review, review_reason_codes)
  ON x.unit_code = u.code
ON CONFLICT (code) DO UPDATE SET
  institution_unit_id = EXCLUDED.institution_unit_id,
  name = EXCLUDED.name,
  campus_kind = EXCLUDED.campus_kind,
  mapping_status = EXCLUDED.mapping_status,
  needs_review = EXCLUDED.needs_review,
  review_reason_codes = EXCLUDED.review_reason_codes,
  updated_at = now();

WITH campus_map AS (
  SELECT
    b.id AS branch_id,
    iu.id AS institution_unit_id,
    c.id AS campus_id,
    CASE
      WHEN b.code = '1' AND b.legacy_bookpoint_id = 2 THEN 'CONFIRMED'
      WHEN b.code = '2' AND b.legacy_bookpoint_id = 3 THEN 'CONFIRMED'
      WHEN b.code = '3' AND b.legacy_bookpoint_id = 4 THEN 'CONFIRMED'
      WHEN b.code IN ('KSTLIB', 'BOOKPOINT_5') THEN 'CONFIRMED'
      ELSE 'UNMAPPED'
    END AS mapping_status,
    CASE
      WHEN b.code = '1' AND b.legacy_bookpoint_id = 2 THEN 0.99
      WHEN b.code = '2' AND b.legacy_bookpoint_id = 3 THEN 0.99
      WHEN b.code = '3' AND b.legacy_bookpoint_id = 4 THEN 0.99
      WHEN b.code = 'KSTLIB' THEN 0.95
      WHEN b.code = 'BOOKPOINT_5' THEN 0.90
      ELSE 0.10
    END AS mapping_confidence,
    CASE
      WHEN b.code = '1' AND b.legacy_bookpoint_id = 2 THEN 'legacy_bookpoint_abonement_1_to_economic'
      WHEN b.code = '2' AND b.legacy_bookpoint_id = 3 THEN 'legacy_bookpoint_abonement_2_to_technological'
      WHEN b.code = '3' AND b.legacy_bookpoint_id = 4 THEN 'legacy_bookpoint_abonement_3_to_college'
      WHEN b.code = 'KSTLIB' THEN 'legacy_kstlib_to_university_central'
      WHEN b.code = 'BOOKPOINT_5' THEN 'legacy_nb_kazutb_to_university_central'
      ELSE 'unmapped_branch_signal'
    END AS mapping_method,
    CASE
      WHEN b.code = '1' THEN 'ab1'
      WHEN b.code = '2' THEN 'ab2'
      WHEN b.code = '3' THEN 'college'
      ELSE b.code
    END AS legacy_location_code,
    CASE
      WHEN b.code = '1' THEN 'UNIVERSITY_ECONOMIC'
      WHEN b.code = '2' THEN 'UNIVERSITY_TECHNOLOGICAL'
      WHEN b.code = '3' THEN 'COLLEGE_MAIN'
      WHEN b.code IN ('KSTLIB', 'BOOKPOINT_5') THEN 'UNIVERSITY_CENTRAL'
      ELSE NULL
    END AS campus_code,
    CASE
      WHEN b.code = '3' THEN 'COLLEGE'
      WHEN b.code IN ('1', '2', 'KSTLIB', 'BOOKPOINT_5') THEN 'UNIVERSITY'
      ELSE NULL
    END AS unit_code
  FROM app.branches b
  LEFT JOIN app.institution_units iu ON iu.code = CASE
    WHEN b.code = '3' THEN 'COLLEGE'
    WHEN b.code IN ('1', '2', 'KSTLIB', 'BOOKPOINT_5') THEN 'UNIVERSITY'
    ELSE NULL
  END
  LEFT JOIN app.campuses c ON c.code = CASE
    WHEN b.code = '1' THEN 'UNIVERSITY_ECONOMIC'
    WHEN b.code = '2' THEN 'UNIVERSITY_TECHNOLOGICAL'
    WHEN b.code = '3' THEN 'COLLEGE_MAIN'
    WHEN b.code IN ('KSTLIB', 'BOOKPOINT_5') THEN 'UNIVERSITY_CENTRAL'
    ELSE NULL
  END
)
UPDATE app.branches b
SET institution_unit_id = cm.institution_unit_id,
    campus_id = cm.campus_id,
    branch_kind = 'SERVICE_POINT',
    legacy_location_code = cm.legacy_location_code,
    mapping_status = cm.mapping_status,
    mapping_confidence = cm.mapping_confidence,
    mapping_method = cm.mapping_method,
    location_notes = CASE WHEN cm.mapping_status = 'SUGGESTED' THEN 'Needs business confirmation for central/shared location semantics' ELSE NULL END,
    needs_review = (
      EXISTS (
        SELECT 1
        FROM unnest(b.review_reason_codes) AS existing_reason
        WHERE existing_reason <> 'location_mapping_requires_confirmation'
      )
      OR cm.mapping_status <> 'CONFIRMED'
    ),
    review_reason_codes = ARRAY(
      SELECT DISTINCT x
      FROM unnest(
        ARRAY(
          SELECT existing_reason
          FROM unnest(b.review_reason_codes) AS existing_reason
          WHERE existing_reason <> 'location_mapping_requires_confirmation'
        ) ||
        CASE WHEN cm.mapping_status <> 'CONFIRMED' THEN ARRAY['location_mapping_requires_confirmation']::text[] ELSE ARRAY[]::text[] END
      ) AS t(x)
      WHERE x IS NOT NULL
    ),
    updated_at = now()
FROM campus_map cm
WHERE b.id = cm.branch_id;

WITH sigla_map AS (
  SELECT
    s.id AS sigla_id,
    CASE
      WHEN s.legacy_sigla_id = 7 THEN 'UNIVERSITY'
      WHEN s.legacy_sigla_id = 8 THEN 'UNIVERSITY'
      WHEN s.legacy_sigla_id = 9 THEN 'COLLEGE'
      WHEN s.legacy_sigla_id = 6 THEN 'UNIVERSITY'
      ELSE NULL
    END AS unit_code,
    CASE
      WHEN s.legacy_sigla_id = 7 THEN 'UNIVERSITY_ECONOMIC'
      WHEN s.legacy_sigla_id = 8 THEN 'UNIVERSITY_TECHNOLOGICAL'
      WHEN s.legacy_sigla_id = 9 THEN 'COLLEGE_MAIN'
      WHEN s.legacy_sigla_id = 6 THEN 'UNIVERSITY_CENTRAL'
      ELSE NULL
    END AS campus_code,
    CASE
      WHEN s.legacy_sigla_id IN (7, 8, 9) THEN 'CONFIRMED'
      WHEN s.legacy_sigla_id = 6 THEN 'CONFIRMED'
      ELSE 'UNMAPPED'
    END AS mapping_status,
    CASE
      WHEN s.legacy_sigla_id IN (7, 8, 9) THEN 0.99
      WHEN s.legacy_sigla_id = 6 THEN 0.95
      ELSE 0.10
    END AS mapping_confidence,
    CASE
      WHEN s.legacy_sigla_id = 7 THEN 'legacy_sigla_ab1_to_economic'
      WHEN s.legacy_sigla_id = 8 THEN 'legacy_sigla_ab2_to_technological'
      WHEN s.legacy_sigla_id = 9 THEN 'legacy_sigla_college_to_college_main'
      WHEN s.legacy_sigla_id = 6 THEN 'legacy_sigla_kstlib_to_university_central'
      ELSE 'unmapped_sigla_signal'
    END AS mapping_method
  FROM app.siglas s
)
UPDATE app.siglas s
SET institution_unit_id = iu.id,
    campus_id = c.id,
    mapping_status = sm.mapping_status,
    mapping_confidence = sm.mapping_confidence,
    mapping_method = sm.mapping_method,
    location_notes = CASE WHEN sm.mapping_status = 'SUGGESTED' THEN 'KSTLIB central/shared mapping should be confirmed by librarians' ELSE NULL END,
    needs_review = (
      EXISTS (
        SELECT 1
        FROM unnest(s.review_reason_codes) AS existing_reason
        WHERE existing_reason <> 'location_mapping_requires_confirmation'
      )
      OR sm.mapping_status <> 'CONFIRMED'
    ),
    review_reason_codes = ARRAY(
      SELECT DISTINCT x
      FROM unnest(
        ARRAY(
          SELECT existing_reason
          FROM unnest(s.review_reason_codes) AS existing_reason
          WHERE existing_reason <> 'location_mapping_requires_confirmation'
        ) ||
        CASE WHEN sm.mapping_status <> 'CONFIRMED' THEN ARRAY['location_mapping_requires_confirmation']::text[] ELSE ARRAY[]::text[] END
      ) AS t(x)
      WHERE x IS NOT NULL
    ),
    updated_at = now()
FROM sigla_map sm
LEFT JOIN app.institution_units iu ON iu.code = sm.unit_code
LEFT JOIN app.campuses c ON c.code = sm.campus_code
WHERE s.id = sm.sigla_id;

WITH copy_location AS (
  SELECT
    bc.id,
    coalesce(sig.institution_unit_id, br.institution_unit_id) AS institution_unit_id,
    coalesce(sig.campus_id, br.campus_id) AS campus_id,
    CASE
      WHEN sig.campus_id IS NOT NULL AND br.campus_id IS NOT NULL AND sig.campus_id <> br.campus_id THEN 'CONFLICT'
      WHEN coalesce(sig.campus_id, br.campus_id) IS NOT NULL THEN coalesce(sig.mapping_status, br.mapping_status, 'CONFIRMED')
      ELSE 'UNMAPPED'
    END AS location_mapping_status,
    CASE
      WHEN sig.campus_id IS NOT NULL AND br.campus_id IS NOT NULL AND sig.campus_id <> br.campus_id THEN 0.10
      ELSE greatest(coalesce(sig.mapping_confidence, 0), coalesce(br.mapping_confidence, 0))
    END AS location_mapping_confidence,
    CASE
      WHEN sig.campus_id IS NOT NULL AND br.campus_id IS NOT NULL AND sig.campus_id <> br.campus_id THEN 'location_mapping_conflict'
      WHEN coalesce(sig.campus_id, br.campus_id) IS NULL THEN 'location_unmapped'
      WHEN coalesce(sig.mapping_status, br.mapping_status, 'CONFIRMED') <> 'CONFIRMED' THEN 'location_mapping_requires_confirmation'
      ELSE NULL
    END AS location_issue_code
  FROM app.book_copies bc
  LEFT JOIN app.branches br ON br.id = bc.branch_id
  LEFT JOIN app.siglas sig ON sig.id = bc.sigla_id
)
UPDATE app.book_copies bc
SET institution_unit_id = cl.institution_unit_id,
    campus_id = cl.campus_id,
    location_mapping_status = cl.location_mapping_status,
    location_mapping_confidence = cl.location_mapping_confidence,
    needs_review = (
      EXISTS (
        SELECT 1
        FROM unnest(bc.review_reason_codes) AS existing_reason
        WHERE existing_reason NOT IN ('location_mapping_requires_confirmation', 'location_mapping_conflict', 'location_unmapped')
      )
      OR cl.location_issue_code IS NOT NULL
    ),
    review_reason_codes = ARRAY(
      SELECT DISTINCT x
      FROM unnest(
        ARRAY(
          SELECT existing_reason
          FROM unnest(bc.review_reason_codes) AS existing_reason
          WHERE existing_reason NOT IN ('location_mapping_requires_confirmation', 'location_mapping_conflict', 'location_unmapped')
        ) ||
        CASE WHEN cl.location_issue_code IS NOT NULL THEN ARRAY[cl.location_issue_code]::text[] ELSE ARRAY[]::text[] END
      ) AS t(x)
      WHERE x IS NOT NULL
    ),
    updated_at = now()
FROM copy_location cl
WHERE bc.id = cl.id;

INSERT INTO app.reader_service_points (
  reader_id,
  service_point_id,
  institution_unit_id,
  campus_id,
  source_kind,
  mapping_status,
  mapping_confidence,
  source_payload,
  needs_review,
  review_reason_codes
)
SELECT DISTINCT
  ar.id,
  ab.id,
  ab.institution_unit_id,
  ab.campus_id,
  'raw_rdrbp',
  coalesce(ab.mapping_status, 'UNMAPPED'),
  ab.mapping_confidence,
  rbp.source_payload,
  (ab.id IS NULL OR ab.campus_id IS NULL OR ab.mapping_status <> 'CONFIRMED'),
  array_remove(ARRAY[
    CASE WHEN ab.id IS NULL THEN 'reader_service_point_unmapped' END,
    CASE WHEN ab.id IS NOT NULL AND ab.campus_id IS NULL THEN 'reader_campus_unmapped' END,
    CASE WHEN ab.mapping_status IS NOT NULL AND ab.mapping_status <> 'CONFIRMED' THEN 'reader_location_mapping_requires_confirmation' END
  ]::text[], NULL)
FROM raw.rdrbp rbp
JOIN app.readers ar ON ar.legacy_reader_id = rbp.legacy_reader_id
LEFT JOIN app.branches ab ON ab.legacy_bookpoint_id = rbp.legacy_bookpoint_id
ON CONFLICT (reader_id, service_point_id, source_kind) DO UPDATE SET
  institution_unit_id = EXCLUDED.institution_unit_id,
  campus_id = EXCLUDED.campus_id,
  mapping_status = EXCLUDED.mapping_status,
  mapping_confidence = EXCLUDED.mapping_confidence,
  source_payload = EXCLUDED.source_payload,
  needs_review = EXCLUDED.needs_review,
  review_reason_codes = EXCLUDED.review_reason_codes;

DELETE FROM app.review_tasks
WHERE task_type = 'LOCATION_MAPPING_REVIEW';

DELETE FROM app.data_quality_flags
WHERE issue_code IN (
  'location_mapping_requires_confirmation',
  'location_mapping_conflict',
  'reader_location_mapping_requires_confirmation'
);

DELETE FROM app.location_mapping_candidates;

INSERT INTO app.location_mapping_candidates (
  entity_type,
  entity_id,
  signal_type,
  signal_value,
  suggested_institution_unit_id,
  suggested_campus_id,
  suggested_service_point_id,
  suggested_sigla_id,
  confidence_score,
  mapping_method,
  status,
  details
)
SELECT
  'service_point',
  b.id,
  'branch_code',
  b.code,
  b.institution_unit_id,
  b.campus_id,
  b.id,
  NULL,
  b.mapping_confidence,
  b.mapping_method,
  b.mapping_status,
  jsonb_build_object('legacy_bookpoint_id', b.legacy_bookpoint_id, 'name', b.name)
FROM app.branches b
WHERE b.mapping_status <> 'CONFIRMED'
ON CONFLICT (entity_type, entity_id, signal_type, signal_value) DO UPDATE SET
  suggested_institution_unit_id = EXCLUDED.suggested_institution_unit_id,
  suggested_campus_id = EXCLUDED.suggested_campus_id,
  suggested_service_point_id = EXCLUDED.suggested_service_point_id,
  confidence_score = EXCLUDED.confidence_score,
  mapping_method = EXCLUDED.mapping_method,
  status = EXCLUDED.status,
  details = EXCLUDED.details;

INSERT INTO app.location_mapping_candidates (
  entity_type,
  entity_id,
  signal_type,
  signal_value,
  suggested_institution_unit_id,
  suggested_campus_id,
  suggested_service_point_id,
  suggested_sigla_id,
  confidence_score,
  mapping_method,
  status,
  details
)
SELECT
  'storage_sigla',
  s.id,
  'sigla_code',
  s.shortname,
  s.institution_unit_id,
  s.campus_id,
  s.branch_id,
  s.id,
  s.mapping_confidence,
  s.mapping_method,
  s.mapping_status,
  jsonb_build_object('legacy_sigla_id', s.legacy_sigla_id)
FROM app.siglas s
WHERE s.mapping_status <> 'CONFIRMED'
ON CONFLICT (entity_type, entity_id, signal_type, signal_value) DO UPDATE SET
  suggested_institution_unit_id = EXCLUDED.suggested_institution_unit_id,
  suggested_campus_id = EXCLUDED.suggested_campus_id,
  suggested_service_point_id = EXCLUDED.suggested_service_point_id,
  suggested_sigla_id = EXCLUDED.suggested_sigla_id,
  confidence_score = EXCLUDED.confidence_score,
  mapping_method = EXCLUDED.mapping_method,
  status = EXCLUDED.status,
  details = EXCLUDED.details;

INSERT INTO app.source_lineage (entity_type, entity_id, source_schema, source_table, source_key, source_payload)
SELECT 'service_point', b.id, 'core', 'library_branches', coalesce(b.legacy_bookpoint_id::text, b.code), b.source_payload
FROM app.branches b
ON CONFLICT DO NOTHING;

INSERT INTO app.source_lineage (entity_type, entity_id, source_schema, source_table, source_key, source_payload)
SELECT 'storage_sigla', s.id, 'core', 'storage_siglas', s.legacy_sigla_id::text, s.source_payload
FROM app.siglas s
ON CONFLICT DO NOTHING;

INSERT INTO app.data_quality_flags (
  entity_type,
  entity_id,
  issue_code,
  severity,
  status,
  raw_value,
  normalized_value,
  suggested_value,
  auto_generated,
  confidence_score,
  details
)
SELECT
  'service_point',
  b.id,
  'location_mapping_requires_confirmation',
  'MEDIUM',
  'OPEN',
  b.name,
  c.name,
  c.name,
  true,
  b.mapping_confidence,
  jsonb_build_object('branch_code', b.code, 'mapping_status', b.mapping_status, 'mapping_method', b.mapping_method)
FROM app.branches b
LEFT JOIN app.campuses c ON c.id = b.campus_id
WHERE b.mapping_status <> 'CONFIRMED'
ON CONFLICT (entity_type, entity_id, issue_code, auto_generated) DO NOTHING;

INSERT INTO app.data_quality_flags (
  entity_type,
  entity_id,
  issue_code,
  severity,
  status,
  raw_value,
  normalized_value,
  suggested_value,
  auto_generated,
  confidence_score,
  details
)
SELECT
  'book_copy',
  bc.id,
  CASE
    WHEN bc.location_mapping_status = 'CONFLICT' THEN 'location_mapping_conflict'
    ELSE 'location_mapping_requires_confirmation'
  END,
  CASE WHEN bc.location_mapping_status = 'CONFLICT' THEN 'HIGH' ELSE 'MEDIUM' END,
  'OPEN',
  bc.branch_hint_raw,
  coalesce(c.name, '(unmapped)'),
  coalesce(c.name, '(unmapped)'),
  true,
  bc.location_mapping_confidence,
  jsonb_build_object('legacy_inv_id', bc.legacy_inv_id, 'location_mapping_status', bc.location_mapping_status)
FROM app.book_copies bc
LEFT JOIN app.campuses c ON c.id = bc.campus_id
WHERE bc.location_mapping_status IN ('SUGGESTED', 'CONFLICT', 'UNMAPPED')
ON CONFLICT (entity_type, entity_id, issue_code, auto_generated) DO NOTHING;

INSERT INTO app.data_quality_flags (
  entity_type,
  entity_id,
  issue_code,
  severity,
  status,
  raw_value,
  normalized_value,
  suggested_value,
  auto_generated,
  confidence_score,
  details
)
SELECT
  'reader',
  rsp.reader_id,
  'reader_location_mapping_requires_confirmation',
  'MEDIUM',
  'OPEN',
  rsp.source_payload::text,
  coalesce(c.name, '(unmapped)'),
  coalesce(c.name, '(unmapped)'),
  true,
  rsp.mapping_confidence,
  jsonb_build_object('mapping_status', rsp.mapping_status, 'source_kind', rsp.source_kind)
FROM app.reader_service_points rsp
LEFT JOIN app.campuses c ON c.id = rsp.campus_id
WHERE rsp.mapping_status <> 'CONFIRMED' OR rsp.needs_review
ON CONFLICT (entity_type, entity_id, issue_code, auto_generated) DO NOTHING;

INSERT INTO app.review_tasks (
  entity_type,
  entity_id,
  task_type,
  priority,
  status,
  title,
  description,
  raw_value,
  normalized_value,
  suggested_value,
  related_flag_id
)
SELECT
  dqf.entity_type,
  dqf.entity_id,
  'LOCATION_MAPPING_REVIEW',
  CASE WHEN dqf.severity = 'HIGH' THEN 'HIGH' ELSE 'MEDIUM' END,
  'OPEN',
  concat('Review location mapping: ', dqf.issue_code),
  'Auto-generated location mapping review task',
  dqf.raw_value,
  dqf.normalized_value,
  dqf.suggested_value,
  dqf.id
FROM app.data_quality_flags dqf
WHERE dqf.issue_code IN (
  'location_mapping_requires_confirmation',
  'location_mapping_conflict',
  'reader_location_mapping_requires_confirmation'
)
ON CONFLICT (related_flag_id) DO NOTHING;
