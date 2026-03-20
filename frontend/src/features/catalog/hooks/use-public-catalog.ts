import { useQuery } from "@tanstack/react-query";

import {
  AppReviewQuery,
  fetchPublicBookById,
  fetchAppReviewIssueDetail,
  fetchAppReviewQueue,
  fetchCatalogFacets,
  fetchLocationSummary,
  fetchPublicBookAvailability,
  fetchPublicBooks,
  PublicCatalogQuery,
} from "../api/public-catalog-api";

export function usePublicCatalog(query: PublicCatalogQuery) {
  return useQuery({
    queryKey: ["public-catalog", query],
    queryFn: () => fetchPublicBooks(query),
  });
}

export function usePublicCatalogFilters() {
  return useQuery({
    queryKey: ["public-catalog-filters"],
    queryFn: fetchCatalogFacets,
    staleTime: 60_000,
  });
}

export function usePublicBookDetails(id: string) {
  return useQuery({
    queryKey: ["public-catalog-book", id],
    queryFn: () => fetchPublicBookById(id),
    enabled: Boolean(id),
  });
}

export function usePublicBookAvailability(
  id: string,
  query?: Pick<
    PublicCatalogQuery,
    "institutionUnitCode" | "campusCode" | "servicePointCode"
  >,
) {
  return useQuery({
    queryKey: ["public-catalog-book-availability", id, query],
    queryFn: () => fetchPublicBookAvailability(id, query),
    enabled: Boolean(id),
  });
}

export function useLocationSummary(
  query?: Pick<
    PublicCatalogQuery,
    "institutionUnitCode" | "campusCode" | "servicePointCode"
  >,
) {
  return useQuery({
    queryKey: ["location-summary", query],
    queryFn: () => fetchLocationSummary(query),
    staleTime: 60_000,
  });
}

export function useAppReviewQueue(query: AppReviewQuery) {
  return useQuery({
    queryKey: ["app-review-queue", query],
    queryFn: () => fetchAppReviewQueue(query),
  });
}

export function useAppReviewIssueDetail(flagId: string) {
  return useQuery({
    queryKey: ["app-review-issue", flagId],
    queryFn: () => fetchAppReviewIssueDetail(flagId),
    enabled: Boolean(flagId),
  });
}
