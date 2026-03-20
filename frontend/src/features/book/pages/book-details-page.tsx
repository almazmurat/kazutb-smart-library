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
      <PageIntro
        eyebrow={t("catalogInstitutionalLabel")}
        title={book.title.display || book.title.raw || "Без названия"}
        description={book.title.subtitle || authorList}
        badges={[
          book.language.code || "Нет",
          `ISBN: ${book.isbn.normalized || book.isbn.raw || "Нет"}`,
          `${t("catalogCardYear")}: ${book.publication.year || "Нет"}`,
        ]}
        actions={
          <div className="grid gap-2 text-left">
            <Link to="/catalog" className="app-button-secondary justify-start">
              {t("catalogBackToList")}
            </Link>
            <div className="grid grid-cols-2 gap-2 text-sm">
              <div className="rounded-2xl bg-slate-50 px-3 py-2">
                <div className="text-xs uppercase tracking-[0.14em] text-slate-500">
                  Доступно
                </div>
                <div className="mt-1 font-semibold text-slate-950">
                  {availability.reduce(
                    (sum, row) => sum + row.copies.available,
                    0,
                  )}
                </div>
              </div>
              <div className="rounded-2xl bg-slate-50 px-3 py-2">
                <div className="text-xs uppercase tracking-[0.14em] text-slate-500">
                  Экземпляров
                </div>
                <div className="mt-1 font-semibold text-slate-950">
                  {availability.reduce((sum, row) => sum + row.copies.total, 0)}
                </div>
              </div>
            </div>
          </div>
        }
      />

      <div className="grid gap-6 xl:grid-cols-[0.78fr_1.22fr]">
        <article className="app-panel p-5">
          <BookCoverMock
            title={book.title.display || book.title.raw || "Без названия"}
            subtitle={book.title.subtitle}
            accent={book.language.code?.toUpperCase() || "BOOK"}
          />

          <dl className="mt-4 grid grid-cols-2 gap-2 text-sm text-slate-700">
            <div className="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
              <dt className="text-xs text-slate-500">{t("catalogCardAvailable")}</dt>
              <dd className="mt-1 text-xl font-semibold text-slate-900">
                {availability.reduce((sum, row) => sum + row.copies.available, 0)}
              </dd>
            </div>
            <div className="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
              <dt className="text-xs text-slate-500">{t("catalogCardTotalCopies")}</dt>
              <dd className="mt-1 text-xl font-semibold text-slate-900">
                {availability.reduce((sum, row) => sum + row.copies.total, 0)}
              </dd>
            </div>
          </dl>
        </article>

        <div className="space-y-5">
          <article className="app-panel p-5">
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
                  Издатель
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.publisher?.name || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  Авторы
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {authorList || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  Служебный номер
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.controlNumber || "Не указан"}
                </dd>
              </div>
            </dl>
          </article>

          <article className="app-panel p-5">
            <h2 className="app-section-heading">Наличие экземпляров</h2>
            <div className="mt-4 space-y-3">
              {availability.length === 0 ? (
                <p className="text-sm text-slate-600">
                  Данные о наличии отсутствуют.
                </p>
              ) : (
                availability.map((row, index) => (
                  <div
                    key={`${row.campus?.code || "campus"}-${row.servicePoint?.code || index}`}
                    className="app-feature-card p-4"
                  >
                    <p className="text-sm font-semibold text-slate-900">
                      {toReadableLocation(
                        row.campus?.name || row.campus?.code || "Кампус",
                      )}{" "}
                      •{" "}
                      {row.servicePoint?.name ||
                        row.servicePoint?.code ||
                        "Пункт обслуживания"}
                    </p>
                    <p className="mt-2 text-xs text-slate-600">
                      Доступно {row.copies.available} / {row.copies.total} •
                      Проблемы {row.copies.problem} • На проверке{" "}
                      {row.copies.review}
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
