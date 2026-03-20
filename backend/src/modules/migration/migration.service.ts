import {
  BadRequestException,
  ForbiddenException,
  Injectable,
  NotFoundException,
} from "@nestjs/common";
import {
  DataQualityReviewStatus,
  LibraryBranchCode,
  Prisma,
  UserRole as PrismaUserRole,
} from "@prisma/client";
import { existsSync, readFileSync } from "node:fs";
import * as path from "node:path";

import { RequestUser } from "../../common/types/request-user.interface";
import { UserRole } from "../../common/types/user-role.enum";
import { AuditService } from "../audit/audit.service";
import { PrismaService } from "../../prisma/prisma.service";

type DataQualityStage = "raw" | "clean" | "normalized";
type DataQualitySeverity = "CRITICAL" | "HIGH" | "MEDIUM" | "LOW";
type DataQualityIssueClass =
  | "IDENTITY"
  | "REFERENTIAL"
  | "SEMANTIC"
  | "FORMAT"
  | "GOVERNANCE"
  | "DERIVED";

type DataQualityIssueFilterStatus = DataQualityReviewStatus | "ALL";

interface DataQualityDetectedIssue {
  id: string;
  batchId: string;
  stage: DataQualityStage;
  severity: DataQualitySeverity;
  issueClass: DataQualityIssueClass;
  sourceTable: string;
  sourceRecordKey: string;
  branch?: string;
  fieldName?: string;
  autoFixable: boolean;
  detectedAt: string;
  summary: string;
  detectionRule: string;
  sourceContext: {
    title?: string;
    author?: string;
    isbn?: string;
    languageCode?: string;
    publicationYear?: string;
    publisher?: string;
    publicationPlace?: string;
  };
}

interface DataQualityIssue extends DataQualityDetectedIssue {
  review: {
    status: DataQualityReviewStatus;
    assignedToUserId?: string;
    assignedToName?: string;
    latestNote?: string;
    noteCount: number;
    lastReviewedByUserId?: string;
    lastReviewedByName?: string;
    updatedAt?: string;
  };
}

interface DataQualitySnapshot {
  issues: DataQualityDetectedIssue[];
  artifactStats: {
    sourceDir: string;
    docViewRowsScanned: number;
    docTableRowCount: number | null;
    totalLegacyTables: number;
    foreignKeyCount: number;
  };
  detectedAt: string;
}

interface DataQualityFilters {
  stage?: string;
  severity?: string;
  issueClass?: string;
  status?: string;
  sourceTable?: string;
  limit?: number;
}

interface ScopeContext {
  role: UserRole;
  branchId: string | null;
  branchCode: LibraryBranchCode | null;
}

interface AppReviewActionInput {
  action: "accept_suggestion" | "reject_suggestion" | "manual_edit";
  suggestionId?: string;
  fieldName?: string;
  manualValue?: string;
  note?: string;
}

interface AppReviewFlagRow {
  flag_id: string;
  task_id: string | null;
  entity_type: string;
  entity_id: string;
  issue_code: string;
  details: unknown;
  suggested_value: string | null;
}

@Injectable()
export class MigrationService {
  private cache: DataQualitySnapshot | null = null;

  constructor(
    private readonly prisma: PrismaService,
    private readonly auditService: AuditService,
  ) {}

  list() {
    return [];
  }

  async getDataQualitySummary(
    actor: RequestUser,
    filters: DataQualityFilters = {},
  ) {
    const { issues, artifactStats, detectedAt } = await this.getFilteredIssues(
      actor,
      filters,
    );

    const bySeverity: Record<DataQualitySeverity, number> = {
      CRITICAL: 0,
      HIGH: 0,
      MEDIUM: 0,
      LOW: 0,
    };

    const byClass: Record<DataQualityIssueClass, number> = {
      IDENTITY: 0,
      REFERENTIAL: 0,
      SEMANTIC: 0,
      FORMAT: 0,
      GOVERNANCE: 0,
      DERIVED: 0,
    };

    const byStatus: Record<DataQualityReviewStatus, number> = {
      OPEN: 0,
      IN_REVIEW: 0,
      NEEDS_METADATA_COMPLETION: 0,
      DUPLICATE_CANDIDATE: 0,
      ESCALATED: 0,
      REVIEWED: 0,
    };

    for (const issue of issues) {
      bySeverity[issue.severity] += 1;
      byClass[issue.issueClass] += 1;
      byStatus[issue.review.status] += 1;
    }

    return {
      total: issues.length,
      critical: bySeverity.CRITICAL,
      high: bySeverity.HIGH,
      reviewed: byStatus.REVIEWED,
      bySeverity,
      byClass,
      byStatus,
      artifactStats,
      detectedAt,
    };
  }

  async getDataQualityIssues(
    actor: RequestUser,
    filters: DataQualityFilters = {},
  ) {
    const { issues, artifactStats, detectedAt } = await this.getFilteredIssues(
      actor,
      filters,
    );
    const limit =
      typeof filters.limit === "number"
        ? filters.limit
        : filters.limit
          ? Number.parseInt(String(filters.limit), 10)
          : 200;

    const boundedLimit = Number.isFinite(limit)
      ? Math.min(Math.max(limit, 1), 1000)
      : 200;

    return {
      total: issues.length,
      items: issues.slice(0, boundedLimit),
      artifactStats,
      detectedAt,
      appliedFilters: {
        stage: filters.stage ?? "ALL",
        severity: filters.severity ?? "ALL",
        issueClass: filters.issueClass ?? "ALL",
        status: filters.status ?? "ALL",
        sourceTable: filters.sourceTable ?? "ALL",
      },
    };
  }

  async getDataQualityIssueById(actor: RequestUser, id: string) {
    const issue = await this.resolveIssueForActor(actor, id);

    return {
      data: issue,
      artifactStats: this.getSnapshot().artifactStats,
      detectedAt: this.getSnapshot().detectedAt,
      notes: await this.getIssueNotes(id),
    };
  }

  async updateIssueReviewStatus(
    actor: RequestUser,
    id: string,
    status: DataQualityReviewStatus,
    note?: string,
  ) {
    const issue = await this.resolveIssueForActor(actor, id, true);
    this.assertValidReviewStatus(status);

    const review = await this.prisma.dataQualityIssueReview.upsert({
      where: { issueId: id },
      create: {
        issueId: id,
        sourceTable: issue.sourceTable,
        sourceRecordKey: issue.sourceRecordKey,
        issueClass: issue.issueClass,
        severity: issue.severity,
        status,
        latestNote: note?.trim() || null,
        lastReviewedByUserId: actor.id,
      },
      update: {
        status,
        latestNote: note?.trim() || undefined,
        lastReviewedByUserId: actor.id,
      },
      include: {
        assignedToUser: { select: { id: true, fullName: true } },
        lastReviewedByUser: { select: { id: true, fullName: true } },
      },
    });

    if (note?.trim()) {
      await this.prisma.dataQualityIssueReviewNote.create({
        data: {
          reviewId: review.id,
          userId: actor.id,
          note: note.trim(),
        },
      });
    }

    await this.auditService.write({
      action: "DATA_QUALITY_REVIEW_STATUS_CHANGED",
      entityType: "DataQualityIssue",
      entityId: id,
      userId: actor.id,
      metadata: {
        status,
        note: note?.trim() || null,
      },
    });

    return this.getDataQualityIssueById(actor, id);
  }

  async addIssueNote(actor: RequestUser, id: string, note: string) {
    const issue = await this.resolveIssueForActor(actor, id, true);
    const normalizedNote = note.trim();

    if (!normalizedNote) {
      throw new BadRequestException("Note must not be empty");
    }

    const review = await this.prisma.dataQualityIssueReview.upsert({
      where: { issueId: id },
      create: {
        issueId: id,
        sourceTable: issue.sourceTable,
        sourceRecordKey: issue.sourceRecordKey,
        issueClass: issue.issueClass,
        severity: issue.severity,
        status: DataQualityReviewStatus.IN_REVIEW,
        latestNote: normalizedNote,
        lastReviewedByUserId: actor.id,
      },
      update: {
        latestNote: normalizedNote,
        lastReviewedByUserId: actor.id,
      },
    });

    await this.prisma.dataQualityIssueReviewNote.create({
      data: {
        reviewId: review.id,
        userId: actor.id,
        note: normalizedNote,
      },
    });

    await this.auditService.write({
      action: "DATA_QUALITY_NOTE_ADDED",
      entityType: "DataQualityIssue",
      entityId: id,
      userId: actor.id,
      metadata: {
        note: normalizedNote,
      },
    });

    return this.getDataQualityIssueById(actor, id);
  }

  async assignIssue(actor: RequestUser, id: string, assigneeUserId?: string) {
    const issue = await this.resolveIssueForActor(actor, id, true);
    const scope = await this.resolveActorScope(actor);

    if (
      actor.role === UserRole.LIBRARIAN &&
      assigneeUserId &&
      assigneeUserId !== actor.id
    ) {
      throw new ForbiddenException("Librarian can only self-assign issues");
    }

    if (assigneeUserId) {
      const assignee = await this.prisma.user.findUnique({
        where: { id: assigneeUserId },
        select: {
          id: true,
          role: true,
          libraryBranchId: true,
          fullName: true,
        },
      });

      if (!assignee) {
        throw new NotFoundException("Assignee user not found");
      }

      if (
        scope.role === UserRole.LIBRARIAN &&
        scope.branchId &&
        assignee.libraryBranchId !== scope.branchId
      ) {
        throw new ForbiddenException(
          "Librarian can assign only within own branch",
        );
      }

      if (
        assignee.role !== PrismaUserRole.LIBRARIAN &&
        assignee.role !== PrismaUserRole.ADMIN &&
        assignee.role !== PrismaUserRole.ANALYST
      ) {
        throw new BadRequestException(
          "Assignee must be librarian, analyst, or admin",
        );
      }
    }

    await this.prisma.dataQualityIssueReview.upsert({
      where: { issueId: id },
      create: {
        issueId: id,
        sourceTable: issue.sourceTable,
        sourceRecordKey: issue.sourceRecordKey,
        issueClass: issue.issueClass,
        severity: issue.severity,
        status: DataQualityReviewStatus.IN_REVIEW,
        assignedToUserId: assigneeUserId || null,
        lastReviewedByUserId: actor.id,
      },
      update: {
        assignedToUserId: assigneeUserId || null,
        lastReviewedByUserId: actor.id,
      },
    });

    await this.auditService.write({
      action: "DATA_QUALITY_ISSUE_ASSIGNED",
      entityType: "DataQualityIssue",
      entityId: id,
      userId: actor.id,
      metadata: {
        assignedToUserId: assigneeUserId || null,
      },
    });

    return this.getDataQualityIssueById(actor, id);
  }

  async applyAppReviewAction(
    actor: RequestUser,
    flagId: string,
    input: AppReviewActionInput,
  ) {
    await this.resolveActorScope(actor);

    const flags = await this.prisma.$queryRaw<AppReviewFlagRow[]>(Prisma.sql`
      SELECT
        dqf.id AS flag_id,
        rt.id AS task_id,
        dqf.entity_type,
        dqf.entity_id,
        dqf.issue_code,
        dqf.details,
        dqf.suggested_value
      FROM app.data_quality_flags dqf
      LEFT JOIN app.review_tasks rt ON rt.related_flag_id = dqf.id
      WHERE dqf.id = CAST(${flagId} AS uuid)
      LIMIT 1
    `);

    const flag = flags[0];
    if (!flag) {
      throw new NotFoundException("App review issue not found");
    }

    if (input.action === "manual_edit" && !input.manualValue?.trim()) {
      throw new BadRequestException(
        "manualValue is required for manual_edit action",
      );
    }

    const normalizedNote = input.note?.trim() || null;
    const details = this.parseJsonObject(flag.details);
    const resolvedSuggestionId =
      input.suggestionId ||
      this.readString(details, "suggestion_id") ||
      this.readString(details, "suggestionId");

    let finalAppliedValue: string | null = null;

    await this.prisma.$transaction(async (tx) => {
      if (input.action === "accept_suggestion") {
        finalAppliedValue = flag.suggested_value?.trim() || null;
        if (!finalAppliedValue) {
          throw new BadRequestException(
            "No suggested value available to accept",
          );
        }
      }

      if (input.action === "manual_edit") {
        finalAppliedValue = input.manualValue!.trim();
      }

      const targetField =
        input.fieldName ||
        this.readString(details, "field_name") ||
        this.readString(details, "fieldName");

      if (finalAppliedValue && targetField) {
        await this.applyEntityFieldUpdate(
          tx,
          flag.entity_type,
          flag.entity_id,
          targetField,
          finalAppliedValue,
        );
      }

      if (resolvedSuggestionId) {
        const reviewStatus =
          input.action === "reject_suggestion" ? "REJECTED" : "ACCEPTED";
        await tx.$executeRaw(Prisma.sql`
          UPDATE app.text_correction_suggestions
          SET
            reviewed = true,
            review_status = ${reviewStatus},
            reviewed_by = ${actor.id},
            reviewed_at = now(),
            review_notes = ${normalizedNote},
            updated_at = now()
          WHERE id = CAST(${resolvedSuggestionId} AS uuid)
        `);
      }

      const nextFlagStatus =
        input.action === "reject_suggestion" ? "REJECTED" : "RESOLVED";
      const nextTaskStatus =
        input.action === "reject_suggestion" ? "CANCELLED" : "COMPLETED";

      await tx.$executeRaw(Prisma.sql`
        UPDATE app.data_quality_flags
        SET
          status = ${nextFlagStatus},
          resolved_at = now(),
          details = details || jsonb_build_object(
            'review_action', ${input.action},
            'reviewed_by', CAST(${actor.id} AS text),
            'reviewed_at', now(),
            'review_note', CAST(${normalizedNote} AS text),
            'applied_field', CAST(${targetField ?? null} AS text),
            'applied_value', CAST(${finalAppliedValue} AS text)
          )
        WHERE id = CAST(${flagId} AS uuid)
      `);

      if (flag.task_id) {
        await tx.$executeRaw(Prisma.sql`
          UPDATE app.review_tasks
          SET
            status = ${nextTaskStatus},
            completed_at = now(),
            assigned_to = coalesce(assigned_to, ${actor.fullName})
          WHERE id = CAST(${flag.task_id} AS uuid)
        `);
      }
    });

    await this.auditService.write({
      action: "APP_REVIEW_ACTION_APPLIED",
      entityType: "AppDataQualityFlag",
      entityId: flagId,
      userId: actor.id,
      metadata: {
        action: input.action,
        suggestionId: resolvedSuggestionId ?? null,
        note: normalizedNote,
        appliedValue: finalAppliedValue,
      },
    });

    return {
      ok: true,
    };
  }

  private async getFilteredIssues(
    actor: RequestUser,
    filters: DataQualityFilters,
  ) {
    const scope = await this.resolveActorScope(actor);
    const snapshot = this.getSnapshot();

    const scopedDetected = snapshot.issues.filter((issue) =>
      this.canAccessIssueByScope(scope, issue.branch),
    );

    const merged = await this.mergeWithReviewState(scopedDetected);

    const statusFilter = (filters.status?.toUpperCase() ||
      "ALL") as DataQualityIssueFilterStatus;

    const issues = merged.filter((issue) => {
      const stagePass =
        !filters.stage ||
        filters.stage === "ALL" ||
        issue.stage === filters.stage;
      const severityPass =
        !filters.severity ||
        filters.severity === "ALL" ||
        issue.severity === filters.severity;
      const classPass =
        !filters.issueClass ||
        filters.issueClass === "ALL" ||
        issue.issueClass === filters.issueClass;
      const statusPass =
        statusFilter === "ALL" || issue.review.status === statusFilter;
      const sourcePass =
        !filters.sourceTable ||
        filters.sourceTable === "ALL" ||
        issue.sourceTable.toLowerCase() === filters.sourceTable.toLowerCase();

      return stagePass && severityPass && classPass && statusPass && sourcePass;
    });

    return {
      issues,
      artifactStats: snapshot.artifactStats,
      detectedAt: snapshot.detectedAt,
    };
  }

  private async applyEntityFieldUpdate(
    tx: Prisma.TransactionClient,
    entityType: string,
    entityId: string,
    fieldName: string,
    value: string,
  ) {
    const entityMap: Record<string, { table: string; fields: Set<string> }> = {
      document: {
        table: "app.documents",
        fields: new Set([
          "title_display",
          "title_normalized",
          "subtitle_normalized",
          "language_code",
          "isbn_normalized",
        ]),
      },
      book_copy: {
        table: "app.book_copies",
        fields: new Set(["inventory_number_normalized"]),
      },
      reader: {
        table: "app.readers",
        fields: new Set(["full_name_normalized"]),
      },
    };

    const mapping = entityMap[entityType];
    if (!mapping) {
      throw new BadRequestException(
        `Unsupported entity type for manual update: ${entityType}`,
      );
    }

    if (!mapping.fields.has(fieldName)) {
      throw new BadRequestException(
        `Field ${fieldName} is not editable for entity type ${entityType}`,
      );
    }

    await tx.$executeRawUnsafe(
      `UPDATE ${mapping.table} SET ${fieldName} = $1, updated_at = now() WHERE id = CAST($2 AS uuid)`,
      value,
      entityId,
    );
  }

  private parseJsonObject(value: unknown): Record<string, unknown> {
    if (value && typeof value === "object" && !Array.isArray(value)) {
      return value as Record<string, unknown>;
    }

    return {};
  }

  private readString(
    record: Record<string, unknown>,
    key: string,
  ): string | undefined {
    const value = record[key];
    return typeof value === "string" && value.trim() ? value.trim() : undefined;
  }

  private async resolveIssueForActor(
    actor: RequestUser,
    issueId: string,
    writeMode = false,
  ): Promise<DataQualityIssue> {
    const scope = await this.resolveActorScope(actor);
    const snapshot = this.getSnapshot();
    const detected = snapshot.issues.find((issue) => issue.id === issueId);

    if (!detected) {
      throw new NotFoundException(`Data quality issue not found: ${issueId}`);
    }

    if (!this.canAccessIssueByScope(scope, detected.branch)) {
      throw new ForbiddenException("Issue is out of librarian scope");
    }

    if (writeMode && actor.role === UserRole.ANALYST) {
      throw new ForbiddenException(
        "Analyst role is read-only for data quality actions",
      );
    }

    const [mergedIssue] = await this.mergeWithReviewState([detected]);
    return mergedIssue;
  }

  private async mergeWithReviewState(
    detectedIssues: DataQualityDetectedIssue[],
  ): Promise<DataQualityIssue[]> {
    if (detectedIssues.length === 0) {
      return [];
    }

    const issueIds = detectedIssues.map((issue) => issue.id);

    const reviews = await this.prisma.dataQualityIssueReview.findMany({
      where: { issueId: { in: issueIds } },
      include: {
        assignedToUser: {
          select: {
            id: true,
            fullName: true,
          },
        },
        lastReviewedByUser: {
          select: {
            id: true,
            fullName: true,
          },
        },
        _count: {
          select: {
            notes: true,
          },
        },
      },
    });

    const reviewMap = new Map(reviews.map((item) => [item.issueId, item]));

    return detectedIssues.map((issue) => {
      const review = reviewMap.get(issue.id);
      return {
        ...issue,
        review: {
          status: review?.status ?? DataQualityReviewStatus.OPEN,
          assignedToUserId: review?.assignedToUserId ?? undefined,
          assignedToName: review?.assignedToUser?.fullName ?? undefined,
          latestNote: review?.latestNote ?? undefined,
          noteCount: review?._count.notes ?? 0,
          lastReviewedByUserId: review?.lastReviewedByUserId ?? undefined,
          lastReviewedByName: review?.lastReviewedByUser?.fullName ?? undefined,
          updatedAt: review?.updatedAt.toISOString() ?? undefined,
        },
      };
    });
  }

  private async getIssueNotes(issueId: string) {
    const review = await this.prisma.dataQualityIssueReview.findUnique({
      where: { issueId },
      select: { id: true },
    });

    if (!review) {
      return [];
    }

    const notes = await this.prisma.dataQualityIssueReviewNote.findMany({
      where: { reviewId: review.id },
      orderBy: { createdAt: "desc" },
      take: 20,
      include: {
        user: {
          select: {
            id: true,
            fullName: true,
          },
        },
      },
    });

    return notes.map((note) => ({
      id: note.id,
      note: note.note,
      createdAt: note.createdAt.toISOString(),
      userId: note.user.id,
      userName: note.user.fullName,
    }));
  }

  private async resolveActorScope(actor: RequestUser): Promise<ScopeContext> {
    if (actor.role === UserRole.ADMIN || actor.role === UserRole.ANALYST) {
      return {
        role: actor.role,
        branchId: null,
        branchCode: null,
      };
    }

    if (actor.role === UserRole.LIBRARIAN) {
      if (actor.universityId === "librarian_demo") {
        return {
          role: actor.role,
          branchId: null,
          branchCode: LibraryBranchCode.ECONOMIC_LIBRARY,
        };
      }

      const user = await this.prisma.user.findUnique({
        where: { id: actor.id },
        select: {
          libraryBranchId: true,
          libraryBranch: {
            select: { code: true },
          },
        },
      });

      if (!user?.libraryBranchId || !user.libraryBranch?.code) {
        throw new ForbiddenException(
          "Librarian must be assigned to a library branch",
        );
      }

      return {
        role: actor.role,
        branchId: user.libraryBranchId,
        branchCode: user.libraryBranch.code,
      };
    }

    throw new ForbiddenException("Access denied");
  }

  private canAccessIssueByScope(
    scope: ScopeContext,
    issueBranchRaw?: string,
  ): boolean {
    if (scope.role === UserRole.ADMIN || scope.role === UserRole.ANALYST) {
      return true;
    }

    if (!scope.branchCode) {
      return false;
    }

    if (!issueBranchRaw) {
      // Unknown branch ownership fallback: allow librarian review.
      return true;
    }

    const issueBranch = this.mapLegacyBranch(issueBranchRaw);
    if (!issueBranch) {
      // Unknown mapping fallback: keep visible so records are not orphaned from review.
      return true;
    }

    return issueBranch === scope.branchCode;
  }

  private mapLegacyBranch(raw: string): LibraryBranchCode | null {
    const normalized = raw.toUpperCase();

    if (
      normalized.includes("ECONOMIC_LIBRARY") ||
      normalized.includes("КУР") ||
      normalized.includes("ECONOMIC")
    ) {
      return LibraryBranchCode.ECONOMIC_LIBRARY;
    }
    if (
      normalized.includes("TECHNOLOGICAL_LIBRARY") ||
      normalized.includes("КСИ") ||
      normalized.includes("TECHNOLOGICAL")
    ) {
      return LibraryBranchCode.TECHNOLOGICAL_LIBRARY;
    }
    if (
      normalized.includes("COLLEGE_LIBRARY") ||
      normalized.includes("КУК") ||
      normalized.includes("COLLEGE")
    ) {
      return LibraryBranchCode.COLLEGE_LIBRARY;
    }

    return null;
  }

  private assertValidReviewStatus(status: string) {
    const valid = Object.values(DataQualityReviewStatus);
    if (!valid.includes(status as DataQualityReviewStatus)) {
      throw new BadRequestException(
        `Invalid review status. Allowed: ${valid.join(", ")}`,
      );
    }
  }

  private getSnapshot(): DataQualitySnapshot {
    if (this.cache) {
      return this.cache;
    }

    const artifactsDir = this.resolveArtifactsDir();
    const docViewPath = path.join(artifactsDir, "dbo_DOC_VIEW.csv");
    const rowCountsPath = path.join(artifactsDir, "table_row_counts.csv");
    const columnsPath = path.join(artifactsDir, "columns_inventory.csv");
    const foreignKeysPath = path.join(artifactsDir, "foreign_keys.csv");

    const docRows = this.readFileLines(docViewPath).slice(0, 1500);
    const rowCountsRows = this.readFileLines(rowCountsPath);
    const columnsRows = this.readFileLines(columnsPath);
    const foreignKeyRows = this.readFileLines(foreignKeysPath);

    const docTableRowCount = this.findTableCount(rowCountsRows, "DOC");

    const distinctTables = new Set<string>();
    for (const line of columnsRows) {
      const parts = line.split(";");
      if (parts.length >= 2 && parts[1]) {
        distinctTables.add(parts[1]);
      }
    }

    const issues = docRows.flatMap((line, index) =>
      this.detectDocViewIssues(line, index),
    );

    this.cache = {
      issues,
      artifactStats: {
        sourceDir: artifactsDir,
        docViewRowsScanned: docRows.length,
        docTableRowCount,
        totalLegacyTables: distinctTables.size,
        foreignKeyCount: foreignKeyRows.length,
      },
      detectedAt: new Date().toISOString(),
    };

    return this.cache;
  }

  private detectDocViewIssues(
    line: string,
    rowIndex: number,
  ): DataQualityDetectedIssue[] {
    const sourceRecordKey =
      this.extractRecordKey(line) ??
      this.extractTagSubfield(line, "001", "0") ??
      `ROW:${rowIndex + 1}`;

    const title = this.extractTagSubfield(line, "245", "a");
    const titleRemainder = this.extractTagSubfield(line, "245", "b");
    const author =
      this.extractTagSubfield(line, "1001", "a") ??
      this.extractTagSubfield(line, "245", "c") ??
      this.extractTagSubfield(line, "7001", "a");
    const isbn = this.extractTagSubfield(line, "020", "a");
    const languageCode = this.extractTagSubfield(line, "041", "a");
    const publicationYear =
      this.extractYearFromTag260(line) ?? this.extractAnyFourDigitYear(line);
    const publisher = this.extractTagSubfield(line, "260", "b");
    const publicationPlace = this.extractTagSubfield(line, "260", "a");
    const branch = this.extractTagSubfield(line, "952", "a") ?? undefined;

    const finalTitle = [title, titleRemainder].filter(Boolean).join(" ").trim();

    const metadataPresenceCount = [
      finalTitle,
      author,
      isbn,
      languageCode,
      publicationYear,
      publisher,
      publicationPlace,
    ].filter(Boolean).length;

    const sharedContext = {
      title: finalTitle || undefined,
      author: author || undefined,
      isbn: isbn || undefined,
      languageCode: languageCode || undefined,
      publicationYear: publicationYear || undefined,
      publisher: publisher || undefined,
      publicationPlace: publicationPlace || undefined,
    };

    const issues: DataQualityDetectedIssue[] = [];

    if (!finalTitle) {
      issues.push(
        this.createDetectedIssue({
          sourceRecordKey,
          branch,
          fieldName: "title",
          severity: "CRITICAL",
          issueClass: "IDENTITY",
          stage: "clean",
          autoFixable: false,
          detectionRule: "missing_title",
          summary:
            "Title is missing in bibliographic payload (MARC 245$a/245$b).",
          sourceContext: sharedContext,
        }),
      );
    }

    if (!author) {
      issues.push(
        this.createDetectedIssue({
          sourceRecordKey,
          branch,
          fieldName: "author",
          severity: "HIGH",
          issueClass: "SEMANTIC",
          stage: "clean",
          autoFixable: false,
          detectionRule: "missing_author",
          summary:
            "Primary author is missing (checked MARC 1001$a, 245$c, and 7001$a).",
          sourceContext: sharedContext,
        }),
      );
    }

    if (!publicationYear) {
      issues.push(
        this.createDetectedIssue({
          sourceRecordKey,
          branch,
          fieldName: "publication_year",
          severity: "HIGH",
          issueClass: "FORMAT",
          stage: "clean",
          autoFixable: false,
          detectionRule: "missing_publication_year",
          summary:
            "Publication year is missing or not detectable from MARC 260$c.",
          sourceContext: sharedContext,
        }),
      );
    }

    if (!languageCode) {
      issues.push(
        this.createDetectedIssue({
          sourceRecordKey,
          branch,
          fieldName: "language_code",
          severity: "MEDIUM",
          issueClass: "SEMANTIC",
          stage: "clean",
          autoFixable: false,
          detectionRule: "missing_language_code",
          summary: "Language code is missing in MARC 041$a.",
          sourceContext: sharedContext,
        }),
      );
    }

    if (isbn && !this.isValidIsbn(isbn)) {
      issues.push(
        this.createDetectedIssue({
          sourceRecordKey,
          branch,
          fieldName: "isbn",
          severity: "MEDIUM",
          issueClass: "FORMAT",
          stage: "normalized",
          autoFixable: true,
          detectionRule: "malformed_isbn",
          summary:
            "ISBN exists but fails ISBN-10/ISBN-13 checksum validation after normalization.",
          sourceContext: sharedContext,
        }),
      );
    }

    if (!publisher || !publicationPlace) {
      issues.push(
        this.createDetectedIssue({
          sourceRecordKey,
          branch,
          fieldName: "publication_metadata",
          severity: "MEDIUM",
          issueClass: "GOVERNANCE",
          stage: "clean",
          autoFixable: false,
          detectionRule: "incomplete_publication_metadata",
          summary:
            "Publication metadata is incomplete (missing MARC 260$a place or 260$b publisher).",
          sourceContext: sharedContext,
        }),
      );
    }

    if (metadataPresenceCount <= 2) {
      issues.push(
        this.createDetectedIssue({
          sourceRecordKey,
          branch,
          fieldName: "record_density",
          severity: "LOW",
          issueClass: "DERIVED",
          stage: "raw",
          autoFixable: true,
          detectionRule: "suspiciously_sparse_record",
          summary:
            "Record is suspiciously sparse: too few core bibliographic fields are populated.",
          sourceContext: sharedContext,
        }),
      );
    }

    return issues;
  }

  private createDetectedIssue(params: {
    sourceRecordKey: string;
    branch?: string;
    fieldName: string;
    severity: DataQualitySeverity;
    issueClass: DataQualityIssueClass;
    stage: DataQualityStage;
    autoFixable: boolean;
    detectionRule: string;
    summary: string;
    sourceContext: DataQualityDetectedIssue["sourceContext"];
  }): DataQualityDetectedIssue {
    const id = `DQ-${params.detectionRule}-${this.stableHashToken(params.sourceRecordKey)}`;

    return {
      id,
      batchId: "legacy-artifact-snapshot-2026-03",
      stage: params.stage,
      severity: params.severity,
      issueClass: params.issueClass,
      sourceTable: "DOC_VIEW",
      sourceRecordKey: params.sourceRecordKey,
      branch: params.branch,
      fieldName: params.fieldName,
      autoFixable: params.autoFixable,
      detectedAt: new Date().toISOString().slice(0, 10),
      summary: params.summary,
      detectionRule: params.detectionRule,
      sourceContext: params.sourceContext,
    };
  }

  private resolveArtifactsDir(): string {
    const configuredPath = process.env.LEGACY_DISCOVERY_DIR;

    const candidates = [
      configuredPath,
      path.resolve(process.cwd(), "../docs/legacy-db-discovery"),
      path.resolve(process.cwd(), "docs/legacy-db-discovery"),
      path.resolve(__dirname, "../../../../docs/legacy-db-discovery"),
    ].filter(Boolean) as string[];

    for (const candidate of candidates) {
      if (existsSync(path.join(candidate, "dbo_DOC_VIEW.csv"))) {
        return candidate;
      }
    }

    throw new Error(
      "Legacy discovery artifacts not found. Set LEGACY_DISCOVERY_DIR or ensure docs/legacy-db-discovery exists.",
    );
  }

  private readFileLines(filePath: string): string[] {
    const raw = readFileSync(filePath, "utf8").replace(/^\uFEFF/, "");
    return raw
      .split(/\r?\n/)
      .map((line) => line.trim())
      .filter((line) => line.length > 0);
  }

  private extractTagSubfield(
    line: string,
    tag: string,
    subfield: string,
  ): string | null {
    const pattern = new RegExp(
      `\\u001e${tag}\\s+[^\\u001f]*\\u001f${subfield}([^\\u001e]+)`,
      "i",
    );
    const match = line.match(pattern);
    return this.normalizeText(match?.[1] ?? null);
  }

  private extractRecordKey(line: string): string | null {
    const match = line.match(/RU\/IS\/BASE\/\d+/i);
    return match ? match[0].toUpperCase() : null;
  }

  private extractYearFromTag260(line: string): string | null {
    const value = this.extractTagSubfield(line, "260", "c");
    if (!value) {
      return null;
    }
    const match = value.match(/\b(19|20)\d{2}\b/);
    return match ? match[0] : null;
  }

  private extractAnyFourDigitYear(line: string): string | null {
    const match = line.match(/\b(19|20)\d{2}\b/);
    return match ? match[0] : null;
  }

  private isValidIsbn(rawIsbn: string): boolean {
    const normalized = rawIsbn.toUpperCase().replace(/[^0-9X]/g, "");

    if (normalized.length === 10) {
      let sum = 0;
      for (let i = 0; i < 9; i += 1) {
        const digit = Number.parseInt(normalized[i], 10);
        if (Number.isNaN(digit)) {
          return false;
        }
        sum += digit * (10 - i);
      }

      const checkChar = normalized[9];
      const checkValue =
        checkChar === "X" ? 10 : Number.parseInt(checkChar, 10);
      if (Number.isNaN(checkValue)) {
        return false;
      }

      sum += checkValue;
      return sum % 11 === 0;
    }

    if (normalized.length === 13) {
      let sum = 0;
      for (let i = 0; i < 12; i += 1) {
        const digit = Number.parseInt(normalized[i], 10);
        if (Number.isNaN(digit)) {
          return false;
        }
        sum += digit * (i % 2 === 0 ? 1 : 3);
      }

      const expected = (10 - (sum % 10)) % 10;
      const checkDigit = Number.parseInt(normalized[12], 10);
      if (Number.isNaN(checkDigit)) {
        return false;
      }
      return expected === checkDigit;
    }

    return false;
  }

  private normalizeText(value: string | null): string | null {
    if (!value) {
      return null;
    }
    const normalized = value
      .replace(/\u0000/g, "")
      .replace(/\s+/g, " ")
      .replace(/^NULL$/i, "")
      .trim();

    return normalized.length > 0 ? normalized : null;
  }

  private findTableCount(lines: string[], tableName: string): number | null {
    for (const line of lines) {
      const [name, value] = line.split(";");
      if (name?.toUpperCase() === tableName.toUpperCase()) {
        const parsed = Number.parseInt(value ?? "", 10);
        return Number.isFinite(parsed) ? parsed : null;
      }
    }
    return null;
  }

  private stableHashNumber(input: string): number {
    let hash = 0;
    for (let i = 0; i < input.length; i += 1) {
      hash = (hash << 5) - hash + input.charCodeAt(i);
      hash |= 0;
    }
    return Math.abs(hash);
  }

  private stableHashToken(input: string): string {
    return this.stableHashNumber(input)
      .toString(36)
      .padStart(6, "0")
      .slice(0, 6);
  }
}
