import { useQuery } from "@tanstack/react-query";

import {
  fetchPublicBookById,
  fetchPublicBooks,
  fetchPublicFilters,
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
    queryFn: fetchPublicFilters,
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
