import { ChangeEvent } from "react";

import {
  PublicCatalogFilters,
  PublicCatalogQuery,
} from "../api/public-catalog-api";

interface CatalogFiltersProps {
  labels: {
    search: string;
    author: string;
    category: string;
    branch: string;
    language: string;
    reset: string;
    allCategories: string;
    allBranches: string;
    allLanguages: string;
  };
  value: PublicCatalogQuery;
  filters?: PublicCatalogFilters;
  onChange: (next: PublicCatalogQuery) => void;
}

export function CatalogFilters({
  labels,
  value,
  filters,
  onChange,
}: CatalogFiltersProps) {
  const apply = (patch: Partial<PublicCatalogQuery>) => {
    onChange({
      ...value,
      ...patch,
      page: 1,
    });
  };

  const onTextChange =
    (key: "title" | "author") => (event: ChangeEvent<HTMLInputElement>) => {
      apply({ [key]: event.target.value || undefined });
    };

  const onSelectChange =
    (key: "categoryId" | "branchId" | "language") =>
    (event: ChangeEvent<HTMLSelectElement>) => {
      apply({ [key]: event.target.value || undefined });
    };

  // Provide default empty arrays when filters is not yet loaded
  const categories = filters?.categories ?? [];
  const branches = filters?.branches ?? [];
  const languages = filters?.languages ?? [];

  return (
    <section className="app-panel p-5 md:p-6">
      <div className="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p className="app-kicker">Search and Filters</p>
          <h2 className="mt-2 text-2xl font-semibold tracking-tight text-slate-900">
            {labels.search}
          </h2>
        </div>
        <button
          type="button"
          className="app-button-secondary self-start"
          onClick={() =>
            onChange({
              page: 1,
              limit: value.limit,
            })
          }
        >
          {labels.reset}
        </button>
      </div>

      <div className="mt-5 grid gap-4 md:grid-cols-2 lg:grid-cols-5">
        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.search}</span>
          <input
            type="text"
            value={value.title || ""}
            onChange={onTextChange("title")}
            className="rounded-xl border border-slate-200 bg-[rgba(248,250,252,0.72)] px-3.5 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.author}</span>
          <input
            type="text"
            value={value.author || ""}
            onChange={onTextChange("author")}
            className="rounded-xl border border-slate-200 bg-[rgba(248,250,252,0.72)] px-3.5 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.category}</span>
          <select
            value={value.categoryId || ""}
            onChange={onSelectChange("categoryId")}
            className="rounded-xl border border-slate-200 bg-[rgba(248,250,252,0.72)] px-3.5 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          >
            <option value="">{labels.allCategories}</option>
            {categories.map((category) => (
              <option key={category.id} value={category.id}>
                {category.name}
              </option>
            ))}
          </select>
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.branch}</span>
          <select
            value={value.branchId || ""}
            onChange={onSelectChange("branchId")}
            className="rounded-xl border border-slate-200 bg-[rgba(248,250,252,0.72)] px-3.5 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          >
            <option value="">{labels.allBranches}</option>
            {branches.map((branch) => (
              <option key={branch.id} value={branch.id}>
                {branch.name}
              </option>
            ))}
          </select>
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.language}</span>
          <select
            value={value.language || ""}
            onChange={onSelectChange("language")}
            className="rounded-xl border border-slate-200 bg-[rgba(248,250,252,0.72)] px-3.5 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          >
            <option value="">{labels.allLanguages}</option>
            {languages.map((language) => (
              <option key={language} value={language}>
                {language.toUpperCase()}
              </option>
            ))}
          </select>
        </label>
      </div>
    </section>
  );
}
