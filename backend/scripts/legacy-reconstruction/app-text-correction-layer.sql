CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE SCHEMA IF NOT EXISTS app;

CREATE TABLE IF NOT EXISTS app.text_correction_suggestions (
  id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
  normalization_run_id uuid REFERENCES app.normalization_runs(id) ON DELETE SET NULL,
  entity_type text NOT NULL,
  entity_id uuid NOT NULL,
  field_name text NOT NULL,
  source_raw_value text,
  current_value text,
  suggested_value text NOT NULL,
  correction_rule_key text NOT NULL,
  confidence_score numeric(5,2) NOT NULL,
  action_class text NOT NULL,
  auto_applied boolean NOT NULL DEFAULT false,
  applied_at timestamptz,
  reviewed boolean NOT NULL DEFAULT false,
  review_status text NOT NULL DEFAULT 'PENDING',
  reviewed_by text,
  reviewed_at timestamptz,
  review_notes text,
  details jsonb NOT NULL DEFAULT '{}'::jsonb,
  created_at timestamptz NOT NULL DEFAULT now(),
  updated_at timestamptz NOT NULL DEFAULT now(),
  UNIQUE (entity_type, entity_id, field_name, suggested_value, correction_rule_key)
);

ALTER TABLE app.text_correction_suggestions
  DROP CONSTRAINT IF EXISTS chk_app_text_correction_action_class;
ALTER TABLE app.text_correction_suggestions
  ADD CONSTRAINT chk_app_text_correction_action_class
  CHECK (action_class IN ('auto_apply', 'suggest_only', 'flag_only'));

ALTER TABLE app.text_correction_suggestions
  DROP CONSTRAINT IF EXISTS chk_app_text_correction_review_status;
ALTER TABLE app.text_correction_suggestions
  ADD CONSTRAINT chk_app_text_correction_review_status
  CHECK (review_status IN ('PENDING', 'ACCEPTED', 'REJECTED', 'NOT_REQUIRED'));

CREATE INDEX IF NOT EXISTS idx_app_text_correction_action
  ON app.text_correction_suggestions(action_class, reviewed, review_status);

CREATE INDEX IF NOT EXISTS idx_app_text_correction_entity
  ON app.text_correction_suggestions(entity_type, entity_id, field_name);

CREATE INDEX IF NOT EXISTS idx_app_text_correction_rule
  ON app.text_correction_suggestions(correction_rule_key, confidence_score DESC);

CREATE OR REPLACE VIEW app.text_correction_queue_v AS
SELECT
  tcs.id,
  tcs.normalization_run_id,
  tcs.entity_type,
  tcs.entity_id,
  tcs.field_name,
  tcs.source_raw_value,
  tcs.current_value,
  tcs.suggested_value,
  tcs.correction_rule_key,
  tcs.confidence_score,
  tcs.action_class,
  tcs.auto_applied,
  tcs.applied_at,
  tcs.reviewed,
  tcs.review_status,
  tcs.reviewed_by,
  tcs.reviewed_at,
  tcs.review_notes,
  tcs.details,
  tcs.created_at,
  tcs.updated_at
FROM app.text_correction_suggestions tcs;
