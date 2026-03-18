import { useMemo, useState } from "react";

import { CatalogFilters } from "../components/catalog-filters";
import { PublicBookCard } from "../components/public-book-card";
import { PublicCatalogQuery } from "../api/public-catalog-api";
import {
  usePublicCatalog,
  usePublicCatalogFilters,
} from "../hooks/use-public-catalog";
import { useI18n } from "@shared/i18n/use-i18n";

const PAGE_SIZE = 12;

export function CatalogPage() {
  const { t } = useI18n();

  const [query, setQuery] = useState<PublicCatalogQuery>({
    page: 1,
    limit: PAGE_SIZE,
  });

  const booksQuery = usePublicCatalog(query);
  const filtersQuery = usePublicCatalogFilters();

  const totalPages = useMemo(
    () => booksQuery.data?.meta.totalPages || 1,
    [booksQuery.data?.meta.totalPages],
  );

  const currentPage = query.page || 1;

  return (
    <section className="space-y-4">
      <header className="rounded-xl border border-blue-100 bg-white p-6 shadow-sm">
        <p className="text-xs uppercase tracking-[0.16em] text-blue-700">
          {t("catalogInstitutionalLabel")}
        </p>
        <h1 className="mt-2 text-3xl font-semibold text-slate-900">
          {t("catalogPublicTitle")}
        </h1>
        <p className="mt-2 max-w-3xl text-sm text-slate-600">
          {t("catalogPublicDescription")}
        </p>
      </header>

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
        <div className="rounded-xl border border-blue-100 bg-white p-8 text-center text-sm text-slate-600">
          {t("catalogLoading")}
        </div>
      ) : null}

      {booksQuery.isError ? (
        <div className="rounded-xl border border-red-200 bg-white p-8 text-center text-sm text-red-700">
          {t("catalogError")}
        </div>
      ) : null}

      {!booksQuery.isLoading && !booksQuery.isError && booksQuery.data ? (
        <>
          {booksQuery.data.data.length === 0 ? (
            <div className="rounded-xl border border-blue-100 bg-white p-8 text-center text-sm text-slate-600">
              {t("catalogEmpty")}
            </div>
          ) : (
            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
              {booksQuery.data.data.map((book) => (
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

          <div className="flex items-center justify-between rounded-xl border border-blue-100 bg-white px-4 py-3">
            <p className="text-sm text-slate-600">
              {t("catalogResults")}: {booksQuery.data.meta.total}
            </p>
            <div className="flex items-center gap-2">
              <button
                type="button"
                className="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-700 disabled:opacity-50"
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
                className="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm text-slate-700 disabled:opacity-50"
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
