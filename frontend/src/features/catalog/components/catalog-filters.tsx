import { ChangeEvent } from "react";

import {
  CatalogFacetsResponse,
  PublicCatalogQuery,
} from "../api/public-catalog-api";

interface CatalogFiltersProps {
  labels: {
    query: string;
    title: string;
    author: string;
    isbn: string;
    campus: string;
    servicePoint: string;
    language: string;
    availability: string;
    allCampuses: string;
    allServicePoints: string;
    allAvailability: string;
    allLanguages: string;
    searchAction: string;
    reset: string;
  };
  value: PublicCatalogQuery;
  filters?: CatalogFacetsResponse;
  onSubmit: () => void;
  onChange: (next: PublicCatalogQuery) => void;
}

export function CatalogFilters({
  labels,
  value,
  filters,
  onSubmit,
  onChange,
}: CatalogFiltersProps) {
  const apply = (patch: Partial<PublicCatalogQuery>) => {
    onChange({
      ...value,
      ...patch,
    });
  };

  const onTextChange =
    (key: "q" | "title" | "author" | "isbn") =>
    (event: ChangeEvent<HTMLInputElement>) => {
      apply({ [key]: event.target.value || undefined });
    };

  const onSelectChange =
    (key: "campusCode" | "servicePointCode" | "language" | "availability") =>
    (event: ChangeEvent<HTMLSelectElement>) => {
      apply({ [key]: event.target.value || undefined });
    };

  const campuses = filters?.campuses ?? [];
  const servicePoints = filters?.servicePoints ?? [];
  const languages = filters?.languages ?? [];
  const availabilityValues = filters?.availability ?? [];

  return (
    <section className="app-panel-strong p-5 md:p-6">
      <div className="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p className="app-kicker">Search and Filters</p>
          <h2 className="mt-2 text-xl font-semibold tracking-tight text-slate-900 md:text-2xl">
            {labels.query}
          </h2>
        </div>
        <div className="flex items-center gap-2">
          <button
            type="button"
            className="app-button-primary self-start"
            onClick={onSubmit}
          >
            {labels.searchAction}
          </button>
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
      </div>

      <div className="app-filter-grid xl:grid-cols-4">
        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.query}</span>
          <input
            type="text"
            value={value.q || ""}
            onChange={onTextChange("q")}
            className="app-form-control"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.title}</span>
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
          <span className="font-medium">{labels.isbn}</span>
          <input
            type="text"
            value={value.isbn || ""}
            onChange={onTextChange("isbn")}
            className="app-form-control"
          />
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.campus}</span>
          <select
            value={value.campusCode || ""}
            onChange={onSelectChange("campusCode")}
            className="app-form-control"
          >
            <option value="">{labels.allCampuses}</option>
            {campuses.map((campus) => (
              <option key={campus.value} value={campus.value}>
                {campus.label}
              </option>
            ))}
          </select>
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.servicePoint}</span>
          <select
            value={value.servicePointCode || ""}
            onChange={onSelectChange("servicePointCode")}
            className="app-form-control"
          >
            <option value="">{labels.allServicePoints}</option>
            {servicePoints.map((servicePoint) => (
              <option key={servicePoint.value} value={servicePoint.value}>
                {servicePoint.label}
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
              <option key={language.value} value={language.value}>
                {language.label}
              </option>
            ))}
          </select>
        </label>

        <label className="flex flex-col gap-1 text-sm text-slate-700">
          <span className="font-medium">{labels.availability}</span>
          <select
            value={value.availability || ""}
            onChange={onSelectChange("availability")}
            className="app-form-control"
          >
            <option value="">{labels.allAvailability}</option>
            {availabilityValues.map((value) => (
              <option key={value.value} value={value.value}>
                {value.label}
              </option>
            ))}
          </select>
        </label>
      </div>
    </section>
  );
}
