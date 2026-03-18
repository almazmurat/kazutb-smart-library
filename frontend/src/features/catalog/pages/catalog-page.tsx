import { useMemo, useState } from "react";

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

export function CatalogPage() {
  const { t } = useI18n();

  const [query, setQuery] = useState<PublicCatalogQuery>({
    page: 1,
    limit: PAGE_SIZE,
  });

  const booksQuery = usePublicCatalog(query);
  const filtersQuery = usePublicCatalogFilters();

  const booksData = booksQuery.data?.data ?? [];
  const booksMeta = booksQuery.data?.meta;

  const totalPages = useMemo(
    () => booksMeta?.totalPages || 1,
    [booksMeta?.totalPages],
  );

  const currentPage = query.page || 1;

  return (
    <section className="space-y-6">
      <PageIntro
        eyebrow={t("catalogInstitutionalLabel")}
        title={t("catalogPublicTitle")}
        description={t("catalogPublicDescription")}
        badges={[t("shellPublicLabel"), t("catalogFeatureInstitutional")]}
        actions={
          <div className="flex min-w-[220px] flex-col gap-2 px-3 py-2 text-left">
            <span className="app-kicker">Catalog Snapshot</span>
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
          search: t("catalogFilterTitle"),
          author: t("catalogFilterAuthor"),
          category: t("catalogFilterCategory"),
          branch: t("catalogFilterBranch"),
          language: t("catalogFilterLanguage"),
          reset: t("catalogFilterReset"),
          allCategories: t("catalogAllCategories"),
          allBranches: t("catalogAllBranches"),
          allLanguages: t("catalogAllLanguages"),
        }}
        value={query}
        filters={filtersQuery.data}
        onChange={setQuery}
      />

      {booksQuery.isLoading ? (
        <div className="app-empty-state text-sm text-slate-600">
          {t("catalogLoading")}
        </div>
      ) : null}

      {booksQuery.isError ? (
        <div className="app-empty-state border-red-200 text-sm text-red-700">
          {t("catalogError")}
        </div>
      ) : null}

      {!booksQuery.isLoading && !booksQuery.isError ? (
        <>
          {booksData.length === 0 ? (
            <div className="app-empty-state text-sm text-slate-600">
              {t("catalogEmpty")}
            </div>
          ) : (
            <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
              {booksData.map((book) => (
                <PublicBookCard
                  key={book.id}
                  book={book}
                  labels={{
                    year: t("catalogCardYear"),
                    language: t("catalogCardLanguage"),
                    branch: t("catalogCardBranch"),
                    available: t("catalogCardAvailable"),
                    totalCopies: t("catalogCardTotalCopies"),
                    openDetails: t("catalogOpenDetails"),
                  }}
                />
              ))}
            </div>
          )}

          <div className="app-panel flex items-center justify-between px-5 py-4">
            <p className="text-sm text-slate-600">
              {t("catalogResults")}: {booksMeta?.total ?? 0}
            </p>
            <div className="flex items-center gap-2">
              <button
                type="button"
                className="app-button-secondary px-3 py-1.5 disabled:opacity-50"
                disabled={currentPage <= 1}
                onClick={() =>
                  setQuery((prev) => ({
                    ...prev,
                    page: (prev.page || 1) - 1,
                  }))
                }
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
                onClick={() =>
                  setQuery((prev) => ({
                    ...prev,
                    page: (prev.page || 1) + 1,
                  }))
                }
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
