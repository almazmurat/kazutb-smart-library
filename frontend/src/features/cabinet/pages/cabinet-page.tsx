import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";
import { CabinetReservationsPage } from "./cabinet-reservations-page";
import { CabinetLoansPage } from "./cabinet-loans-page";

export function CabinetPage() {
  const { t } = useI18n();

  return (
    <div className="space-y-8">
      <PageIntro
        eyebrow={t("shellReaderSection")}
        title={t("cabinetPageTitle")}
        description={t("cabinetPageDescription")}
        badges={[t("shellSecureLabel")]}
      />
      <CabinetReservationsPage />
      <CabinetLoansPage />
    </div>
  );
}
