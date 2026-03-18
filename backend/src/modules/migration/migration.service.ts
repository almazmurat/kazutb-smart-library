import { Injectable, NotFoundException } from "@nestjs/common";
import { existsSync, readFileSync } from "node:fs";
import * as path from "node:path";

type DataQualityStage = "raw" | "clean" | "normalized";
type DataQualitySeverity = "CRITICAL" | "HIGH" | "MEDIUM" | "LOW";
type DataQualityIssueClass =
  | "IDENTITY"
  | "REFERENTIAL"
  | "SEMANTIC"
  | "FORMAT"
  | "GOVERNANCE"
  | "DERIVED";
type DataQualityIssueStatus =
  | "new"
  | "in_review"
  | "approved"
  | "rejected"
  | "fixed";

interface DataQualityIssue {
  id: string;
  batchId: string;
  stage: DataQualityStage;
  severity: DataQualitySeverity;
  issueClass: DataQualityIssueClass;
  sourceTable: string;
  sourceRecordKey: string;
  branch?: string;
  fieldName?: string;
  status: DataQualityIssueStatus;
  autoFixable: boolean;
  detectedAt: string;
  reviewer?: string;
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

interface DataQualitySnapshot {
  issues: DataQualityIssue[];
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

@Injectable()
export class MigrationService {
  private readonly workflowStates: DataQualityIssueStatus[] = [
    "new",
    "in_review",
    "approved",
    "rejected",
    "fixed",
  ];

  private cache: DataQualitySnapshot | null = null;

  list() {
    return [];
  }

  getDataQualitySummary(filters: DataQualityFilters = {}) {
    const { issues, artifactStats, detectedAt } = this.getFilteredIssues(filters);

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

    const byStatus: Record<DataQualityIssueStatus, number> = {
      new: 0,
      in_review: 0,
      approved: 0,
      rejected: 0,
      fixed: 0,
    };

    for (const issue of issues) {
      bySeverity[issue.severity] += 1;
      byClass[issue.issueClass] += 1;
      byStatus[issue.status] += 1;
    }

    return {
      total: issues.length,
      critical: bySeverity.CRITICAL,
      high: bySeverity.HIGH,
      autoFixable: issues.filter((item) => item.autoFixable).length,
      bySeverity,
      byClass,
      byStatus,
      artifactStats,
      detectedAt,
    };
  }

  getDataQualityIssues(filters: DataQualityFilters = {}) {
    const { issues, artifactStats, detectedAt } = this.getFilteredIssues(filters);
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

  getDataQualityIssueById(id: string) {
    const snapshot = this.getSnapshot();
    const issue = snapshot.issues.find((item) => item.id === id);

    if (!issue) {
      throw new NotFoundException(`Data quality issue not found: ${id}`);
    }

    return {
      data: issue,
      artifactStats: snapshot.artifactStats,
      detectedAt: snapshot.detectedAt,
    };
  }

  private getFilteredIssues(filters: DataQualityFilters) {
    const snapshot = this.getSnapshot();

    const issues = snapshot.issues.filter((issue) => {
      const stagePass = !filters.stage || filters.stage === "ALL" || issue.stage === filters.stage;
      const severityPass =
        !filters.severity || filters.severity === "ALL" || issue.severity === filters.severity;
      const classPass =
        !filters.issueClass ||
        filters.issueClass === "ALL" ||
        issue.issueClass === filters.issueClass;
      const statusPass =
        !filters.status || filters.status === "ALL" || issue.status === filters.status;
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

  private detectDocViewIssues(line: string, rowIndex: number): DataQualityIssue[] {
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

    const issues: DataQualityIssue[] = [];

    if (!finalTitle) {
      issues.push(
        this.createIssue({
          sourceRecordKey,
          branch,
          fieldName: "title",
          severity: "CRITICAL",
          issueClass: "IDENTITY",
          stage: "clean",
          autoFixable: false,
          detectionRule: "missing_title",
          summary: "Title is missing in bibliographic payload (MARC 245$a/245$b).",
          sourceContext: sharedContext,
        }),
      );
    }

    if (!author) {
      issues.push(
        this.createIssue({
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
        this.createIssue({
          sourceRecordKey,
          branch,
          fieldName: "publication_year",
          severity: "HIGH",
          issueClass: "FORMAT",
          stage: "clean",
          autoFixable: false,
          detectionRule: "missing_publication_year",
          summary: "Publication year is missing or not detectable from MARC 260$c.",
          sourceContext: sharedContext,
        }),
      );
    }

    if (!languageCode) {
      issues.push(
        this.createIssue({
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
        this.createIssue({
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
        this.createIssue({
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
        this.createIssue({
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

  private createIssue(params: {
    sourceRecordKey: string;
    branch?: string;
    fieldName: string;
    severity: DataQualitySeverity;
    issueClass: DataQualityIssueClass;
    stage: DataQualityStage;
    autoFixable: boolean;
    detectionRule: string;
    summary: string;
    sourceContext: DataQualityIssue["sourceContext"];
  }): DataQualityIssue {
    const id = `DQ-${params.detectionRule}-${this.stableHashToken(params.sourceRecordKey)}`;
    const status = this.workflowStates[
      this.stableHashNumber(`${params.detectionRule}:${params.sourceRecordKey}`) %
        this.workflowStates.length
    ];

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
      status,
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
      const checkValue = checkChar === "X" ? 10 : Number.parseInt(checkChar, 10);
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