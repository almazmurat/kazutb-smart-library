import { useEffect, useMemo, useState } from "react";
import { useSearchParams } from "react-router-dom";

import { CatalogFilters } from "../components/catalog-filters";
import { PublicBookCard } from "../components/public-book-card";
import { PublicCatalogQuery } from "../api/public-catalog-api";
import {
  usePublicCatalog,
  usePublicCatalogFilters,
} from "../hooks/use-public-catalog";
import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";

const PAGE_SIZE = 12;

function readQueryFromParams(params: URLSearchParams): PublicCatalogQuery {
  return {
    q: params.get("q") || undefined,
    title: params.get("title") || undefined,
    author: params.get("author") || undefined,
    isbn: params.get("isbn") || undefined,
    language: params.get("language") || undefined,
    campusCode: params.get("campusCode") || undefined,
    servicePointCode: params.get("servicePointCode") || undefined,
    availability:
      (params.get("availability") as PublicCatalogQuery["availability"]) ||
      undefined,
    page: Number(params.get("page") || "1"),
    limit: Number(params.get("limit") || String(PAGE_SIZE)),
  };
}

function toParams(query: PublicCatalogQuery) {
  const params = new URLSearchParams();
  Object.entries(query).forEach(([key, value]) => {
    if (value !== undefined && value !== "") {
      params.set(key, String(value));
    }
  });
  return params;
}

export function CatalogPage() {
  const { t } = useI18n();
  const [searchParams, setSearchParams] = useSearchParams();
  const serverQuery = readQueryFromParams(searchParams);

  const [draftQuery, setDraftQuery] = useState<PublicCatalogQuery>(serverQuery);
  const query = serverQuery;

  useEffect(() => {
    setDraftQuery(serverQuery);
  }, [searchParams]);

  const booksQuery = usePublicCatalog(query);
  const filtersQuery = usePublicCatalogFilters();

  const booksData = booksQuery.data?.data ?? [];
  const booksMeta = booksQuery.data?.meta;

  const totalPages = useMemo(
    () => booksMeta?.totalPages || 1,
    [booksMeta?.totalPages],
  );

  const currentPage = query.page || 1;

  const submitSearch = () => {
    setSearchParams(
      toParams({
        ...draftQuery,
        page: 1,
        limit: draftQuery.limit || PAGE_SIZE,
      }),
    );
  };

  const updateQuery = (next: PublicCatalogQuery) => {
    setDraftQuery(next);
  };

  const goToPage = (page: number) => {
    setSearchParams(
      toParams({
        ...query,
        page,
        limit: query.limit || PAGE_SIZE,
      }),
    );
  };

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("catalogInstitutionalLabel")}
        title={t("catalogPublicTitle")}
        description={t("catalogPublicDescription")}
        badges={[t("shellPublicLabel"), t("catalogFeatureInstitutional")]}
        actions={
          <div className="flex min-w-[220px] flex-col gap-1.5 px-3 py-2 text-left">
            <span className="app-kicker">Catalog Results</span>
            <span className="text-3xl font-semibold tracking-tight text-slate-950">
              {booksMeta?.total ?? 0}
            </span>
            <span className="text-sm text-slate-500">
              {t("catalogResults")}
            </span>
          </div>
        }
      />

      <CatalogFilters
        labels={{
          query: "Global query",
          title: t("catalogFilterTitle"),
          author: t("catalogFilterAuthor"),
          isbn: "ISBN",
          campus: "Campus",
          servicePoint: "Service point",
          language: t("catalogFilterLanguage"),
          availability: "Availability",
          allCampuses: "All campuses",
          allServicePoints: "All service points",
          allAvailability: "Any availability",
          reset: t("catalogFilterReset"),
          searchAction: "Search",
          allLanguages: t("catalogAllLanguages"),
        }}
        value={draftQuery}
        filters={filtersQuery.data}
        onSubmit={submitSearch}
        onChange={updateQuery}
      />

      {booksQuery.isLoading ? (
        <div className="app-empty-state text-sm text-slate-600">
          {t("catalogLoading")}
        </div>
      ) : null}

      {booksQuery.isError ? (
        <div className="app-state-error">{t("catalogError")}</div>
      ) : null}

      {!booksQuery.isLoading && !booksQuery.isError ? (
        <>
          {booksData.length === 0 ? (
            <div className="app-empty-state text-sm text-slate-600">
              {t("catalogEmpty")}
            </div>
          ) : (
            <div className="app-card-grid md:grid-cols-2 xl:grid-cols-3">
              {booksData.map((book) => (
                <PublicBookCard
                  key={book.id}
                  book={book}
                  labels={{
                    year: t("catalogCardYear"),
                    language: t("catalogCardLanguage"),
                    campus: "Campus",
                    available: t("catalogCardAvailable"),
                    totalCopies: t("catalogCardTotalCopies"),
                    isbn: "ISBN",
                    openDetails: t("catalogOpenDetails"),
                    reviewTag: "Needs review",
                  }}
                />
              ))}
            </div>
          )}

          <div className="app-toolbar">
            <p className="text-sm text-slate-600">
              {t("catalogResults")}: {booksMeta?.total ?? 0}
            </p>
            <div className="flex items-center gap-2">
              <button
                type="button"
                className="app-button-secondary px-3 py-1.5 disabled:opacity-50"
                disabled={currentPage <= 1}
                onClick={() => goToPage(Math.max(1, currentPage - 1))}
              >
                {t("catalogPrevPage")}
              </button>
              <span className="text-sm text-slate-700">
                {currentPage} / {totalPages}
              </span>
              <button
                type="button"
                className="app-button-secondary px-3 py-1.5 disabled:opacity-50"
                disabled={currentPage >= totalPages}
                onClick={() => goToPage(Math.min(totalPages, currentPage + 1))}
              >
                {t("catalogNextPage")}
              </button>
            </div>
          </div>
        </>
      ) : null}
    </section>
  );
}
