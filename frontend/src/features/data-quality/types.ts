export type DataQualityStage = "raw" | "clean" | "normalized";

export type DataQualitySeverity = "CRITICAL" | "HIGH" | "MEDIUM" | "LOW";

export type DataQualityIssueClass =
  | "IDENTITY"
  | "REFERENTIAL"
  | "SEMANTIC"
  | "FORMAT"
  | "GOVERNANCE"
  | "DERIVED";

export type DataQualityIssueStatus =
  | "new"
  | "in_review"
  | "approved"
  | "rejected"
  | "fixed";

export interface DataQualityIssue {
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
  detectionRule?: string;
  sourceContext?: {
    title?: string;
    author?: string;
    isbn?: string;
    languageCode?: string;
    publicationYear?: string;
    publisher?: string;
    publicationPlace?: string;
  };
}

export interface DataQualitySummary {
  total: number;
  critical: number;
  high: number;
  autoFixable: number;
  bySeverity: Record<DataQualitySeverity, number>;
  byClass: Record<DataQualityIssueClass, number>;
  byStatus: Record<DataQualityIssueStatus, number>;
  artifactStats: {
    sourceDir: string;
    docViewRowsScanned: number;
    docTableRowCount: number | null;
    totalLegacyTables: number;
    foreignKeyCount: number;
  };
  detectedAt: string;
}

export interface DataQualityIssuesResponse {
  total: number;
  items: DataQualityIssue[];
  detectedAt: string;
}

export interface DataQualityFilters {
  stage: DataQualityStage;
  severity: DataQualitySeverity | "ALL";
  issueClass: DataQualityIssueClass | "ALL";
  status: DataQualityIssueStatus | "ALL";
  sourceTable: string | "ALL";
}
