CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE SCHEMA IF NOT EXISTS migration;
CREATE SCHEMA IF NOT EXISTS review;
CREATE SCHEMA IF NOT EXISTS raw;
CREATE SCHEMA IF NOT EXISTS parsed;
CREATE SCHEMA IF NOT EXISTS core;

CREATE TABLE IF NOT EXISTS migration.import_batches (
  id uuid PRIMARY KEY,
  source_system text NOT NULL,
  source_database text NOT NULL,
  status text NOT NULL,
  started_at timestamptz NOT NULL DEFAULT now(),
  completed_at timestamptz,
  notes text,
  metrics jsonb NOT NULL DEFAULT '{}'::jsonb
);

CREATE TABLE IF NOT EXISTS migration.import_logs (
  id uuid PRIMARY KEY,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  stage text NOT NULL,
  source_table text,
  level text NOT NULL,
  message text NOT NULL,
  row_count integer,
  details jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS review.quality_issues (
  id uuid PRIMARY KEY,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  issue_code text NOT NULL,
  severity text NOT NULL,
  status text NOT NULL DEFAULT 'OPEN',
  source_schema text NOT NULL,
  source_table text NOT NULL,
  source_key text,
  core_entity text,
  core_entity_key text,
  summary text NOT NULL,
  details jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS review.issue_notes (
  id uuid PRIMARY KEY,
  issue_id uuid NOT NULL REFERENCES review.quality_issues(id) ON DELETE CASCADE,
  author text,
  note text NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.doc (
  legacy_doc_id integer PRIMARY KEY,
  rectype text,
  biblevel text,
  item text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.doc_view (
  legacy_doc_id integer PRIMARY KEY,
  control_number text,
  isbn text,
  author text,
  title text,
  publisher text,
  publication_year integer,
  storage_sigla text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.inv (
  legacy_inv_id integer PRIMARY KEY,
  legacy_doc_id integer,
  sigla_id integer,
  state integer,
  regdate_raw double precision,
  offdate_raw double precision,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.readers (
  legacy_reader_id text PRIMARY KEY,
  code text,
  name text,
  email text,
  birthday_raw double precision,
  regdate_raw double precision,
  reregdate_raw double precision,
  bookpoints text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.rdrbp (
  legacy_rdrbp_id integer PRIMARY KEY,
  legacy_reader_id text,
  legacy_bookpoint_id integer,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.bookpoints (
  legacy_bookpoint_id integer PRIMARY KEY,
  shortname text,
  status integer,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.siglas (
  legacy_sigla_id integer PRIMARY KEY,
  shortname text,
  storage integer,
  access_level integer,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.pointsigla (
  legacy_pointsigla_id integer PRIMARY KEY,
  legacy_bookpoint_id integer NOT NULL,
  legacy_sigla_id integer NOT NULL,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.libs (
  legacy_lib_id integer PRIMARY KEY,
  name text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.libs_bookpoints (
  legacy_link_id integer PRIMARY KEY,
  legacy_lib_id integer,
  legacy_bookpoint_id integer,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.libs_siglas (
  legacy_link_id integer PRIMARY KEY,
  legacy_lib_id integer,
  legacy_sigla_id integer,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.publisher (
  legacy_publisher_id integer PRIMARY KEY,
  name text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.bookstates (
  legacy_bookstate_id integer PRIMARY KEY,
  legacy_reader_id text,
  legacy_inv_id integer,
  legacy_doc_id integer,
  state integer,
  legacy_bookpoint_id integer,
  legacy_sigla_id integer,
  chsdate_raw double precision,
  retdate_raw double precision,
  flags text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS raw.preorders (
  legacy_preorder_id integer PRIMARY KEY,
  legacy_reader_id text,
  legacy_doc_id integer,
  legacy_bookpoint_id integer,
  preorddate_raw double precision,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  imported_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS parsed.documents (
  legacy_doc_id integer PRIMARY KEY,
  control_number text,
  rectype text,
  biblevel text,
  title text,
  title_continuation text,
  physical_media text,
  type_content_access text,
  primary_author_text text,
  publication_place text,
  publisher_name text,
  publication_year integer,
  publication_year_raw text,
  isbn text,
  isbn_raw text,
  language_code text,
  keywords text[] NOT NULL DEFAULT ARRAY[]::text[],
  faculty text,
  department text,
  subject_text text,
  specialization text,
  record_creation_year text,
  record_creation_month text,
  record_creation_date text,
  literature_type text,
  raw_marc text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  parsed_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS parsed.document_authors (
  legacy_doc_id integer NOT NULL REFERENCES parsed.documents(legacy_doc_id) ON DELETE CASCADE,
  author_name text NOT NULL,
  author_role text NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (legacy_doc_id, author_name, author_role)
);

CREATE TABLE IF NOT EXISTS parsed.document_keywords (
  legacy_doc_id integer NOT NULL REFERENCES parsed.documents(legacy_doc_id) ON DELETE CASCADE,
  keyword text NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (legacy_doc_id, keyword)
);

CREATE TABLE IF NOT EXISTS parsed.document_subject_hints (
  legacy_doc_id integer NOT NULL REFERENCES parsed.documents(legacy_doc_id) ON DELETE CASCADE,
  subject_name text NOT NULL,
  source_kind text NOT NULL,
  PRIMARY KEY (legacy_doc_id, subject_name, source_kind)
);

CREATE TABLE IF NOT EXISTS parsed.inventory (
  legacy_inv_id integer PRIMARY KEY,
  legacy_doc_id integer,
  inventory_number text,
  branch_hint text,
  price_raw text,
  registered_at timestamptz,
  registered_at_raw double precision,
  sigla_id integer,
  state_code integer,
  track_index text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  parsed_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS parsed.readers (
  legacy_reader_id text PRIMARY KEY,
  code text,
  full_name text,
  email text,
  work_phone text,
  employment text,
  course text,
  profession text,
  sex text,
  birthday date,
  birthday_raw double precision,
  registration_at timestamptz,
  registration_raw double precision,
  reregistration_at timestamptz,
  reregistration_raw double precision,
  bookpoints_raw text,
  source_payload jsonb NOT NULL,
  batch_id uuid NOT NULL REFERENCES migration.import_batches(id) ON DELETE CASCADE,
  parsed_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.publishers (
  id uuid PRIMARY KEY,
  normalized_name text NOT NULL UNIQUE,
  display_name text NOT NULL,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.authors (
  id uuid PRIMARY KEY,
  normalized_name text NOT NULL UNIQUE,
  display_name text NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.keywords (
  id uuid PRIMARY KEY,
  normalized_keyword text NOT NULL UNIQUE,
  display_keyword text NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.subjects (
  id uuid PRIMARY KEY,
  normalized_subject text NOT NULL UNIQUE,
  display_subject text NOT NULL,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.library_branches (
  id uuid PRIMARY KEY,
  legacy_bookpoint_id integer UNIQUE,
  legacy_lib_id integer,
  code text NOT NULL UNIQUE,
  name text NOT NULL,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.storage_siglas (
  id uuid PRIMARY KEY,
  legacy_sigla_id integer NOT NULL UNIQUE,
  branch_id uuid REFERENCES core.library_branches(id) ON DELETE SET NULL,
  shortname text NOT NULL,
  storage integer,
  access_level integer,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.documents (
  id uuid PRIMARY KEY,
  legacy_doc_id integer NOT NULL UNIQUE,
  control_number text,
  title text,
  subtitle text,
  physical_media text,
  type_content_access text,
  publication_place text,
  publisher_id uuid REFERENCES core.publishers(id) ON DELETE SET NULL,
  publication_year integer,
  isbn text,
  language_code text,
  faculty text,
  department text,
  specialization text,
  literature_type text,
  raw_marc text,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.document_authors (
  document_id uuid NOT NULL REFERENCES core.documents(id) ON DELETE CASCADE,
  author_id uuid NOT NULL REFERENCES core.authors(id) ON DELETE CASCADE,
  author_role text NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (document_id, author_id, author_role)
);

CREATE TABLE IF NOT EXISTS core.document_keywords (
  document_id uuid NOT NULL REFERENCES core.documents(id) ON DELETE CASCADE,
  keyword_id uuid NOT NULL REFERENCES core.keywords(id) ON DELETE CASCADE,
  PRIMARY KEY (document_id, keyword_id)
);

CREATE TABLE IF NOT EXISTS core.document_subjects (
  document_id uuid NOT NULL REFERENCES core.documents(id) ON DELETE CASCADE,
  subject_id uuid NOT NULL REFERENCES core.subjects(id) ON DELETE CASCADE,
  source_kind text NOT NULL,
  PRIMARY KEY (document_id, subject_id, source_kind)
);

CREATE TABLE IF NOT EXISTS core.book_copies (
  id uuid PRIMARY KEY,
  legacy_inv_id integer NOT NULL UNIQUE,
  legacy_doc_id integer,
  document_id uuid REFERENCES core.documents(id) ON DELETE SET NULL,
  branch_id uuid REFERENCES core.library_branches(id) ON DELETE SET NULL,
  storage_sigla_id uuid REFERENCES core.storage_siglas(id) ON DELETE SET NULL,
  inventory_number text,
  branch_hint text,
  price_raw text,
  registered_at timestamptz,
  state_code integer,
  track_index text,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.legacy_readers (
  id uuid PRIMARY KEY,
  legacy_reader_id text NOT NULL UNIQUE,
  legacy_code text,
  full_name text,
  email text,
  work_phone text,
  employment text,
  course text,
  profession text,
  sex text,
  birthday date,
  registration_at timestamptz,
  reregistration_at timestamptz,
  bookpoints_raw text,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS core.legacy_copy_events (
  id uuid PRIMARY KEY,
  legacy_bookstate_id integer NOT NULL UNIQUE,
  legacy_reader_id text,
  legacy_inv_id integer,
  legacy_doc_id integer,
  book_copy_id uuid REFERENCES core.book_copies(id) ON DELETE SET NULL,
  reader_id uuid REFERENCES core.legacy_readers(id) ON DELETE SET NULL,
  branch_id uuid REFERENCES core.library_branches(id) ON DELETE SET NULL,
  storage_sigla_id uuid REFERENCES core.storage_siglas(id) ON DELETE SET NULL,
  state_code integer,
  charged_at timestamptz,
  returned_at timestamptz,
  flags text,
  source_payload jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_review_quality_issues_batch_id ON review.quality_issues(batch_id);
CREATE INDEX IF NOT EXISTS idx_review_quality_issues_code ON review.quality_issues(issue_code);
CREATE INDEX IF NOT EXISTS idx_raw_inv_legacy_doc_id ON raw.inv(legacy_doc_id);
CREATE INDEX IF NOT EXISTS idx_raw_bookstates_legacy_reader_id ON raw.bookstates(legacy_reader_id);
CREATE INDEX IF NOT EXISTS idx_parsed_documents_control_number ON parsed.documents(control_number);
CREATE INDEX IF NOT EXISTS idx_core_documents_legacy_doc_id ON core.documents(legacy_doc_id);
CREATE INDEX IF NOT EXISTS idx_core_book_copies_document_id ON core.book_copies(document_id);