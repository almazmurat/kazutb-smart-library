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
    <section className="app-panel-strong p-5 md:p-6">
      <div className="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p className="app-kicker">Search and Filters</p>
          <h2 className="mt-2 text-xl font-semibold tracking-tight text-slate-900 md:text-2xl">
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

      <div className="app-filter-grid xl:grid-cols-5">
        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.search}</span>
          <input
            type="text"
            value={value.title || ""}
            onChange={onTextChange("title")}
            className="app-form-control"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.author}</span>
          <input
            type="text"
            value={value.author || ""}
            onChange={onTextChange("author")}
            className="app-form-control"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.category}</span>
          <select
            value={value.categoryId || ""}
            onChange={onSelectChange("categoryId")}
            className="app-form-control"
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
            className="app-form-control"
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
            className="app-form-control"
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
