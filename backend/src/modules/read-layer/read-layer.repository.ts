import { Injectable } from "@nestjs/common";
import { Prisma } from "@prisma/client";

import { PrismaService } from "@prisma/prisma.service";
import {
  AvailabilityRow,
  CatalogSearchRow,
  CountRow,
  DocumentDetailRow,
  FacetRow,
  ReviewQueueRow,
} from "./read-layer.types";

export interface CatalogSearchFilters {
  q?: string;
  title?: string;
  author?: string;
  isbn?: string;
  language?: string;
  institutionUnitCode?: string;
  campusCode?: string;
  servicePointCode?: string;
  availability?: "all" | "available" | "unavailable";
  minCopies?: number;
  page: number;
  limit: number;
}

export interface AvailabilityFilters {
  institutionUnitCode?: string;
  campusCode?: string;
  servicePointCode?: string;
}

export interface ReviewQueueFilters {
  entityType?: string;
  issueCode?: string;
  severity?: string;
  campusCode?: string;
  servicePointCode?: string;
  page: number;
  limit: number;
}

@Injectable()
export class ReadLayerRepository {
  constructor(private readonly prisma: PrismaService) {}

  async searchCatalog(
    filters: CatalogSearchFilters,
  ): Promise<{ rows: CatalogSearchRow[]; total: number }> {
    const whereClause = this.buildCatalogWhere(filters);
    const offset = (filters.page - 1) * filters.limit;

    const rowsQuery = Prisma.sql`
      SELECT
        document_id,
        legacy_doc_id,
        title_raw,
        title_normalized,
        title_display,
        subtitle_raw,
        subtitle_normalized,
        publication_year,
        isbn_raw,
        isbn_normalized,
        isbn_is_valid,
        language_raw,
        language_code,
        publisher_id,
        publisher_name,
        authors_json,
        primary_author_display,
        total_copy_count,
        available_copy_count,
        unavailable_copy_count,
        review_copy_count,
        problem_copy_count,
        orphan_copy_count,
        institution_unit_codes,
        campus_codes,
        campus_names,
        service_point_codes,
        service_point_names,
        document_needs_review,
        has_open_review,
        highest_review_severity,
        review_issue_codes,
        open_task_count,
        document_flag_count,
        copy_flag_count
      FROM app.catalog_search_mv
      ${whereClause}
      ORDER BY
        available_copy_count DESC,
        total_copy_count DESC,
        publication_year DESC NULLS LAST,
        title_display ASC NULLS LAST,
        document_id ASC
      LIMIT ${filters.limit}
      OFFSET ${offset}
    `;

    const countQuery = Prisma.sql`
      SELECT count(*)::int AS total
      FROM app.catalog_search_mv
      ${whereClause}
    `;

    const [rows, countRows] = await this.prisma.$transaction([
      this.prisma.$queryRaw<CatalogSearchRow[]>(rowsQuery),
      this.prisma.$queryRaw<CountRow[]>(countQuery),
    ]);

    return {
      rows,
      total: countRows[0]?.total ?? 0,
    };
  }

  async getDocumentDetailById(
    documentId: string,
  ): Promise<DocumentDetailRow | null> {
    const rows = await this.prisma.$queryRaw<DocumentDetailRow[]>(Prisma.sql`
      SELECT
        document_id,
        legacy_doc_id,
        control_number,
        title_display,
        title_normalized,
        title_raw,
        subtitle_raw,
        subtitle_normalized,
        publication_place_raw,
        publication_place_normalized,
        publication_year,
        language_raw,
        language_code,
        isbn_raw,
        isbn_normalized,
        isbn_is_valid,
        faculty_raw,
        faculty_normalized,
        department_raw,
        department_normalized,
        specialization_raw,
        specialization_normalized,
        literature_type_raw,
        literature_type_normalized,
        publisher_id,
        publisher_name,
        authors_json,
        subjects_json,
        keywords_json,
        title_variants_json,
        document_quality_flags_json,
        copy_quality_flags_json,
        review_tasks_json,
        copy_summary_json,
        campus_distribution_json,
        service_point_distribution_json
      FROM app.document_detail_v
      WHERE document_id = CAST(${documentId} AS uuid)
      LIMIT 1
    `);

    return rows[0] ?? null;
  }

  async getDocumentAvailability(
    documentId: string,
    filters: AvailabilityFilters,
  ): Promise<AvailabilityRow[]> {
    const conditions: Prisma.Sql[] = [
      Prisma.sql`document_id = CAST(${documentId} AS uuid)`,
    ];

    if (filters.institutionUnitCode) {
      conditions.push(
        Prisma.sql`institution_unit_code = ${filters.institutionUnitCode}`,
      );
    }
    if (filters.campusCode) {
      conditions.push(Prisma.sql`campus_code = ${filters.campusCode}`);
    }
    if (filters.servicePointCode) {
      conditions.push(
        Prisma.sql`service_point_code = ${filters.servicePointCode}`,
      );
    }

    const whereClause = Prisma.sql`WHERE ${Prisma.join(conditions, " AND ")}`;

    return this.prisma.$queryRaw<AvailabilityRow[]>(Prisma.sql`
      SELECT
        document_id,
        legacy_doc_id,
        title_display,
        institution_unit_id,
        institution_unit_code,
        institution_unit_name,
        campus_id,
        campus_code,
        campus_name,
        service_point_id,
        service_point_code,
        service_point_name,
        total_copy_count,
        available_copy_count,
        unavailable_copy_count,
        review_copy_count,
        problem_copy_count,
        orphan_copy_count
      FROM app.document_availability_by_location_v
      ${whereClause}
      ORDER BY campus_name ASC NULLS LAST, service_point_name ASC NULLS LAST, service_point_code ASC NULLS LAST
    `);
  }

  async getLocationInventorySummary(
    filters: AvailabilityFilters,
  ): Promise<AvailabilityRow[]> {
    const conditions: Prisma.Sql[] = [];

    if (filters.institutionUnitCode) {
      conditions.push(
        Prisma.sql`institution_unit_code = ${filters.institutionUnitCode}`,
      );
    }
    if (filters.campusCode) {
      conditions.push(Prisma.sql`campus_code = ${filters.campusCode}`);
    }
    if (filters.servicePointCode) {
      conditions.push(
        Prisma.sql`service_point_code = ${filters.servicePointCode}`,
      );
    }

    const whereClause =
      conditions.length > 0
        ? Prisma.sql`WHERE ${Prisma.join(conditions, " AND ")}`
        : Prisma.empty;

    return this.prisma.$queryRaw<AvailabilityRow[]>(Prisma.sql`
      SELECT
        NULL::uuid AS document_id,
        NULL::int AS legacy_doc_id,
        NULL::text AS title_display,
        institution_unit_id,
        institution_unit_code,
        institution_unit_name,
        campus_id,
        campus_code,
        campus_name,
        service_point_id,
        service_point_code,
        service_point_name,
        total_copy_count,
        available_copy_count,
        unavailable_copy_count,
        review_copy_count,
        problem_copy_count,
        orphan_copy_count
      FROM app.location_inventory_summary_v
      ${whereClause}
      ORDER BY total_copy_count DESC, campus_name ASC NULLS LAST, service_point_name ASC NULLS LAST
    `);
  }

  async getCatalogFacets(): Promise<FacetRow[]> {
    return this.prisma.$queryRaw<FacetRow[]>(Prisma.sql`
      SELECT
        facet_type,
        facet_value,
        facet_label,
        document_count,
        total_copy_count,
        available_copy_count,
        review_document_count
      FROM app.catalog_filter_facets_v
      ORDER BY facet_type ASC, document_count DESC, facet_label ASC
    `);
  }

  async getReviewQueue(
    filters: ReviewQueueFilters,
  ): Promise<{ rows: ReviewQueueRow[]; total: number }> {
    const whereClause = this.buildReviewWhere(filters);
    const offset = (filters.page - 1) * filters.limit;

    const rowsQuery = Prisma.sql`
      SELECT
        flag_id,
        task_id,
        entity_type,
        entity_id,
        issue_code,
        severity,
        flag_status,
        raw_value,
        normalized_value,
        suggested_value,
        confidence_score,
        details,
        task_type,
        task_priority,
        task_status,
        task_title,
        task_description,
        assigned_to,
        flagged_at,
        task_created_at,
        document_id,
        legacy_doc_id,
        title_display,
        isbn_normalized,
        language_code,
        copy_id,
        legacy_inv_id,
        inventory_number_normalized,
        reader_id,
        legacy_reader_id,
        full_name_normalized,
        institution_unit_code,
        campus_codes,
        service_point_codes,
        reader_service_point_names
      FROM app.review_queue_v
      ${whereClause}
      ORDER BY
        CASE severity
          WHEN 'CRITICAL' THEN 4
          WHEN 'HIGH' THEN 3
          WHEN 'MEDIUM' THEN 2
          WHEN 'LOW' THEN 1
          ELSE 0
        END DESC,
        flagged_at DESC,
        flag_id ASC
      LIMIT ${filters.limit}
      OFFSET ${offset}
    `;

    const countQuery = Prisma.sql`
      SELECT count(*)::int AS total
      FROM app.review_queue_v
      ${whereClause}
    `;

    const [rows, countRows] = await this.prisma.$transaction([
      this.prisma.$queryRaw<ReviewQueueRow[]>(rowsQuery),
      this.prisma.$queryRaw<CountRow[]>(countQuery),
    ]);

    return {
      rows,
      total: countRows[0]?.total ?? 0,
    };
  }

  async getReviewIssueByFlagId(flagId: string): Promise<ReviewQueueRow | null> {
    const rows = await this.prisma.$queryRaw<ReviewQueueRow[]>(Prisma.sql`
      SELECT
        flag_id,
        task_id,
        entity_type,
        entity_id,
        issue_code,
        severity,
        flag_status,
        raw_value,
        normalized_value,
        suggested_value,
        confidence_score,
        details,
        task_type,
        task_priority,
        task_status,
        task_title,
        task_description,
        assigned_to,
        flagged_at,
        task_created_at,
        document_id,
        legacy_doc_id,
        title_display,
        isbn_normalized,
        language_code,
        copy_id,
        legacy_inv_id,
        inventory_number_normalized,
        reader_id,
        legacy_reader_id,
        full_name_normalized,
        institution_unit_code,
        campus_codes,
        service_point_codes,
        reader_service_point_names
      FROM app.review_queue_v
      WHERE flag_id = CAST(${flagId} AS uuid)
      LIMIT 1
    `);

    return rows[0] ?? null;
  }

  async getRelatedReviewIssues(
    documentId: string,
    excludeFlagId: string,
  ): Promise<ReviewQueueRow[]> {
    return this.prisma.$queryRaw<ReviewQueueRow[]>(Prisma.sql`
      SELECT
        flag_id,
        task_id,
        entity_type,
        entity_id,
        issue_code,
        severity,
        flag_status,
        raw_value,
        normalized_value,
        suggested_value,
        confidence_score,
        details,
        task_type,
        task_priority,
        task_status,
        task_title,
        task_description,
        assigned_to,
        flagged_at,
        task_created_at,
        document_id,
        legacy_doc_id,
        title_display,
        isbn_normalized,
        language_code,
        copy_id,
        legacy_inv_id,
        inventory_number_normalized,
        reader_id,
        legacy_reader_id,
        full_name_normalized,
        institution_unit_code,
        campus_codes,
        service_point_codes,
        reader_service_point_names
      FROM app.review_queue_v
      WHERE document_id = CAST(${documentId} AS uuid)
        AND flag_id <> CAST(${excludeFlagId} AS uuid)
      ORDER BY flagged_at DESC, flag_id ASC
      LIMIT 20
    `);
  }

  private buildCatalogWhere(filters: CatalogSearchFilters): Prisma.Sql {
    const conditions: Prisma.Sql[] = [];

    if (filters.q) {
      const qLike = this.toLike(filters.q);
      conditions.push(
        Prisma.sql`(
          search_vector @@ websearch_to_tsquery('simple', ${filters.q})
          OR searchable_text % ${filters.q}
          OR title_display ILIKE ${qLike}
          OR primary_author_display ILIKE ${qLike}
          OR isbn_raw ILIKE ${qLike}
          OR isbn_normalized = ${this.normalizeIsbn(filters.q)}
        )`,
      );
    }

    if (filters.title) {
      const like = this.toLike(filters.title);
      conditions.push(
        Prisma.sql`(title_display ILIKE ${like} OR title_raw ILIKE ${like})`,
      );
    }

    if (filters.author) {
      const like = this.toLike(filters.author);
      conditions.push(
        Prisma.sql`(
          primary_author_display ILIKE ${like}
          OR EXISTS (
            SELECT 1
            FROM unnest(author_names) AS author_name
            WHERE author_name ILIKE ${like}
          )
        )`,
      );
    }

    if (filters.isbn) {
      const normalized = this.normalizeIsbn(filters.isbn);
      const like = this.toLike(filters.isbn);
      conditions.push(
        Prisma.sql`(isbn_normalized = ${normalized} OR isbn_raw ILIKE ${like})`,
      );
    }

    if (filters.language) {
      conditions.push(
        Prisma.sql`lower(coalesce(language_code, '')) = lower(${filters.language})`,
      );
    }

    if (filters.institutionUnitCode) {
      conditions.push(
        Prisma.sql`EXISTS (
          SELECT 1
          FROM unnest(institution_unit_codes) AS unit_code
          WHERE unit_code = ${filters.institutionUnitCode}
        )`,
      );
    }

    if (filters.campusCode) {
      conditions.push(
        Prisma.sql`EXISTS (
          SELECT 1
          FROM unnest(campus_codes) AS campus_code
          WHERE campus_code = ${filters.campusCode}
        )`,
      );
    }

    if (filters.servicePointCode) {
      conditions.push(
        Prisma.sql`EXISTS (
          SELECT 1
          FROM unnest(service_point_codes) AS service_point_code
          WHERE service_point_code = ${filters.servicePointCode}
        )`,
      );
    }

    if (filters.availability === "available") {
      conditions.push(Prisma.sql`available_copy_count > 0`);
    }

    if (filters.availability === "unavailable") {
      conditions.push(Prisma.sql`available_copy_count = 0`);
    }

    if (typeof filters.minCopies === "number") {
      conditions.push(Prisma.sql`total_copy_count >= ${filters.minCopies}`);
    }

    return conditions.length > 0
      ? Prisma.sql`WHERE ${Prisma.join(conditions, " AND ")}`
      : Prisma.empty;
  }

  private buildReviewWhere(filters: ReviewQueueFilters): Prisma.Sql {
    const conditions: Prisma.Sql[] = [];

    if (filters.entityType) {
      conditions.push(Prisma.sql`entity_type = ${filters.entityType}`);
    }
    if (filters.issueCode) {
      conditions.push(Prisma.sql`issue_code = ${filters.issueCode}`);
    }
    if (filters.severity) {
      conditions.push(Prisma.sql`severity = ${filters.severity}`);
    }
    if (filters.campusCode) {
      conditions.push(
        Prisma.sql`EXISTS (
          SELECT 1
          FROM unnest(campus_codes) AS campus_code
          WHERE campus_code = ${filters.campusCode}
        )`,
      );
    }
    if (filters.servicePointCode) {
      conditions.push(
        Prisma.sql`EXISTS (
          SELECT 1
          FROM unnest(service_point_codes) AS service_point_code
          WHERE service_point_code = ${filters.servicePointCode}
        )`,
      );
    }

    return conditions.length > 0
      ? Prisma.sql`WHERE ${Prisma.join(conditions, " AND ")}`
      : Prisma.empty;
  }

  private normalizeIsbn(value: string): string {
    return value.replace(/[^0-9A-Za-z]/g, "").toUpperCase();
  }

  private toLike(value: string): string {
    return `%${value.trim()}%`;
  }
}
