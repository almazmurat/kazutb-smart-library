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
    <section className="rounded-xl border border-blue-100 bg-white p-4 shadow-sm">
      <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-5">
        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.search}</span>
          <input
            type="text"
            value={value.title || ""}
            onChange={onTextChange("title")}
            className="rounded-md border border-slate-300 bg-white px-3 py-2 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.author}</span>
          <input
            type="text"
            value={value.author || ""}
            onChange={onTextChange("author")}
            className="rounded-md border border-slate-300 bg-white px-3 py-2 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.category}</span>
          <select
            value={value.categoryId || ""}
            onChange={onSelectChange("categoryId")}
            className="rounded-md border border-slate-300 bg-white px-3 py-2 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
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
            className="rounded-md border border-slate-300 bg-white px-3 py-2 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
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
            className="rounded-md border border-slate-300 bg-white px-3 py-2 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
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

      <div className="mt-3 flex justify-end">
        <button
          type="button"
          className="rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
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
    </section>
  );
}
