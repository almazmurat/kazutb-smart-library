import { Link } from "react-router-dom";

import { CatalogSearchItem } from "../api/public-catalog-api";
import { BookCoverMock } from "@shared/ui/book-cover-mock";
import { toReadableLocation } from "@shared/catalog/location-labels";

interface PublicBookCardProps {
  book: CatalogSearchItem;
  labels: {
    year: string;
    language: string;
    campus: string;
    available: string;
    totalCopies: string;
    isbn: string;
    openDetails: string;
    reviewTag: string;
  };
}

export function PublicBookCard({ book, labels }: PublicBookCardProps) {
  return (
    <article className="app-panel group flex h-full flex-col overflow-hidden p-4 transition duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-[0_22px_42px_rgba(15,23,42,0.1)] md:p-5">
      <BookCoverMock
        title={book.title.display || book.title.raw || "Untitled"}
        subtitle={book.title.subtitle}
        accent={book.language.code?.toUpperCase() || labels.language}
        compact
      />

      <div className="mt-5 flex flex-1 flex-col">
        <div className="flex flex-wrap items-center gap-2">
          {book.locations.campusNames.slice(0, 1).map((campusName) => (
            <span key={campusName} className="app-chip text-[11px]">
              {toReadableLocation(campusName)}
            </span>
          ))}
          <span className="app-chip-muted text-[11px]">
            {labels.available}: {book.copies.available}/{book.copies.total}
          </span>
          {book.review.documentNeedsReview ? (
            <span className="app-chip-muted border-amber-100 bg-amber-50 text-[11px] text-amber-800">
              {labels.reviewTag}
            </span>
          ) : null}
        </div>

        <h3 className="mt-4 text-lg font-semibold leading-tight text-slate-950 md:text-xl">
          {book.title.display || book.title.raw || "Untitled"}
        </h3>
        {book.title.subtitle ? (
          <p className="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
            {book.title.subtitle}
          </p>
        ) : null}

        <p className="mt-3 line-clamp-2 text-sm font-medium text-slate-700">
          {book.primaryAuthor || "-"}
        </p>

        <div className="mt-4 grid grid-cols-2 gap-2.5 text-xs text-slate-600">
          <div className="rounded-2xl border border-slate-200/70 bg-slate-50 px-3 py-2.5">
            <span className="block font-medium text-slate-500">
              {labels.year}
            </span>
            <span className="mt-1 block text-sm font-semibold text-slate-900">
              {book.publicationYear || "-"}
            </span>
          </div>
          <div className="rounded-2xl border border-slate-200/70 bg-slate-50 px-3 py-2.5">
            <span className="block font-medium text-slate-500">
              {labels.language}
            </span>
            <span className="mt-1 block text-sm font-semibold text-slate-900">
              {(book.language.code || book.language.raw || "-").toUpperCase()}
            </span>
          </div>
          <div className="col-span-2 rounded-2xl border border-slate-200/70 bg-slate-50 px-3 py-2.5">
            <span className="block font-medium text-slate-500">
              {labels.campus}
            </span>
            <span className="mt-1 block text-sm font-semibold text-slate-900">
              {book.locations.campusNames
                .slice(0, 2)
                .map((name) => toReadableLocation(name))
                .join(" • ") || "-"}
            </span>
          </div>
          <div className="col-span-2 rounded-2xl border border-slate-200/70 bg-slate-50 px-3 py-2.5">
            <span className="block font-medium text-slate-500">
              {labels.isbn}
            </span>
            <span className="mt-1 block text-sm font-semibold text-slate-900">
              {book.isbn.normalized || book.isbn.raw || "-"}
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
