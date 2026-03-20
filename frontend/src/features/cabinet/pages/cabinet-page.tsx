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
          <div className="grid gap-2 text-sm text-slate-600">
            <p>{t("cabinetReservationsDescription")}</p>
            <p>{t("cabinetLoansDescription")}</p>
          </div>
        }
      />
      <CabinetReservationsPage />
      <CabinetLoansPage />
    </div>
  );
}
