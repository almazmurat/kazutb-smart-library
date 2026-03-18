import axios from "axios";

export interface PublicCatalogBookItem {
  id: string;
  title: string;
  subtitle?: string | null;
  publishYear?: number | null;
  language?: string | null;
  description?: string | null;
  libraryBranch: {
    id: string;
    code: string;
    name: string;
    scope: {
      id: string;
      code: string;
      name: string;
    };
  };
  authors: Array<{
    id: string;
    fullName: string;
  }>;
  categories: Array<{
    id: string;
    name: string;
  }>;
  availability: {
    total: number;
    available: number;
  };
}

export interface PublicCatalogPagination {
  page: number;
  limit: number;
  total: number;
  totalPages: number;
}

export interface PublicCatalogBooksResponse {
  data: PublicCatalogBookItem[];
  meta: PublicCatalogPagination;
}

export interface PublicCatalogBookDetails extends PublicCatalogBookItem {
  isbn?: string | null;
}

export interface PublicCatalogFilters {
  categories: Array<{
    id: string;
    name: string;
  }>;
  branches: Array<{
    id: string;
    code: string;
    name: string;
    scope: {
      code: string;
    };
  }>;
  languages: string[];
}

export interface PublicCatalogQuery {
  title?: string;
  author?: string;
  categoryId?: string;
  branchId?: string;
  language?: string;
  page?: number;
  limit?: number;
}

const api = axios.create({
  baseURL: "/api/v1",
});

export async function fetchPublicBooks(
  query: PublicCatalogQuery,
): Promise<PublicCatalogBooksResponse> {
  const { data } = await api.get<PublicCatalogBooksResponse>("/public/books", {
    params: query,
  });

  return {
    data: Array.isArray(data?.data) ? data.data : [],
    meta: {
      page: data?.meta?.page ?? query.page ?? 1,
      limit: data?.meta?.limit ?? query.limit ?? 20,
      total: data?.meta?.total ?? 0,
      totalPages: data?.meta?.totalPages ?? 1,
    },
  };
}

export async function fetchPublicBookById(
  id: string,
): Promise<PublicCatalogBookDetails> {
  const { data } = await api.get<PublicCatalogBookDetails>(
    `/public/books/${id}`,
  );
  return data;
}

export async function fetchPublicFilters(): Promise<PublicCatalogFilters> {
  const { data } = await api.get<PublicCatalogFilters>("/public/filters");
  return data;
}
