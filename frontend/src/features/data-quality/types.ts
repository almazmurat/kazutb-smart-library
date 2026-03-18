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
}
