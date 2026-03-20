import { Link, useParams } from "react-router-dom";

import {
  usePublicBookAvailability,
  usePublicBookDetails,
} from "@features/catalog/hooks/use-public-catalog";
import { useI18n } from "@shared/i18n/use-i18n";
import { BookCoverMock } from "@shared/ui/book-cover-mock";
import { PageIntro } from "@shared/ui/page-intro";
import { toReadableLocation } from "@shared/catalog/location-labels";

export function BookDetailsPage() {
  const { id = "" } = useParams();
  const { t } = useI18n();

  const bookQuery = usePublicBookDetails(id);
  const availabilityQuery = usePublicBookAvailability(id);

  if (bookQuery.isLoading || availabilityQuery.isLoading) {
    return (
      <section className="app-empty-state text-sm text-slate-600">
        {t("catalogLoading")}
      </section>
    );
  }

  if (bookQuery.isError || !bookQuery.data) {
    return (
      <section className="app-state-error">{t("catalogBookNotFound")}</section>
    );
  }

  const book = bookQuery.data;
  const availability = availabilityQuery.data?.items ?? [];
  const authorList = (book.authors as Array<Record<string, unknown>>)
    .map((author) => String(author?.fullName || author?.name || "").trim())
    .filter(Boolean)
    .join(", ");

  return (
    <section className="app-page">
      <div className="flex items-center justify-between gap-3">
        <Link
          to="/catalog"
          className="text-sm font-medium text-blue-700 hover:text-blue-800"
        >
          {t("catalogBackToList")}
        </Link>
      </div>

      <PageIntro
        eyebrow={t("catalogInstitutionalLabel")}
        title={book.title.display || book.title.raw || "Untitled"}
        description={book.title.subtitle || authorList}
        badges={[
          book.language.code || "N/A",
          `ISBN: ${book.isbn.normalized || book.isbn.raw || "N/A"}`,
          `${t("catalogCardYear")}: ${book.publication.year || "N/A"}`,
        ]}
      />

      <div className="grid gap-6 xl:grid-cols-[0.84fr_1.16fr]">
        <div className="space-y-6">
          <BookCoverMock
            title={book.title.display || book.title.raw || "Untitled"}
            subtitle={book.title.subtitle}
            accent={book.language.code?.toUpperCase() || "BOOK"}
          />

          <article className="app-panel-strong p-6">
            <h2 className="app-section-heading">
              {t("catalogAvailabilityTitle")}
            </h2>
            <dl className="mt-4 grid grid-cols-2 gap-3 text-sm text-slate-700">
              <div className="rounded-2xl bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogCardAvailable")}
                </dt>
                <dd className="mt-1 text-2xl font-semibold text-slate-950">
                  {availability.reduce(
                    (sum, row) => sum + row.copies.available,
                    0,
                  )}
                </dd>
              </div>
              <div className="rounded-2xl bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogCardTotalCopies")}
                </dt>
                <dd className="mt-1 text-2xl font-semibold text-slate-950">
                  {availability.reduce((sum, row) => sum + row.copies.total, 0)}
                </dd>
              </div>
            </dl>

            <div className="mt-5 rounded-[20px] border border-blue-100 bg-blue-50/80 px-4 py-3 text-sm leading-6 text-blue-950">
              Campus and service-point availability is shown below for College,
              University Economic, University Technological, and University
              Central.
            </div>
          </article>
        </div>

        <div className="space-y-6">
          <article className="app-panel p-6">
            <h2 className="app-section-heading">{t("catalogMetadataTitle")}</h2>
            <dl className="mt-5 grid gap-4 md:grid-cols-2 text-sm text-slate-700">
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogCardYear")}
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.publication.year || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogCardLanguage")}
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {(
                    book.language.code ||
                    book.language.raw ||
                    "-"
                  ).toUpperCase()}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  ISBN
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.isbn.normalized || book.isbn.raw || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  Publisher
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.publisher?.name || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  Authors
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {authorList || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  Metadata
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.controlNumber || "No control number"}
                </dd>
              </div>
            </dl>
          </article>

          <article className="app-panel p-6">
            <h2 className="app-section-heading">Availability by location</h2>
            <div className="mt-4 space-y-3">
              {availability.length === 0 ? (
                <p className="text-sm text-slate-600">No availability rows.</p>
              ) : (
                availability.map((row, index) => (
                  <div
                    key={`${row.campus?.code || "campus"}-${row.servicePoint?.code || index}`}
                    className="rounded-[18px] border border-slate-200 bg-slate-50 p-4"
                  >
                    <p className="text-sm font-semibold text-slate-900">
                      {toReadableLocation(
                        row.campus?.name || row.campus?.code || "Campus",
                      )}{" "}
                      •{" "}
                      {row.servicePoint?.name ||
                        row.servicePoint?.code ||
                        "Service point"}
                    </p>
                    <p className="mt-2 text-xs text-slate-600">
                      Available {row.copies.available} / {row.copies.total} •
                      Problem {row.copies.problem} • Review {row.copies.review}
                    </p>
                  </div>
                ))
              )}
            </div>
          </article>
        </div>
      </div>
    </section>
  );
}
