export type DataQualityStage = "raw" | "clean" | "normalized";

export type DataQualitySeverity = "CRITICAL" | "HIGH" | "MEDIUM" | "LOW";

export type DataQualityIssueClass =
  | "IDENTITY"
  | "REFERENTIAL"
  | "SEMANTIC"
  | "FORMAT"
  | "GOVERNANCE"
  | "DERIVED";

export type DataQualityReviewStatus =
  | "OPEN"
  | "IN_REVIEW"
  | "NEEDS_METADATA_COMPLETION"
  | "DUPLICATE_CANDIDATE"
  | "ESCALATED"
  | "REVIEWED";

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
  autoFixable: boolean;
  detectedAt: string;
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

export interface DataQualityIssueNote {
  id: string;
  note: string;
  createdAt: string;
  userId: string;
  userName: string;
}

export interface DataQualitySummary {
  total: number;
  critical: number;
  high: number;
  reviewed: number;
  bySeverity: Record<DataQualitySeverity, number>;
  byClass: Record<DataQualityIssueClass, number>;
  byStatus: Record<DataQualityReviewStatus, number>;
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

export interface DataQualityIssueDetailResponse {
  data: DataQualityIssue;
  notes: DataQualityIssueNote[];
  detectedAt: string;
}

export interface DataQualityFilters {
  stage: DataQualityStage;
  severity: DataQualitySeverity | "ALL";
  issueClass: DataQualityIssueClass | "ALL";
  status: DataQualityReviewStatus | "ALL";
  sourceTable: string | "ALL";
}
