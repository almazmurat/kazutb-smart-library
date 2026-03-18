import { useQuery } from "@tanstack/react-query";

import type { DataQualityFilters } from "../types";
import {
  fetchDataQualityIssueById,
  fetchDataQualityIssues,
  fetchDataQualitySummary,
} from "../api/data-quality-api";

export function useDataQualitySummary(filters: Partial<DataQualityFilters>) {
  return useQuery({
    queryKey: ["migration", "data-quality", "summary", filters],
    queryFn: () => fetchDataQualitySummary(filters),
    staleTime: 30_000,
  });
}

export function useDataQualityIssues(filters: Partial<DataQualityFilters>) {
  return useQuery({
    queryKey: ["migration", "data-quality", "issues", filters],
    queryFn: () => fetchDataQualityIssues(filters),
    staleTime: 30_000,
  });
}

export function useDataQualityIssue(id: string | null) {
  return useQuery({
    queryKey: ["migration", "data-quality", "issue", id],
    queryFn: () => fetchDataQualityIssueById(id ?? ""),
    enabled: Boolean(id),
    staleTime: 30_000,
  });
}
