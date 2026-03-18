import { Link } from "react-router-dom";

import { PublicCatalogBookItem } from "../api/public-catalog-api";

interface PublicBookCardProps {
  book: PublicCatalogBookItem;
  labels: {
    year: string;
    language: string;
    branch: string;
    available: string;
    totalCopies: string;
    openDetails: string;
  };
}

export function PublicBookCard({ book, labels }: PublicBookCardProps) {
  return (
    <article className="flex h-full flex-col rounded-xl border border-blue-100 bg-white p-4 shadow-sm transition hover:border-blue-300 hover:shadow-md">
      <h3 className="text-lg font-semibold text-slate-900">{book.title}</h3>
      {book.subtitle ? (
        <p className="mt-1 text-sm text-slate-600">{book.subtitle}</p>
      ) : null}

      <p className="mt-3 text-sm text-slate-700">
        {book.authors.map((author) => author.fullName).join(", ") || "-"}
      </p>

      <div className="mt-3 grid grid-cols-2 gap-2 text-xs text-slate-600">
        <div>
          <span className="font-medium text-slate-700">{labels.year}: </span>
          <span>{book.publishYear || "-"}</span>
        </div>
        <div>
          <span className="font-medium text-slate-700">
            {labels.language}:{" "}
          </span>
          <span>{book.language?.toUpperCase() || "-"}</span>
        </div>
        <div className="col-span-2">
          <span className="font-medium text-slate-700">{labels.branch}: </span>
          <span>{book.libraryBranch.name}</span>
        </div>
        <div>
          <span className="font-medium text-slate-700">
            {labels.available}:{" "}
          </span>
          <span>{book.availability.available}</span>
        </div>
        <div>
          <span className="font-medium text-slate-700">
            {labels.totalCopies}:{" "}
          </span>
          <span>{book.availability.total}</span>
        </div>
      </div>

      <div className="mt-4">
        <Link
          to={`/books/${book.id}`}
          className="inline-flex rounded-md bg-blue-700 px-3 py-2 text-sm font-medium text-white hover:bg-blue-800"
        >
          {labels.openDetails}
        </Link>
      </div>
    </article>
  );
}
