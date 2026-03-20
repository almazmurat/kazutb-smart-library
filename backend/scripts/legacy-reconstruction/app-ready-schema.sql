CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE SCHEMA IF NOT EXISTS app;

CREATE TABLE IF NOT EXISTS app.normalization_runs (
  id uuid PRIMARY KEY,
  run_type text NOT NULL,
  status text NOT NULL,
  started_at timestamptz NOT NULL DEFAULT now(),
  completed_at timestamptz,
  notes text,
  metrics jsonb NOT NULL DEFAULT '{}'::jsonb
);

CREATE TABLE IF NOT EXISTS app.publishers (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_publisher_id uuid UNIQUE,
  normalized_name text NOT NULL UNIQUE,
  display_name text NOT NULL,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.authors (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_author_id uuid UNIQUE,
  normalized_name text NOT NULL UNIQUE,
  display_name text NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.subjects (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_subject_id uuid UNIQUE,
  normalized_subject text NOT NULL UNIQUE,
  display_subject text NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.keywords (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_keyword_id uuid UNIQUE,
  normalized_keyword text NOT NULL UNIQUE,
  display_keyword text NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.branches (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_branch_id uuid UNIQUE,
  legacy_bookpoint_id integer,
  legacy_lib_id integer,
  code text NOT NULL UNIQUE,
  name text NOT NULL,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  needs_review boolean NOT NULL DEFAULT false,
  review_reason_codes text[] NOT NULL DEFAULT ARRAY[]::text[],
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.siglas (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_sigla_id uuid UNIQUE,
  legacy_sigla_id integer,
  branch_id uuid REFERENCES app.branches(id) ON DELETE SET NULL,
  shortname text,
  storage integer,
  access_level integer,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  needs_review boolean NOT NULL DEFAULT false,
  review_reason_codes text[] NOT NULL DEFAULT ARRAY[]::text[],
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.documents (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_document_id uuid UNIQUE,
  legacy_doc_id integer NOT NULL UNIQUE,
  control_number text,
  title_raw text,
  title_normalized text,
  title_display text,
  subtitle_raw text,
  subtitle_normalized text,
  publication_place_raw text,
  publication_place_normalized text,
  publication_year integer,
  isbn_raw text,
  isbn_normalized text,
  isbn_is_valid boolean NOT NULL DEFAULT false,
  language_raw text,
  language_code text,
  publisher_id uuid REFERENCES app.publishers(id) ON DELETE SET NULL,
  faculty_raw text,
  faculty_normalized text,
  department_raw text,
  department_normalized text,
  specialization_raw text,
  specialization_normalized text,
  literature_type_raw text,
  literature_type_normalized text,
  raw_marc text,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  needs_review boolean NOT NULL DEFAULT false,
  review_reason_codes text[] NOT NULL DEFAULT ARRAY[]::text[],
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.document_titles (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  document_id uuid NOT NULL REFERENCES app.documents(id) ON DELETE CASCADE,
  title_kind text NOT NULL,
  value_raw text,
  value_normalized text,
  value_display text,
  is_primary boolean NOT NULL DEFAULT false,
  created_at timestamptz NOT NULL DEFAULT now(),
  UNIQUE (document_id, title_kind, value_display)
);

CREATE TABLE IF NOT EXISTS app.document_authors (
  document_id uuid NOT NULL REFERENCES app.documents(id) ON DELETE CASCADE,
  author_id uuid NOT NULL REFERENCES app.authors(id) ON DELETE CASCADE,
  author_role text NOT NULL,
  sort_order integer NOT NULL,
  is_primary boolean NOT NULL DEFAULT false,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now(),
  PRIMARY KEY (document_id, author_id, author_role)
);

CREATE TABLE IF NOT EXISTS app.document_subjects (
  document_id uuid NOT NULL REFERENCES app.documents(id) ON DELETE CASCADE,
  subject_id uuid NOT NULL REFERENCES app.subjects(id) ON DELETE CASCADE,
  source_kind text,
  created_at timestamptz NOT NULL DEFAULT now(),
  PRIMARY KEY (document_id, subject_id)
);

CREATE TABLE IF NOT EXISTS app.document_keywords (
  document_id uuid NOT NULL REFERENCES app.documents(id) ON DELETE CASCADE,
  keyword_id uuid NOT NULL REFERENCES app.keywords(id) ON DELETE CASCADE,
  created_at timestamptz NOT NULL DEFAULT now(),
  PRIMARY KEY (document_id, keyword_id)
);

CREATE TABLE IF NOT EXISTS app.book_copies (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_copy_id uuid UNIQUE,
  legacy_inv_id integer NOT NULL UNIQUE,
  legacy_doc_id integer,
  document_id uuid REFERENCES app.documents(id) ON DELETE SET NULL,
  branch_id uuid REFERENCES app.branches(id) ON DELETE SET NULL,
  sigla_id uuid REFERENCES app.siglas(id) ON DELETE SET NULL,
  inventory_number_raw text,
  inventory_number_normalized text,
  branch_hint_raw text,
  branch_hint_normalized text,
  price_raw text,
  registered_at timestamptz,
  state_code integer,
  track_index_raw text,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  needs_review boolean NOT NULL DEFAULT false,
  review_reason_codes text[] NOT NULL DEFAULT ARRAY[]::text[],
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.readers (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  core_reader_id uuid UNIQUE,
  legacy_reader_id text NOT NULL UNIQUE,
  legacy_code_raw text,
  legacy_code_normalized text,
  full_name_raw text,
  full_name_normalized text,
  birthday date,
  registration_at timestamptz,
  reregistration_at timestamptz,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  needs_review boolean NOT NULL DEFAULT false,
  review_reason_codes text[] NOT NULL DEFAULT ARRAY[]::text[],
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS app.reader_contacts (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  reader_id uuid NOT NULL REFERENCES app.readers(id) ON DELETE CASCADE,
  contact_type text NOT NULL,
  value_raw text,
  value_normalized text,
  value_normalized_key text NOT NULL DEFAULT '',
  is_primary boolean NOT NULL DEFAULT false,
  is_placeholder boolean NOT NULL DEFAULT false,
  is_valid_format boolean,
  needs_review boolean NOT NULL DEFAULT false,
  review_reason_codes text[] NOT NULL DEFAULT ARRAY[]::text[],
  created_at timestamptz NOT NULL DEFAULT now(),
  UNIQUE (reader_id, contact_type, value_normalized_key)
);

CREATE TABLE IF NOT EXISTS app.source_lineage (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  entity_type text NOT NULL,
  entity_id uuid NOT NULL,
  source_schema text NOT NULL,
  source_table text NOT NULL,
  source_key text NOT NULL,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  normalization_run_id uuid REFERENCES app.normalization_runs(id) ON DELETE SET NULL,
  created_at timestamptz NOT NULL DEFAULT now(),
  UNIQUE (entity_type, entity_id, source_schema, source_table, source_key)
);

CREATE TABLE IF NOT EXISTS app.data_quality_flags (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  entity_type text NOT NULL,
  entity_id uuid NOT NULL,
  issue_code text NOT NULL,
  severity text NOT NULL,
  status text NOT NULL DEFAULT 'OPEN',
  raw_value text,
  normalized_value text,
  suggested_value text,
  auto_generated boolean NOT NULL DEFAULT true,
  confidence_score numeric(5,2),
  details jsonb NOT NULL DEFAULT '{}'::jsonb,
  source_issue_id uuid,
  created_at timestamptz NOT NULL DEFAULT now(),
  resolved_at timestamptz,
  UNIQUE (entity_type, entity_id, issue_code, auto_generated)
);

CREATE TABLE IF NOT EXISTS app.review_tasks (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  entity_type text NOT NULL,
  entity_id uuid NOT NULL,
  task_type text NOT NULL,
  priority text NOT NULL,
  status text NOT NULL DEFAULT 'OPEN',
  title text NOT NULL,
  description text,
  raw_value text,
  normalized_value text,
  suggested_value text,
  related_flag_id uuid REFERENCES app.data_quality_flags(id) ON DELETE SET NULL,
  created_at timestamptz NOT NULL DEFAULT now(),
  assigned_to text,
  completed_at timestamptz,
  UNIQUE (related_flag_id)
);

CREATE INDEX IF NOT EXISTS idx_app_documents_needs_review ON app.documents(needs_review);
CREATE INDEX IF NOT EXISTS idx_app_documents_language_code ON app.documents(language_code);
CREATE INDEX IF NOT EXISTS idx_app_documents_isbn_normalized ON app.documents(isbn_normalized);
CREATE INDEX IF NOT EXISTS idx_app_book_copies_document_id ON app.book_copies(document_id);
CREATE INDEX IF NOT EXISTS idx_app_book_copies_needs_review ON app.book_copies(needs_review);
CREATE INDEX IF NOT EXISTS idx_app_readers_needs_review ON app.readers(needs_review);
CREATE INDEX IF NOT EXISTS idx_app_quality_flags_entity ON app.data_quality_flags(entity_type, entity_id);
CREATE INDEX IF NOT EXISTS idx_app_review_tasks_status ON app.review_tasks(status, priority);
CREATE INDEX IF NOT EXISTS idx_app_lineage_entity ON app.source_lineage(entity_type, entity_id);

WITH norm_publishers AS (
  SELECT
    p.id AS core_publisher_id,
    p.normalized_name,
    p.display_name,
    p.source_payload
  FROM core.publishers p
)
INSERT INTO app.publishers (core_publisher_id, normalized_name, display_name, source_payload)
SELECT core_publisher_id, normalized_name, display_name, source_payload
FROM norm_publishers
ON CONFLICT (core_publisher_id) DO UPDATE SET
  normalized_name = EXCLUDED.normalized_name,
  display_name = EXCLUDED.display_name,
  source_payload = EXCLUDED.source_payload,
  updated_at = now();

WITH norm_authors AS (
  SELECT
    a.id AS core_author_id,
    a.normalized_name,
    a.display_name
  FROM core.authors a
)
INSERT INTO app.authors (core_author_id, normalized_name, display_name)
SELECT core_author_id, normalized_name, display_name
FROM norm_authors
ON CONFLICT (core_author_id) DO UPDATE SET
  normalized_name = EXCLUDED.normalized_name,
  display_name = EXCLUDED.display_name,
  updated_at = now();

WITH norm_subjects AS (
  SELECT
    s.id AS core_subject_id,
    s.normalized_subject,
    s.display_subject
  FROM core.subjects s
)
INSERT INTO app.subjects (core_subject_id, normalized_subject, display_subject)
SELECT core_subject_id, normalized_subject, display_subject
FROM norm_subjects
ON CONFLICT (core_subject_id) DO UPDATE SET
  normalized_subject = EXCLUDED.normalized_subject,
  display_subject = EXCLUDED.display_subject,
  updated_at = now();

WITH norm_keywords AS (
  SELECT
    k.id AS core_keyword_id,
    k.normalized_keyword,
    k.display_keyword
  FROM core.keywords k
)
INSERT INTO app.keywords (core_keyword_id, normalized_keyword, display_keyword)
SELECT core_keyword_id, normalized_keyword, display_keyword
FROM norm_keywords
ON CONFLICT (core_keyword_id) DO UPDATE SET
  normalized_keyword = EXCLUDED.normalized_keyword,
  display_keyword = EXCLUDED.display_keyword,
  updated_at = now();

WITH norm_branches AS (
  SELECT
    b.id AS core_branch_id,
    b.legacy_bookpoint_id,
    b.legacy_lib_id,
    b.code,
    b.name,
    b.source_payload,
    (coalesce(btrim(b.name), '') = '') AS needs_review
  FROM core.library_branches b
)
INSERT INTO app.branches (
  core_branch_id,
  legacy_bookpoint_id,
  legacy_lib_id,
  code,
  name,
  source_payload,
  needs_review,
  review_reason_codes
)
SELECT
  core_branch_id,
  legacy_bookpoint_id,
  legacy_lib_id,
  code,
  name,
  source_payload,
  needs_review,
  CASE WHEN needs_review THEN ARRAY['branch_name_missing']::text[] ELSE ARRAY[]::text[] END
FROM norm_branches
ON CONFLICT (core_branch_id) DO UPDATE SET
  legacy_bookpoint_id = EXCLUDED.legacy_bookpoint_id,
  legacy_lib_id = EXCLUDED.legacy_lib_id,
  code = EXCLUDED.code,
  name = EXCLUDED.name,
  source_payload = EXCLUDED.source_payload,
  needs_review = EXCLUDED.needs_review,
  review_reason_codes = EXCLUDED.review_reason_codes,
  updated_at = now();

WITH norm_siglas AS (
  SELECT
    s.id AS core_sigla_id,
    s.legacy_sigla_id,
    ab.id AS app_branch_id,
    s.shortname,
    s.storage,
    s.access_level,
    s.source_payload,
    (coalesce(btrim(s.shortname), '') = '') AS needs_review
  FROM core.storage_siglas s
  LEFT JOIN app.branches ab ON ab.core_branch_id = s.branch_id
)
INSERT INTO app.siglas (
  core_sigla_id,
  legacy_sigla_id,
  branch_id,
  shortname,
  storage,
  access_level,
  source_payload,
  needs_review,
  review_reason_codes
)
SELECT
  core_sigla_id,
  legacy_sigla_id,
  app_branch_id,
  shortname,
  storage,
  access_level,
  source_payload,
  needs_review,
  CASE WHEN needs_review THEN ARRAY['sigla_shortname_missing']::text[] ELSE ARRAY[]::text[] END
FROM norm_siglas
ON CONFLICT (core_sigla_id) DO UPDATE SET
  legacy_sigla_id = EXCLUDED.legacy_sigla_id,
  branch_id = EXCLUDED.branch_id,
  shortname = EXCLUDED.shortname,
  storage = EXCLUDED.storage,
  access_level = EXCLUDED.access_level,
  source_payload = EXCLUDED.source_payload,
  needs_review = EXCLUDED.needs_review,
  review_reason_codes = EXCLUDED.review_reason_codes,
  updated_at = now();

WITH base_documents AS (
  SELECT
    d.id AS core_document_id,
    d.legacy_doc_id,
    d.control_number,
    d.title,
    d.subtitle,
    d.publication_place,
    d.publication_year,
    d.isbn,
    d.language_code,
    d.publisher_id,
    d.faculty,
    d.department,
    d.specialization,
    d.literature_type,
    d.raw_marc,
    d.source_payload,
    EXISTS (SELECT 1 FROM core.document_authors da WHERE da.document_id = d.id) AS has_author
  FROM core.documents d
), normalized_documents AS (
  SELECT
    bd.*,
    CASE
      WHEN bd.title IS NULL THEN NULL
      WHEN btrim(bd.title) = '' THEN NULL
      WHEN lower(btrim(bd.title)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.title)
    END AS title_clean,
    CASE
      WHEN bd.subtitle IS NULL THEN NULL
      WHEN btrim(bd.subtitle) = '' THEN NULL
      WHEN lower(btrim(bd.subtitle)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.subtitle)
    END AS subtitle_clean,
    CASE
      WHEN bd.publication_place IS NULL THEN NULL
      WHEN btrim(bd.publication_place) = '' THEN NULL
      WHEN lower(btrim(bd.publication_place)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.publication_place)
    END AS publication_place_clean,
    CASE
      WHEN bd.isbn IS NULL THEN NULL
      WHEN btrim(bd.isbn) = '' THEN NULL
      WHEN lower(btrim(bd.isbn)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.isbn)
    END AS isbn_clean,
    CASE
      WHEN bd.language_code IS NULL THEN NULL
      WHEN btrim(bd.language_code) = '' THEN NULL
      WHEN lower(btrim(bd.language_code)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.language_code)
    END AS language_clean,
    CASE
      WHEN bd.faculty IS NULL THEN NULL
      WHEN btrim(bd.faculty) = '' THEN NULL
      WHEN lower(btrim(bd.faculty)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.faculty)
    END AS faculty_clean,
    CASE
      WHEN bd.department IS NULL THEN NULL
      WHEN btrim(bd.department) = '' THEN NULL
      WHEN lower(btrim(bd.department)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.department)
    END AS department_clean,
    CASE
      WHEN bd.specialization IS NULL THEN NULL
      WHEN btrim(bd.specialization) = '' THEN NULL
      WHEN lower(btrim(bd.specialization)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.specialization)
    END AS specialization_clean,
    CASE
      WHEN bd.literature_type IS NULL THEN NULL
      WHEN btrim(bd.literature_type) = '' THEN NULL
      WHEN lower(btrim(bd.literature_type)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bd.literature_type)
    END AS literature_type_clean
  FROM base_documents bd
), final_documents AS (
  SELECT
    nd.*,
    regexp_replace(upper(coalesce(nd.isbn_clean, '')), '[^0-9A-Z]', '', 'g') AS isbn_norm,
    CASE
      WHEN nd.language_clean IS NULL THEN NULL
      WHEN lower(nd.language_clean) IN ('rus', 'ru', 'russian', 'рус', 'русский') THEN 'rus'
      WHEN lower(nd.language_clean) IN ('kaz', 'kz', 'қаз', 'каз', 'kazakh', 'қазақ') THEN 'kaz'
      WHEN lower(nd.language_clean) IN ('eng', 'en', 'english', 'англ') THEN 'eng'
      WHEN lower(nd.language_clean) IN ('ger', 'de', 'german', 'нем', 'deu') THEN 'ger'
      ELSE NULL
    END AS language_norm,
    regexp_replace(
      regexp_replace(
        coalesce(nd.title_clean, ''),
        '\\mКазак\\M',
        'Қазақ',
        'gi'
      ),
      '\\mАдебиет\\M',
      'Әдебиет',
      'gi'
    ) AS title_auto_fix
  FROM normalized_documents nd
)
INSERT INTO app.documents (
  core_document_id,
  legacy_doc_id,
  control_number,
  title_raw,
  title_normalized,
  title_display,
  subtitle_raw,
  subtitle_normalized,
  publication_place_raw,
  publication_place_normalized,
  publication_year,
  isbn_raw,
  isbn_normalized,
  isbn_is_valid,
  language_raw,
  language_code,
  publisher_id,
  faculty_raw,
  faculty_normalized,
  department_raw,
  department_normalized,
  specialization_raw,
  specialization_normalized,
  literature_type_raw,
  literature_type_normalized,
  raw_marc,
  source_payload,
  needs_review,
  review_reason_codes
)
SELECT
  fd.core_document_id,
  fd.legacy_doc_id,
  fd.control_number,
  fd.title,
  NULLIF(fd.title_auto_fix, ''),
  NULLIF(fd.title_auto_fix, ''),
  fd.subtitle,
  fd.subtitle_clean,
  fd.publication_place,
  fd.publication_place_clean,
  CASE WHEN fd.publication_year > 0 THEN fd.publication_year ELSE NULL END,
  fd.isbn,
  CASE
    WHEN fd.isbn_clean IS NULL THEN NULL
    WHEN regexp_replace(upper(fd.isbn_clean), '[^0-9A-Z]', '', 'g') ~ '^([0-9]{13}|[0-9]{9}X|[0-9]{10})$' THEN regexp_replace(upper(fd.isbn_clean), '[^0-9A-Z]', '', 'g')
    ELSE NULL
  END,
  CASE
    WHEN fd.isbn_clean IS NULL THEN false
    WHEN regexp_replace(upper(fd.isbn_clean), '[^0-9A-Z]', '', 'g') ~ '^([0-9]{13}|[0-9]{9}X|[0-9]{10})$' THEN true
    ELSE false
  END,
  fd.language_clean,
  fd.language_norm,
  ap.id AS app_publisher_id,
  fd.faculty,
  fd.faculty_clean,
  fd.department,
  fd.department_clean,
  fd.specialization,
  fd.specialization_clean,
  fd.literature_type,
  fd.literature_type_clean,
  fd.raw_marc,
  fd.source_payload,
  (
    fd.title_clean IS NULL
    OR fd.isbn_clean IS NULL
    OR (fd.isbn_clean IS NOT NULL AND fd.isbn_norm !~ '^([0-9]{13}|[0-9]{9}X|[0-9]{10})$')
    OR fd.language_norm IS NULL
    OR NOT fd.has_author
  ),
  array_remove(ARRAY[
    CASE WHEN fd.title_clean IS NULL THEN 'missing_title' END,
    CASE WHEN fd.isbn_clean IS NULL THEN 'missing_isbn' END,
    CASE WHEN fd.isbn_clean IS NOT NULL AND fd.isbn_norm !~ '^([0-9]{13}|[0-9]{9}X|[0-9]{10})$' THEN 'invalid_isbn' END,
    CASE WHEN fd.language_norm IS NULL THEN 'invalid_language_code' END,
    CASE WHEN NOT fd.has_author THEN 'missing_author_link' END
  ]::text[], NULL)
FROM final_documents fd
LEFT JOIN app.publishers ap ON ap.core_publisher_id = fd.publisher_id
ON CONFLICT (core_document_id) DO UPDATE SET
  control_number = EXCLUDED.control_number,
  title_raw = EXCLUDED.title_raw,
  title_normalized = EXCLUDED.title_normalized,
  title_display = EXCLUDED.title_display,
  subtitle_raw = EXCLUDED.subtitle_raw,
  subtitle_normalized = EXCLUDED.subtitle_normalized,
  publication_place_raw = EXCLUDED.publication_place_raw,
  publication_place_normalized = EXCLUDED.publication_place_normalized,
  publication_year = EXCLUDED.publication_year,
  isbn_raw = EXCLUDED.isbn_raw,
  isbn_normalized = EXCLUDED.isbn_normalized,
  isbn_is_valid = EXCLUDED.isbn_is_valid,
  language_raw = EXCLUDED.language_raw,
  language_code = EXCLUDED.language_code,
  publisher_id = EXCLUDED.publisher_id,
  faculty_raw = EXCLUDED.faculty_raw,
  faculty_normalized = EXCLUDED.faculty_normalized,
  department_raw = EXCLUDED.department_raw,
  department_normalized = EXCLUDED.department_normalized,
  specialization_raw = EXCLUDED.specialization_raw,
  specialization_normalized = EXCLUDED.specialization_normalized,
  literature_type_raw = EXCLUDED.literature_type_raw,
  literature_type_normalized = EXCLUDED.literature_type_normalized,
  raw_marc = EXCLUDED.raw_marc,
  source_payload = EXCLUDED.source_payload,
  needs_review = EXCLUDED.needs_review,
  review_reason_codes = EXCLUDED.review_reason_codes,
  updated_at = now();

INSERT INTO app.document_titles (document_id, title_kind, value_raw, value_normalized, value_display, is_primary)
SELECT
  ad.id,
  'PRIMARY',
  ad.title_raw,
  ad.title_normalized,
  ad.title_display,
  true
FROM app.documents ad
WHERE ad.title_display IS NOT NULL
ON CONFLICT (document_id, title_kind, value_display) DO NOTHING;

INSERT INTO app.document_authors (document_id, author_id, author_role, sort_order, is_primary, source_payload)
SELECT
  ad.id,
  aa.id,
  cda.author_role,
  cda.sort_order,
  (cda.sort_order = 0),
  '{}'::jsonb
FROM core.document_authors cda
JOIN app.documents ad ON ad.core_document_id = cda.document_id
JOIN app.authors aa ON aa.core_author_id = cda.author_id
ON CONFLICT (document_id, author_id, author_role) DO UPDATE SET
  sort_order = EXCLUDED.sort_order,
  is_primary = EXCLUDED.is_primary;

INSERT INTO app.document_subjects (document_id, subject_id, source_kind)
SELECT
  x.document_id,
  x.subject_id,
  x.source_kind
FROM (
  SELECT DISTINCT ON (ad.id, asb.id)
    ad.id AS document_id,
    asb.id AS subject_id,
    cds.source_kind
  FROM core.document_subjects cds
  JOIN app.documents ad ON ad.core_document_id = cds.document_id
  JOIN app.subjects asb ON asb.core_subject_id = cds.subject_id
  ORDER BY ad.id, asb.id, cds.source_kind
) x
ON CONFLICT (document_id, subject_id) DO UPDATE SET
  source_kind = EXCLUDED.source_kind;

INSERT INTO app.document_keywords (document_id, keyword_id)
SELECT
  ad.id,
  ak.id
FROM core.document_keywords cdk
JOIN app.documents ad ON ad.core_document_id = cdk.document_id
JOIN app.keywords ak ON ak.core_keyword_id = cdk.keyword_id
ON CONFLICT (document_id, keyword_id) DO NOTHING;

WITH base_copies AS (
  SELECT
    c.id AS core_copy_id,
    c.legacy_inv_id,
    c.legacy_doc_id,
    c.document_id,
    c.branch_id,
    c.storage_sigla_id,
    c.inventory_number,
    c.branch_hint,
    c.price_raw,
    c.registered_at,
    c.state_code,
    c.track_index,
    c.source_payload
  FROM core.book_copies c
), normalized_copies AS (
  SELECT
    bc.*,
    CASE
      WHEN bc.inventory_number IS NULL THEN NULL
      WHEN btrim(bc.inventory_number) = '' THEN NULL
      WHEN lower(btrim(bc.inventory_number)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bc.inventory_number)
    END AS inventory_number_clean,
    CASE
      WHEN bc.branch_hint IS NULL THEN NULL
      WHEN btrim(bc.branch_hint) = '' THEN NULL
      WHEN lower(btrim(bc.branch_hint)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bc.branch_hint)
    END AS branch_hint_clean,
    CASE
      WHEN bc.track_index IS NULL THEN NULL
      WHEN btrim(bc.track_index) = '' THEN NULL
      WHEN lower(btrim(bc.track_index)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(bc.track_index)
    END AS track_index_clean
  FROM base_copies bc
)
INSERT INTO app.book_copies (
  core_copy_id,
  legacy_inv_id,
  legacy_doc_id,
  document_id,
  branch_id,
  sigla_id,
  inventory_number_raw,
  inventory_number_normalized,
  branch_hint_raw,
  branch_hint_normalized,
  price_raw,
  registered_at,
  state_code,
  track_index_raw,
  source_payload,
  needs_review,
  review_reason_codes
)
SELECT
  nc.core_copy_id,
  nc.legacy_inv_id,
  nc.legacy_doc_id,
  ad.id,
  ab.id,
  ag.id,
  nc.inventory_number,
  nc.inventory_number_clean,
  nc.branch_hint,
  nc.branch_hint_clean,
  nc.price_raw,
  nc.registered_at,
  nc.state_code,
  nc.track_index,
  nc.source_payload,
  (
    ad.id IS NULL
    OR nc.inventory_number_clean IS NULL
    OR ab.id IS NULL
  ),
  array_remove(ARRAY[
    CASE WHEN ad.id IS NULL THEN 'orphan_copy_document' END,
    CASE WHEN nc.inventory_number_clean IS NULL THEN 'missing_inventory_number' END,
    CASE WHEN ab.id IS NULL THEN 'missing_branch_mapping' END
  ]::text[], NULL)
FROM normalized_copies nc
LEFT JOIN app.documents ad ON ad.core_document_id = nc.document_id
LEFT JOIN app.branches ab ON ab.core_branch_id = nc.branch_id
LEFT JOIN app.siglas ag ON ag.core_sigla_id = nc.storage_sigla_id
ON CONFLICT (core_copy_id) DO UPDATE SET
  legacy_inv_id = EXCLUDED.legacy_inv_id,
  legacy_doc_id = EXCLUDED.legacy_doc_id,
  document_id = EXCLUDED.document_id,
  branch_id = EXCLUDED.branch_id,
  sigla_id = EXCLUDED.sigla_id,
  inventory_number_raw = EXCLUDED.inventory_number_raw,
  inventory_number_normalized = EXCLUDED.inventory_number_normalized,
  branch_hint_raw = EXCLUDED.branch_hint_raw,
  branch_hint_normalized = EXCLUDED.branch_hint_normalized,
  price_raw = EXCLUDED.price_raw,
  registered_at = EXCLUDED.registered_at,
  state_code = EXCLUDED.state_code,
  track_index_raw = EXCLUDED.track_index_raw,
  source_payload = EXCLUDED.source_payload,
  needs_review = EXCLUDED.needs_review,
  review_reason_codes = EXCLUDED.review_reason_codes,
  updated_at = now();

WITH base_readers AS (
  SELECT
    r.id AS core_reader_id,
    r.legacy_reader_id,
    r.legacy_code,
    r.full_name,
    r.email,
    r.birthday,
    r.registration_at,
    r.reregistration_at,
    r.source_payload
  FROM core.legacy_readers r
), normalized_readers AS (
  SELECT
    br.*,
    CASE
      WHEN br.legacy_code IS NULL THEN NULL
      WHEN btrim(br.legacy_code) = '' THEN NULL
      WHEN lower(btrim(br.legacy_code)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE btrim(br.legacy_code)
    END AS legacy_code_clean,
    CASE
      WHEN br.full_name IS NULL THEN NULL
      WHEN btrim(br.full_name) = '' THEN NULL
      WHEN lower(btrim(br.full_name)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE regexp_replace(btrim(br.full_name), '\\s+', ' ', 'g')
    END AS full_name_clean,
    CASE
      WHEN br.email IS NULL THEN NULL
      WHEN btrim(br.email) = '' THEN NULL
      WHEN lower(btrim(br.email)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE lower(btrim(br.email))
    END AS email_clean
  FROM base_readers br
)
INSERT INTO app.readers (
  core_reader_id,
  legacy_reader_id,
  legacy_code_raw,
  legacy_code_normalized,
  full_name_raw,
  full_name_normalized,
  birthday,
  registration_at,
  reregistration_at,
  source_payload,
  needs_review,
  review_reason_codes
)
SELECT
  nr.core_reader_id,
  nr.legacy_reader_id,
  nr.legacy_code,
  nr.legacy_code_clean,
  nr.full_name,
  nr.full_name_clean,
  nr.birthday,
  nr.registration_at,
  nr.reregistration_at,
  nr.source_payload,
  (nr.full_name_clean IS NULL OR nr.email_clean IS NULL),
  array_remove(ARRAY[
    CASE WHEN nr.full_name_clean IS NULL THEN 'missing_reader_name' END,
    CASE WHEN nr.email_clean IS NULL THEN 'missing_reader_email' END
  ]::text[], NULL)
FROM normalized_readers nr
ON CONFLICT (core_reader_id) DO UPDATE SET
  legacy_reader_id = EXCLUDED.legacy_reader_id,
  legacy_code_raw = EXCLUDED.legacy_code_raw,
  legacy_code_normalized = EXCLUDED.legacy_code_normalized,
  full_name_raw = EXCLUDED.full_name_raw,
  full_name_normalized = EXCLUDED.full_name_normalized,
  birthday = EXCLUDED.birthday,
  registration_at = EXCLUDED.registration_at,
  reregistration_at = EXCLUDED.reregistration_at,
  source_payload = EXCLUDED.source_payload,
  needs_review = EXCLUDED.needs_review,
  review_reason_codes = EXCLUDED.review_reason_codes,
  updated_at = now();

WITH reader_email AS (
  SELECT
    ar.id AS reader_id,
    ar.legacy_reader_id,
    clr.email,
    CASE
      WHEN clr.email IS NULL THEN NULL
      WHEN btrim(clr.email) = '' THEN NULL
      WHEN lower(btrim(clr.email)) IN ('-', '--', '---', '.', 'unknown', 'нет', 'n/a', 'na', 'null') THEN NULL
      ELSE lower(btrim(clr.email))
    END AS email_clean
  FROM app.readers ar
  JOIN core.legacy_readers clr ON clr.id = ar.core_reader_id
)
INSERT INTO app.reader_contacts (
  reader_id,
  contact_type,
  value_raw,
  value_normalized,
  value_normalized_key,
  is_primary,
  is_placeholder,
  is_valid_format,
  needs_review,
  review_reason_codes
)
SELECT
  re.reader_id,
  'EMAIL',
  re.email,
  re.email_clean,
  coalesce(re.email_clean, ''),
  true,
  (re.email_clean IS NULL),
  CASE
    WHEN re.email_clean IS NULL THEN false
    WHEN re.email_clean ~* '^[A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,}$' THEN true
    ELSE false
  END,
  (re.email_clean IS NULL OR (re.email_clean IS NOT NULL AND re.email_clean !~* '^[A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,}$')),
  array_remove(ARRAY[
    CASE WHEN re.email_clean IS NULL THEN 'missing_email' END,
    CASE WHEN re.email_clean IS NOT NULL AND re.email_clean !~* '^[A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,}$' THEN 'invalid_email_format' END
  ]::text[], NULL)
FROM reader_email re
ON CONFLICT (reader_id, contact_type, value_normalized_key) DO UPDATE SET
  value_raw = EXCLUDED.value_raw,
  value_normalized = EXCLUDED.value_normalized,
  value_normalized_key = EXCLUDED.value_normalized_key,
  is_primary = EXCLUDED.is_primary,
  is_placeholder = EXCLUDED.is_placeholder,
  is_valid_format = EXCLUDED.is_valid_format,
  needs_review = EXCLUDED.needs_review,
  review_reason_codes = EXCLUDED.review_reason_codes;

INSERT INTO app.source_lineage (entity_type, entity_id, source_schema, source_table, source_key, source_payload)
SELECT
  'document',
  ad.id,
  'core',
  'documents',
  ad.legacy_doc_id::text,
  ad.source_payload
FROM app.documents ad
ON CONFLICT DO NOTHING;

INSERT INTO app.source_lineage (entity_type, entity_id, source_schema, source_table, source_key, source_payload)
SELECT
  'book_copy',
  abc.id,
  'core',
  'book_copies',
  abc.legacy_inv_id::text,
  abc.source_payload
FROM app.book_copies abc
ON CONFLICT DO NOTHING;

INSERT INTO app.source_lineage (entity_type, entity_id, source_schema, source_table, source_key, source_payload)
SELECT
  'reader',
  ar.id,
  'core',
  'legacy_readers',
  ar.legacy_reader_id,
  ar.source_payload
FROM app.readers ar
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
  'document',
  ad.id,
  reason_code,
  CASE
    WHEN reason_code IN ('missing_title', 'missing_author_link') THEN 'HIGH'
    WHEN reason_code IN ('missing_isbn', 'invalid_isbn', 'invalid_language_code') THEN 'MEDIUM'
    ELSE 'LOW'
  END,
  'OPEN',
  ad.title_raw,
  ad.title_normalized,
  ad.title_display,
  true,
  CASE
    WHEN reason_code IN ('missing_title', 'missing_author_link') THEN 0.95
    WHEN reason_code IN ('missing_isbn', 'invalid_isbn') THEN 0.99
    ELSE 0.90
  END,
  jsonb_build_object('review_reason_code', reason_code)
FROM app.documents ad
CROSS JOIN LATERAL unnest(ad.review_reason_codes) AS reason_code
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
  abc.id,
  reason_code,
  CASE
    WHEN reason_code = 'orphan_copy_document' THEN 'HIGH'
    ELSE 'MEDIUM'
  END,
  'OPEN',
  abc.inventory_number_raw,
  abc.inventory_number_normalized,
  abc.inventory_number_normalized,
  true,
  0.98,
  jsonb_build_object('review_reason_code', reason_code)
FROM app.book_copies abc
CROSS JOIN LATERAL unnest(abc.review_reason_codes) AS reason_code
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
  ar.id,
  reason_code,
  'MEDIUM',
  'OPEN',
  ar.full_name_raw,
  ar.full_name_normalized,
  ar.full_name_normalized,
  true,
  0.95,
  jsonb_build_object('review_reason_code', reason_code)
FROM app.readers ar
CROSS JOIN LATERAL unnest(ar.review_reason_codes) AS reason_code
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
  'DATA_CORRECTION',
  CASE WHEN dqf.severity = 'HIGH' THEN 'HIGH' WHEN dqf.severity = 'MEDIUM' THEN 'MEDIUM' ELSE 'LOW' END,
  'OPEN',
  concat('Review ', dqf.issue_code),
  concat('Auto-generated review task for ', dqf.issue_code),
  dqf.raw_value,
  dqf.normalized_value,
  dqf.suggested_value,
  dqf.id
FROM app.data_quality_flags dqf
ON CONFLICT (related_flag_id) DO NOTHING;
