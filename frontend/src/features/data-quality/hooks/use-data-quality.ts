import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";

import type { DataQualityFilters } from "../types";
import {
  fetchDataQualityIssueById,
  fetchDataQualityIssues,
  fetchDataQualitySummary,
  patchDataQualityIssueAssignee,
  patchDataQualityIssueReview,
  postDataQualityIssueNote,
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

function invalidateDataQuality(queryClient: ReturnType<typeof useQueryClient>) {
  return Promise.all([
    queryClient.invalidateQueries({ queryKey: ["migration", "data-quality", "summary"] }),
    queryClient.invalidateQueries({ queryKey: ["migration", "data-quality", "issues"] }),
    queryClient.invalidateQueries({ queryKey: ["migration", "data-quality", "issue"] }),
  ]);
}

export function useUpdateDataQualityReview() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({
      id,
      status,
      note,
    }: {
      id: string;
      status: "OPEN" | "IN_REVIEW" | "NEEDS_METADATA_COMPLETION" | "DUPLICATE_CANDIDATE" | "ESCALATED" | "REVIEWED";
      note?: string;
    }) => patchDataQualityIssueReview(id, { status, note }),
    onSuccess: () => invalidateDataQuality(queryClient),
  });
}

export function useAddDataQualityNote() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ id, note }: { id: string; note: string }) =>
      postDataQualityIssueNote(id, { note }),
    onSuccess: () => invalidateDataQuality(queryClient),
  });
}

export function useAssignDataQualityIssue() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({
      id,
      assigneeUserId,
    }: {
      id: string;
      assigneeUserId?: string;
    }) => patchDataQualityIssueAssignee(id, { assigneeUserId }),
    onSuccess: () => invalidateDataQuality(queryClient),
  });
}
