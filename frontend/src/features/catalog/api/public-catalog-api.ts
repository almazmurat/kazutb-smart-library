import axios from "axios";

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    page: number;
    limit: number;
    total: number;
    totalPages: number;
  };
}

export interface CatalogSearchItem {
  id: string;
  legacyDocId: number;
  title: {
    display: string | null;
    normalized: string | null;
    raw: string | null;
    subtitle: string | null;
  };
  primaryAuthor: string | null;
  authors: unknown[];
  publisher: {
    id: string;
    name: string | null;
  } | null;
  publicationYear: number | null;
  language: {
    code: string | null;
    raw: string | null;
  };
  isbn: {
    raw: string | null;
    normalized: string | null;
    isValid: boolean;
  };
  availability: {
    total: number;
    available: number;
    unavailable: number;
    review: number;
    problem: number;
    orphan: number;
  };
  locations: {
    institutionUnitCodes: string[];
    campusCodes: string[];
    campusNames: string[];
    servicePointCodes: string[];
    servicePointNames: string[];
  };
  review: {
    documentNeedsReview: boolean;
    hasOpenReview: boolean;
    highestSeverity: string | null;
    issueCodes: string[];
    openTaskCount: number;
    documentFlagCount: number;
    copyFlagCount: number;
  };
}

export interface CatalogDetailResponse {
  id: string;
  legacyDocId: number;
  controlNumber: string | null;
  title: {
    display: string | null;
    normalized: string | null;
    raw: string | null;
    subtitle: string | null;
    variants: unknown[];
  };
  publisher: {
    id: string;
    name: string | null;
  } | null;
  publication: {
    placeRaw: string | null;
    placeNormalized: string | null;
    year: number | null;
  };
  language: {
    code: string | null;
    raw: string | null;
  };
  isbn: {
    raw: string | null;
    normalized: string | null;
    isValid: boolean;
  };
  authors: unknown[];
  subjects: unknown[];
  keywords: unknown[];
  classification: {
    faculty: { raw: string | null; normalized: string | null };
    department: { raw: string | null; normalized: string | null };
    specialization: { raw: string | null; normalized: string | null };
    literatureType: { raw: string | null; normalized: string | null };
  };
  copySummary: Record<string, unknown>;
  campusDistribution: unknown[];
  servicePointDistribution: unknown[];
}

export interface CatalogAvailabilityResponse {
  documentId: string;
  legacyDocId: number;
  title: string | null;
  items: Array<{
    institutionUnit: {
      id: string | null;
      code: string;
      name: string | null;
    } | null;
    campus: { id: string | null; code: string; name: string | null } | null;
    servicePoint: {
      id: string | null;
      code: string;
      name: string | null;
    } | null;
    copies: {
      total: number;
      available: number;
      unavailable: number;
      review: number;
      problem: number;
      orphan: number;
    };
  }>;
}

export interface CatalogFacetItem {
  type: string;
  value: string;
  label: string;
  counts: {
    documents: number;
    total: number;
    totalCopies: number;
    availableCopies: number;
    reviewDocuments: number;
  };
}

export interface CatalogFacetsResponse {
  languages: CatalogFacetItem[];
  campuses: CatalogFacetItem[];
  servicePoints: CatalogFacetItem[];
  availability: CatalogFacetItem[];
}

export interface AppReviewQueueItem {
  flagId: string;
  taskId: string | null;
  entityType: string;
  entityId: string;
  issueCode: string;
  severity: string;
  flagStatus: string;
  task: {
    id: string;
    type: string | null;
    priority: string | null;
    status: string | null;
    title: string | null;
    description: string | null;
    assignedTo: string | null;
    createdAt: string | null;
  } | null;
  values: {
    raw: string | null;
    normalized: string | null;
    suggested: string | null;
    confidenceScore: number | null;
  };
  context: {
    documentId: string | null;
    legacyDocId: number | null;
    title: string | null;
    isbnNormalized: string | null;
    languageCode: string | null;
    copyId: string | null;
    legacyInvId: number | null;
    inventoryNumber: string | null;
    readerId: string | null;
    legacyReaderId: string | null;
    readerName: string | null;
    institutionUnitCode: string | null;
    campusCodes: string[];
    servicePointCodes: string[];
    readerServicePointNames: string[];
  };
  details: Record<string, unknown>;
  flaggedAt: string;
}

export interface AppReviewIssueDetailResponse {
  issue: AppReviewQueueItem;
  document: CatalogDetailResponse | null;
  availability: CatalogAvailabilityResponse["items"];
  relatedIssues: AppReviewQueueItem[];
}

export interface PublicCatalogQuery {
  q?: string;
  title?: string;
  author?: string;
  isbn?: string;
  language?: string;
  institutionUnitCode?: string;
  campusCode?: string;
  servicePointCode?: string;
  availability?: "all" | "available" | "unavailable";
  minCopies?: number;
  page?: number;
  limit?: number;
}

export interface AppReviewQuery {
  entityType?: string;
  issueCode?: string;
  severity?: string;
  campusCode?: string;
  servicePointCode?: string;
  page?: number;
  limit?: number;
}

const api = axios.create({
  baseURL: "/api/v1",
});

export async function fetchPublicBooks(
  query: PublicCatalogQuery,
): Promise<PaginatedResponse<CatalogSearchItem>> {
  const { data } = await api.get<PaginatedResponse<CatalogSearchItem>>(
    "/catalog",
    {
      params: query,
    },
  );

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
): Promise<CatalogDetailResponse> {
  const { data } = await api.get<{ data: CatalogDetailResponse }>(
    `/catalog/${id}`,
  );
  return data.data;
}

export async function fetchPublicBookAvailability(
  id: string,
  query?: Pick<
    PublicCatalogQuery,
    "institutionUnitCode" | "campusCode" | "servicePointCode"
  >,
): Promise<CatalogAvailabilityResponse> {
  const { data } = await api.get<{ data: CatalogAvailabilityResponse }>(
    `/catalog/${id}/availability`,
    {
      params: query,
    },
  );
  return data.data;
}

export async function fetchCatalogFacets(): Promise<CatalogFacetsResponse> {
  const { data } = await api.get<{ data: CatalogFacetsResponse }>(
    "/catalog/facets",
  );
  return data.data;
}

export async function fetchLocationSummary(
  query?: Pick<
    PublicCatalogQuery,
    "institutionUnitCode" | "campusCode" | "servicePointCode"
  >,
): Promise<CatalogAvailabilityResponse["items"]> {
  const { data } = await api.get<{
    data: {
      items: CatalogAvailabilityResponse["items"];
    };
  }>("/catalog/locations/summary", {
    params: query,
  });

  return data.data.items ?? [];
}

export async function fetchAppReviewQueue(
  query: AppReviewQuery,
): Promise<PaginatedResponse<AppReviewQueueItem>> {
  const { data } = await api.get<PaginatedResponse<AppReviewQueueItem>>(
    "/migration/app-review/issues",
    {
      params: query,
    },
  );

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

export async function fetchAppReviewIssueDetail(
  flagId: string,
): Promise<AppReviewIssueDetailResponse> {
  const { data } = await api.get<{ data: AppReviewIssueDetailResponse }>(
    `/migration/app-review/issues/${flagId}`,
  );
  return data.data;
}
