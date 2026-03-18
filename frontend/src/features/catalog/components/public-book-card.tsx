import { Link } from "react-router-dom";

import { PublicCatalogBookItem } from "../api/public-catalog-api";
import { BookCoverMock } from "@shared/ui/book-cover-mock";

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
    <article className="app-panel group flex h-full flex-col overflow-hidden p-4 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-[0_22px_42px_rgba(15,23,42,0.1)] md:p-5">
      <BookCoverMock
        title={book.title}
        subtitle={book.subtitle}
        accent={book.language?.toUpperCase() || labels.language}
        compact
      />

      <div className="mt-5 flex flex-1 flex-col">
        <div className="flex flex-wrap items-center gap-2">
          <span className="app-chip text-[11px]">
            {book.libraryBranch.name}
          </span>
          <span className="app-chip-muted text-[11px]">
            {labels.available}: {book.availability.available}/
            {book.availability.total}
          </span>
        </div>

        <h3 className="mt-4 text-xl font-semibold leading-tight text-slate-950">
          {book.title}
        </h3>
        {book.subtitle ? (
          <p className="mt-2 text-sm leading-6 text-slate-600">
            {book.subtitle}
          </p>
        ) : null}

        <p className="mt-3 text-sm font-medium text-slate-700">
          {book.authors.map((author) => author.fullName).join(", ") || "-"}
        </p>

        <div className="mt-4 grid grid-cols-2 gap-3 text-xs text-slate-600">
          <div className="rounded-2xl bg-slate-50 px-3 py-2.5">
            <span className="block font-medium text-slate-500">
              {labels.year}
            </span>
            <span className="mt-1 block text-sm font-semibold text-slate-900">
              {book.publishYear || "-"}
            </span>
          </div>
          <div className="rounded-2xl bg-slate-50 px-3 py-2.5">
            <span className="block font-medium text-slate-500">
              {labels.language}
            </span>
            <span className="mt-1 block text-sm font-semibold text-slate-900">
              {book.language?.toUpperCase() || "-"}
            </span>
          </div>
          <div className="col-span-2 rounded-2xl bg-slate-50 px-3 py-2.5">
            <span className="block font-medium text-slate-500">
              {labels.branch}
            </span>
            <span className="mt-1 block text-sm font-semibold text-slate-900">
              {book.libraryBranch.name}
            </span>
          </div>
        </div>
      </div>

      <div className="mt-5">
        <Link to={`/books/${book.id}`} className="app-button-primary w-full">
          {labels.openDetails}
        </Link>
      </div>
    </article>
  );
}
