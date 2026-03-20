export interface CatalogSearchRow {
  document_id: string;
  legacy_doc_id: number;
  title_raw: string | null;
  title_normalized: string | null;
  title_display: string | null;
  subtitle_raw: string | null;
  subtitle_normalized: string | null;
  publication_year: number | null;
  isbn_raw: string | null;
  isbn_normalized: string | null;
  isbn_is_valid: boolean;
  language_raw: string | null;
  language_code: string | null;
  publisher_id: string | null;
  publisher_name: string | null;
  authors_json: unknown;
  primary_author_display: string | null;
  total_copy_count: number;
  available_copy_count: number;
  unavailable_copy_count: number;
  review_copy_count: number;
  problem_copy_count: number;
  orphan_copy_count: number;
  institution_unit_codes: string[] | null;
  campus_codes: string[] | null;
  campus_names: string[] | null;
  service_point_codes: string[] | null;
  service_point_names: string[] | null;
  document_needs_review: boolean;
  has_open_review: boolean;
  highest_review_severity: string | null;
  review_issue_codes: string[] | null;
  open_task_count: number;
  document_flag_count: number;
  copy_flag_count: number;
}

export interface DocumentDetailRow {
  document_id: string;
  legacy_doc_id: number;
  control_number: string | null;
  title_display: string | null;
  title_normalized: string | null;
  title_raw: string | null;
  subtitle_raw: string | null;
  subtitle_normalized: string | null;
  publication_place_raw: string | null;
  publication_place_normalized: string | null;
  publication_year: number | null;
  language_raw: string | null;
  language_code: string | null;
  isbn_raw: string | null;
  isbn_normalized: string | null;
  isbn_is_valid: boolean;
  faculty_raw: string | null;
  faculty_normalized: string | null;
  department_raw: string | null;
  department_normalized: string | null;
  specialization_raw: string | null;
  specialization_normalized: string | null;
  literature_type_raw: string | null;
  literature_type_normalized: string | null;
  publisher_id: string | null;
  publisher_name: string | null;
  authors_json: unknown;
  subjects_json: unknown;
  keywords_json: unknown;
  title_variants_json: unknown;
  document_quality_flags_json: unknown;
  copy_quality_flags_json: unknown;
  review_tasks_json: unknown;
  copy_summary_json: unknown;
  campus_distribution_json: unknown;
  service_point_distribution_json: unknown;
}

export interface AvailabilityRow {
  document_id: string;
  legacy_doc_id: number;
  title_display: string | null;
  institution_unit_id: string | null;
  institution_unit_code: string | null;
  institution_unit_name: string | null;
  campus_id: string | null;
  campus_code: string | null;
  campus_name: string | null;
  service_point_id: string | null;
  service_point_code: string | null;
  service_point_name: string | null;
  total_copy_count: number;
  available_copy_count: number;
  unavailable_copy_count: number;
  review_copy_count: number;
  problem_copy_count: number;
  orphan_copy_count: number;
}

export interface FacetRow {
  facet_type: string;
  facet_value: string;
  facet_label: string;
  document_count: number;
  total_copy_count: number;
  available_copy_count: number;
  review_document_count: number;
}

export interface ReviewQueueRow {
  flag_id: string;
  task_id: string | null;
  entity_type: string;
  entity_id: string;
  issue_code: string;
  severity: string;
  flag_status: string;
  raw_value: string | null;
  normalized_value: string | null;
  suggested_value: string | null;
  confidence_score: string | number | null;
  details: unknown;
  task_type: string | null;
  task_priority: string | null;
  task_status: string | null;
  task_title: string | null;
  task_description: string | null;
  assigned_to: string | null;
  flagged_at: Date | string;
  task_created_at: Date | string | null;
  document_id: string | null;
  legacy_doc_id: number | null;
  title_display: string | null;
  isbn_normalized: string | null;
  language_code: string | null;
  copy_id: string | null;
  legacy_inv_id: number | null;
  inventory_number_normalized: string | null;
  reader_id: string | null;
  legacy_reader_id: string | null;
  full_name_normalized: string | null;
  institution_unit_code: string | null;
  campus_codes: string[] | null;
  service_point_codes: string[] | null;
  reader_service_point_names: string[] | null;
}

export interface CountRow {
  total: number;
}
