import { useQuery } from "@tanstack/react-query";

import {
  fetchDashboard,
  fetchPopularBooks,
  fetchActivity,
  fetchReportsOverview,
} from "../api/analytics-api";

export function useDashboard() {
  return useQuery({
    queryKey: ["analytics", "dashboard"],
    queryFn: fetchDashboard,
    staleTime: 30_000,
  });
}

export function usePopularBooks(limit = 10) {
  return useQuery({
    queryKey: ["analytics", "popular-books", limit],
    queryFn: () => fetchPopularBooks(limit),
    staleTime: 60_000,
  });
}

export function useActivity() {
  return useQuery({
    queryKey: ["analytics", "activity"],
    queryFn: fetchActivity,
    staleTime: 30_000,
  });
}

export function useReportsOverview(year?: number) {
  return useQuery({
    queryKey: ["reports", "overview", year],
    queryFn: () => fetchReportsOverview(year),
    staleTime: 60_000,
  });
}
