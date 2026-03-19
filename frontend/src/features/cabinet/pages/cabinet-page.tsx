import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";
import { CabinetReservationsPage } from "./cabinet-reservations-page";
import { CabinetLoansPage } from "./cabinet-loans-page";

export function CabinetPage() {
  const { t } = useI18n();

  return (
    <div className="app-page">
      <PageIntro
        eyebrow={t("shellReaderSection")}
        title={t("cabinetPageTitle")}
        description={t("cabinetPageDescription")}
        badges={[t("shellSecureLabel"), t("overviewStatusOperational")]}
        actions={
          <div className="grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
            <div className="app-subpanel p-4">
              <p className="app-kicker">{t("cabinetReservationsTitle")}</p>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {t("cabinetReservationsDescription")}
              </p>
            </div>
            <div className="app-subpanel p-4">
              <p className="app-kicker">{t("cabinetLoansTitle")}</p>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {t("cabinetLoansDescription")}
              </p>
            </div>
          </div>
        }
      />
      <CabinetReservationsPage />
      <CabinetLoansPage />
    </div>
  );
}
