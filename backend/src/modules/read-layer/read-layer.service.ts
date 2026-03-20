import { Injectable, NotFoundException } from "@nestjs/common";

import { paginate } from "@common/utils/pagination.util";

import {
  AvailabilityFilters,
  CatalogSearchFilters,
  ReadLayerRepository,
  ReviewQueueFilters,
} from "./read-layer.repository";
import {
  AvailabilityRow,
  CatalogSearchRow,
  DocumentDetailRow,
  FacetRow,
  ReviewQueueRow,
} from "./read-layer.types";

@Injectable()
export class ReadLayerService {
  constructor(private readonly repository: ReadLayerRepository) {}

  async searchCatalog(filters: CatalogSearchFilters) {
    const result = await this.repository.searchCatalog(filters);

    return paginate(
      result.rows.map((row) => this.toCatalogSearchItem(row)),
      result.total,
      filters.page,
      filters.limit,
    );
  }

  async getPublicDocumentDetail(documentId: string) {
    const row = await this.repository.getDocumentDetailById(documentId);

    if (!row) {
      throw new NotFoundException("Catalog document not found");
    }

    return this.toPublicDocumentDetail(row);
  }

  async getDocumentAvailability(
    documentId: string,
    filters: AvailabilityFilters,
  ) {
    const detail = await this.repository.getDocumentDetailById(documentId);

    if (!detail) {
      throw new NotFoundException("Catalog document not found");
    }

    const rows = await this.repository.getDocumentAvailability(
      documentId,
      filters,
    );

    return {
      documentId,
      legacyDocId: detail.legacy_doc_id,
      title: detail.title_display,
      items: rows.map((row) => this.toAvailabilityItem(row)),
    };
  }

  async getLocationInventorySummary(filters: AvailabilityFilters) {
    const rows = await this.repository.getLocationInventorySummary(filters);

    return {
      items: rows.map((row) => this.toLocationSummaryItem(row)),
    };
  }

  async getCatalogFacets() {
    const rows = await this.repository.getCatalogFacets();

    return rows.reduce(
      (accumulator, row) => {
        const item = this.toFacetItem(row);
        if (row.facet_type === "language") {
          accumulator.languages.push(item);
        } else if (row.facet_type === "campus") {
          accumulator.campuses.push(item);
        } else if (row.facet_type === "service_point") {
          accumulator.servicePoints.push(item);
        } else if (row.facet_type === "availability") {
          accumulator.availability.push(item);
        }
        return accumulator;
      },
      {
        languages: [] as ReturnType<ReadLayerService["toFacetItem"]>[],
        campuses: [] as ReturnType<ReadLayerService["toFacetItem"]>[],
        servicePoints: [] as ReturnType<ReadLayerService["toFacetItem"]>[],
        availability: [] as ReturnType<ReadLayerService["toFacetItem"]>[],
      },
    );
  }

  async getReviewQueue(filters: ReviewQueueFilters) {
    const result = await this.repository.getReviewQueue(filters);

    return paginate(
      result.rows.map((row) => this.toReviewQueueItem(row)),
      result.total,
      filters.page,
      filters.limit,
    );
  }

  async getReviewIssueDetail(flagId: string) {
    const row = await this.repository.getReviewIssueByFlagId(flagId);

    if (!row) {
      throw new NotFoundException("Review issue not found");
    }

    const detail = row.document_id
      ? await this.repository.getDocumentDetailById(row.document_id)
      : null;
    const availability = row.document_id
      ? await this.repository.getDocumentAvailability(row.document_id, {})
      : [];
    const relatedIssues = row.document_id
      ? await this.repository.getRelatedReviewIssues(
          row.document_id,
          row.flag_id,
        )
      : [];

    return {
      issue: this.toReviewQueueItem(row),
      document: detail ? this.toAdminDocumentContext(detail) : null,
      availability: availability.map((item) => this.toAvailabilityItem(item)),
      relatedIssues: relatedIssues.map((item) => this.toReviewQueueItem(item)),
    };
  }

  private toCatalogSearchItem(row: CatalogSearchRow) {
    return {
      id: row.document_id,
      legacyDocId: row.legacy_doc_id,
      title: {
        display: row.title_display,
        normalized: row.title_normalized,
        raw: row.title_raw,
        subtitle: row.subtitle_normalized ?? row.subtitle_raw,
      },
      primaryAuthor: row.primary_author_display,
      authors: this.asArray(row.authors_json),
      publisher: row.publisher_id
        ? {
            id: row.publisher_id,
            name: row.publisher_name,
          }
        : null,
      publicationYear: row.publication_year,
      language: {
        code: row.language_code,
        raw: row.language_raw,
      },
      isbn: {
        raw: row.isbn_raw,
        normalized: row.isbn_normalized,
        isValid: row.isbn_is_valid,
      },
      copies: {
        total: row.total_copy_count,
        available: row.available_copy_count,
        unavailable: row.unavailable_copy_count,
        review: row.review_copy_count,
        problem: row.problem_copy_count,
        orphan: row.orphan_copy_count,
      },
      locations: {
        institutionUnitCodes: row.institution_unit_codes ?? [],
        campusCodes: row.campus_codes ?? [],
        campusNames: row.campus_names ?? [],
        servicePointCodes: row.service_point_codes ?? [],
        servicePointNames: row.service_point_names ?? [],
      },
      review: {
        documentNeedsReview: row.document_needs_review,
        hasOpenReview: row.has_open_review,
        highestSeverity: row.highest_review_severity,
        issueCodes: row.review_issue_codes ?? [],
        openTaskCount: row.open_task_count,
        documentFlagCount: row.document_flag_count,
        copyFlagCount: row.copy_flag_count,
      },
    };
  }

  private toPublicDocumentDetail(row: DocumentDetailRow) {
    return {
      id: row.document_id,
      legacyDocId: row.legacy_doc_id,
      controlNumber: row.control_number,
      title: {
        display: row.title_display,
        normalized: row.title_normalized,
        raw: row.title_raw,
        subtitle: row.subtitle_normalized ?? row.subtitle_raw,
        variants: this.asArray(row.title_variants_json),
      },
      publisher: row.publisher_id
        ? {
            id: row.publisher_id,
            name: row.publisher_name,
          }
        : null,
      publication: {
        placeRaw: row.publication_place_raw,
        placeNormalized: row.publication_place_normalized,
        year: row.publication_year,
      },
      language: {
        code: row.language_code,
        raw: row.language_raw,
      },
      isbn: {
        raw: row.isbn_raw,
        normalized: row.isbn_normalized,
        isValid: row.isbn_is_valid,
      },
      authors: this.asArray(row.authors_json),
      subjects: this.asArray(row.subjects_json),
      keywords: this.asArray(row.keywords_json),
      classification: {
        faculty: {
          raw: row.faculty_raw,
          normalized: row.faculty_normalized,
        },
        department: {
          raw: row.department_raw,
          normalized: row.department_normalized,
        },
        specialization: {
          raw: row.specialization_raw,
          normalized: row.specialization_normalized,
        },
        literatureType: {
          raw: row.literature_type_raw,
          normalized: row.literature_type_normalized,
        },
      },
      copySummary: row.copy_summary_json ?? {},
      campusDistribution: this.asArray(row.campus_distribution_json),
      servicePointDistribution: this.asArray(
        row.service_point_distribution_json,
      ),
    };
  }

  private toAdminDocumentContext(row: DocumentDetailRow) {
    return {
      ...this.toPublicDocumentDetail(row),
      review: {
        documentFlags: this.asArray(row.document_quality_flags_json),
        copyFlags: this.asArray(row.copy_quality_flags_json),
        tasks: this.asArray(row.review_tasks_json),
      },
    };
  }

  private toAvailabilityItem(row: AvailabilityRow) {
    return {
      institutionUnit: row.institution_unit_code
        ? {
            id: row.institution_unit_id,
            code: row.institution_unit_code,
            name: row.institution_unit_name,
          }
        : null,
      campus: row.campus_code
        ? {
            id: row.campus_id,
            code: row.campus_code,
            name: row.campus_name,
          }
        : null,
      servicePoint: row.service_point_code
        ? {
            id: row.service_point_id,
            code: row.service_point_code,
            name: row.service_point_name,
          }
        : null,
      copies: {
        total: row.total_copy_count,
        available: row.available_copy_count,
        unavailable: row.unavailable_copy_count,
        review: row.review_copy_count,
        problem: row.problem_copy_count,
        orphan: row.orphan_copy_count,
      },
    };
  }

  private toLocationSummaryItem(row: AvailabilityRow) {
    return {
      institutionUnit: row.institution_unit_code
        ? {
            id: row.institution_unit_id,
            code: row.institution_unit_code,
            name: row.institution_unit_name,
          }
        : null,
      campus: row.campus_code
        ? {
            id: row.campus_id,
            code: row.campus_code,
            name: row.campus_name,
          }
        : null,
      servicePoint: row.service_point_code
        ? {
            id: row.service_point_id,
            code: row.service_point_code,
            name: row.service_point_name,
          }
        : null,
      copies: {
        total: row.total_copy_count,
        available: row.available_copy_count,
        unavailable: row.unavailable_copy_count,
        review: row.review_copy_count,
        problem: row.problem_copy_count,
        orphan: row.orphan_copy_count,
      },
    };
  }

  private toFacetItem(row: FacetRow) {
    return {
      type: row.facet_type,
      value: row.facet_value,
      label: row.facet_label,
      counts: {
        documents: row.document_count,
        totalCopies: row.total_copy_count,
        availableCopies: row.available_copy_count,
        reviewDocuments: row.review_document_count,
      },
    };
  }

  private toReviewQueueItem(row: ReviewQueueRow) {
    return {
      flagId: row.flag_id,
      taskId: row.task_id,
      entityType: row.entity_type,
      entityId: row.entity_id,
      issueCode: row.issue_code,
      severity: row.severity,
      flagStatus: row.flag_status,
      task: row.task_id
        ? {
            id: row.task_id,
            type: row.task_type,
            priority: row.task_priority,
            status: row.task_status,
            title: row.task_title,
            description: row.task_description,
            assignedTo: row.assigned_to,
            createdAt: row.task_created_at,
          }
        : null,
      values: {
        raw: row.raw_value,
        normalized: row.normalized_value,
        suggested: row.suggested_value,
        confidenceScore:
          row.confidence_score === null
            ? null
            : Number.parseFloat(String(row.confidence_score)),
      },
      context: {
        documentId: row.document_id,
        legacyDocId: row.legacy_doc_id,
        title: row.title_display,
        isbnNormalized: row.isbn_normalized,
        languageCode: row.language_code,
        copyId: row.copy_id,
        legacyInvId: row.legacy_inv_id,
        inventoryNumber: row.inventory_number_normalized,
        readerId: row.reader_id,
        legacyReaderId: row.legacy_reader_id,
        readerName: row.full_name_normalized,
        institutionUnitCode: row.institution_unit_code,
        campusCodes: row.campus_codes ?? [],
        servicePointCodes: row.service_point_codes ?? [],
        readerServicePointNames: row.reader_service_point_names ?? [],
      },
      details: row.details ?? {},
      flaggedAt: row.flagged_at,
    };
  }

  private asArray(value: unknown): unknown[] {
    return Array.isArray(value) ? value : [];
  }
}
