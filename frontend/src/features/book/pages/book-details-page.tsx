import { useState } from "react";
import { Link, useParams, useNavigate } from "react-router-dom";

import { usePublicBookDetails } from "@features/catalog/hooks/use-public-catalog";
import { useCreateReservation } from "@features/reservations/hooks/use-reservations";
import { authStore } from "@shared/auth/auth-store";
import { useI18n } from "@shared/i18n/use-i18n";

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
      <section className="rounded-xl border border-blue-100 bg-white p-8 text-center text-sm text-slate-600">
        {t("catalogLoading")}
      </section>
    );
  }

  if (bookQuery.isError || !bookQuery.data) {
    return (
      <section className="rounded-xl border border-red-200 bg-white p-8 text-center text-sm text-red-700">
        {t("catalogBookNotFound")}
      </section>
    );
  }

  const book = bookQuery.data;
  const isGuest = !authStore.isAuthenticated || authStore.role === "GUEST";
  const isShowingReservation =
    authStore.isAuthenticated &&
    (authStore.role === "STUDENT" || authStore.role === "TEACHER");

  return (
    <section className="space-y-4">
      <div className="rounded-xl border border-blue-100 bg-white p-6 shadow-sm">
        <Link
          to="/catalog"
          className="text-sm text-blue-700 hover:text-blue-800"
        >
          {t("catalogBackToList")}
        </Link>

        <h1 className="mt-3 text-3xl font-semibold text-slate-900">
          {book.title}
        </h1>
        {book.subtitle ? (
          <p className="mt-2 text-lg text-slate-600">{book.subtitle}</p>
        ) : null}

        <p className="mt-4 text-sm text-slate-700">
          {book.authors.map((author) => author.fullName).join(", ")}
        </p>
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        <article className="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
          <h2 className="text-lg font-semibold text-slate-900">
            {t("catalogMetadataTitle")}
          </h2>
          <dl className="mt-3 space-y-2 text-sm text-slate-700">
            <div>
              <dt className="inline font-medium">{t("catalogCardYear")}: </dt>
              <dd className="inline">{book.publishYear || "-"}</dd>
            </div>
            <div>
              <dt className="inline font-medium">
                {t("catalogCardLanguage")}:{" "}
              </dt>
              <dd className="inline">{book.language?.toUpperCase() || "-"}</dd>
            </div>
            <div>
              <dt className="inline font-medium">ISBN: </dt>
              <dd className="inline">{book.isbn || "-"}</dd>
            </div>
            <div>
              <dt className="inline font-medium">{t("catalogCardBranch")}: </dt>
              <dd className="inline">{book.libraryBranch.name}</dd>
            </div>
            <div>
              <dt className="inline font-medium">{t("catalogScopeLabel")}: </dt>
              <dd className="inline">{book.libraryBranch.scope.name}</dd>
            </div>
            <div>
              <dt className="inline font-medium">
                {t("catalogFilterCategory")}:{" "}
              </dt>
              <dd className="inline">
                {book.categories.map((category) => category.name).join(", ") ||
                  "-"}
              </dd>
            </div>
          </dl>
        </article>

        <article className="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
          <h2 className="text-lg font-semibold text-slate-900">
            {t("catalogAvailabilityTitle")}
          </h2>
          <dl className="mt-3 space-y-2 text-sm text-slate-700">
            <div>
              <dt className="inline font-medium">
                {t("catalogCardAvailable")}:{" "}
              </dt>
              <dd className="inline">{book.availability.available}</dd>
            </div>
            <div>
              <dt className="inline font-medium">
                {t("catalogCardTotalCopies")}:{" "}
              </dt>
              <dd className="inline">{book.availability.total}</dd>
            </div>
          </dl>

          <div className="mt-4 rounded-md border border-blue-100 bg-blue-50 px-3 py-2 text-xs text-blue-900">
            {t("catalogDigitalAccessNotice")}
          </div>

          {isShowingReservation && (
            <div className="mt-4">
              <button
                onClick={handleReserveClick}
                disabled={
                  createReservationMutation.isPending ||
                  book.availability.available === 0
                }
                className="w-full rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 disabled:opacity-50"
              >
                {createReservationMutation.isPending
                  ? t("catalogLoading")
                  : t("reservationRequestButton")}
              </button>
            </div>
          )}

          {isGuest && (
            <div className="mt-4 rounded-md border border-amber-100 bg-amber-50 px-3 py-2 text-xs text-amber-900">
              {t("reservationSignInRequired")}
            </div>
          )}

          {reservationMessage && (
            <div
              className={`mt-4 rounded-md px-3 py-2 text-xs ${
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

      <article className="rounded-xl border border-blue-100 bg-white p-5 shadow-sm">
        <h2 className="text-lg font-semibold text-slate-900">
          {t("catalogDescriptionTitle")}
        </h2>
        <p className="mt-2 text-sm leading-6 text-slate-700">
          {book.description || t("catalogDescriptionEmpty")}
        </p>
      </article>
    </section>
  );
}
