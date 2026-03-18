import axios from "axios";

import type {
  DataQualityFilters,
  DataQualityIssue,
  DataQualityIssuesResponse,
  DataQualitySummary,
} from "../types";

const API_BASE = "/api/v1/migration/data-quality";

function normalizeFilters(filters: Partial<DataQualityFilters>) {
  return {
    stage: filters.stage,
    severity: filters.severity && filters.severity !== "ALL" ? filters.severity : undefined,
    issueClass:
      filters.issueClass && filters.issueClass !== "ALL"
        ? filters.issueClass
        : undefined,
    status: filters.status && filters.status !== "ALL" ? filters.status : undefined,
    sourceTable:
      filters.sourceTable && filters.sourceTable !== "ALL"
        ? filters.sourceTable
        : undefined,
  };
}

export async function fetchDataQualitySummary(
  filters: Partial<DataQualityFilters>,
): Promise<DataQualitySummary> {
  const { data } = await axios.get<{ data: DataQualitySummary }>(
    `${API_BASE}/summary`,
    {
      params: normalizeFilters(filters),
    },
  );
  return data.data;
}

export async function fetchDataQualityIssues(
  filters: Partial<DataQualityFilters>,
): Promise<DataQualityIssuesResponse> {
  const { data } = await axios.get<{ data: DataQualityIssuesResponse }>(
    `${API_BASE}/issues`,
    {
      params: {
        ...normalizeFilters(filters),
        limit: 500,
      },
    },
  );
  return data.data;
}

export async function fetchDataQualityIssueById(
  id: string,
): Promise<DataQualityIssue> {
  const { data } = await axios.get<{ data: DataQualityIssue }>(
    `${API_BASE}/issues/${id}`,
  );
  return data.data;
}
