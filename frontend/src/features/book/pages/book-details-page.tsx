import { useState } from "react";
import { Link, useParams, useNavigate } from "react-router-dom";

import { usePublicBookDetails } from "@features/catalog/hooks/use-public-catalog";
import { useCreateReservation } from "@features/reservations/hooks/use-reservations";
import { authStore } from "@shared/auth/auth-store";
import { useI18n } from "@shared/i18n/use-i18n";
import { BookCoverMock } from "@shared/ui/book-cover-mock";
import { PageIntro } from "@shared/ui/page-intro";

export function BookDetailsPage() {
  const { id = "" } = useParams();
  const { t } = useI18n();
  const navigate = useNavigate();
  const [reservationMessage, setReservationMessage] = useState<{
    type: "success" | "error";
    text: string;
  } | null>(null);

  const bookQuery = usePublicBookDetails(id);
  const createReservationMutation = useCreateReservation();

  const handleReserveClick = async () => {
    try {
      setReservationMessage(null);
      await createReservationMutation.mutateAsync(id);
      setReservationMessage({
        type: "success",
        text: t("reservationSuccess"),
      });
      setTimeout(() => {
        navigate("/cabinet");
      }, 2000);
    } catch (error: any) {
      setReservationMessage({
        type: "error",
        text: error.response?.data?.message || t("reservationError"),
      });
    }
  };

  if (bookQuery.isLoading) {
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
  const isGuest = !authStore.isAuthenticated || authStore.role === "GUEST";
  const isShowingReservation =
    authStore.isAuthenticated &&
    (authStore.role === "STUDENT" || authStore.role === "TEACHER");

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
        title={book.title}
        description={
          book.subtitle ||
          book.authors.map((author) => author.fullName).join(", ")
        }
        badges={[
          book.libraryBranch.name,
          book.libraryBranch.scope.name,
          `${t("catalogCardAvailable")}: ${book.availability.available}/${book.availability.total}`,
        ]}
      />

      <div className="grid gap-6 xl:grid-cols-[0.84fr_1.16fr]">
        <div className="space-y-6">
          <BookCoverMock
            title={book.title}
            subtitle={book.subtitle}
            accent={book.language?.toUpperCase() || book.libraryBranch.name}
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
                  {book.availability.available}
                </dd>
              </div>
              <div className="rounded-2xl bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogCardTotalCopies")}
                </dt>
                <dd className="mt-1 text-2xl font-semibold text-slate-950">
                  {book.availability.total}
                </dd>
              </div>
            </dl>

            <div className="mt-5 rounded-[20px] border border-blue-100 bg-blue-50/80 px-4 py-3 text-sm leading-6 text-blue-950">
              {t("catalogDigitalAccessNotice")}
            </div>

            {isShowingReservation && (
              <div className="mt-5">
                <button
                  onClick={handleReserveClick}
                  disabled={
                    createReservationMutation.isPending ||
                    book.availability.available === 0
                  }
                  className="app-button-primary w-full disabled:opacity-50"
                >
                  {createReservationMutation.isPending
                    ? t("catalogLoading")
                    : t("reservationRequestButton")}
                </button>
              </div>
            )}

            {isGuest && (
              <div className="app-state-warning mt-4">
                {t("reservationSignInRequired")}
              </div>
            )}

            {reservationMessage && (
              <div
                className={`mt-4 rounded-[18px] px-4 py-3 text-sm ${
                  reservationMessage.type === "success"
                    ? "border border-green-100 bg-green-50 text-green-900"
                    : "border border-red-100 bg-red-50 text-red-900"
                }`}
              >
                {reservationMessage.text}
              </div>
            )}
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
                  {book.publishYear || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogCardLanguage")}
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.language?.toUpperCase() || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  ISBN
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.isbn || "-"}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogCardBranch")}
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.libraryBranch.name}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogScopeLabel")}
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.libraryBranch.scope.name}
                </dd>
              </div>
              <div className="rounded-[22px] bg-slate-50 px-4 py-3">
                <dt className="text-xs font-medium uppercase tracking-[0.12em] text-slate-500">
                  {t("catalogFilterCategory")}
                </dt>
                <dd className="mt-1 text-base font-semibold text-slate-950">
                  {book.categories
                    .map((category) => category.name)
                    .join(", ") || "-"}
                </dd>
              </div>
            </dl>
          </article>

          <article className="app-panel p-6">
            <h2 className="app-section-heading">
              {t("catalogDescriptionTitle")}
            </h2>
            <p className="mt-4 text-sm leading-7 text-slate-700">
              {book.description || t("catalogDescriptionEmpty")}
            </p>
          </article>
        </div>
      </div>
    </section>
  );
}
