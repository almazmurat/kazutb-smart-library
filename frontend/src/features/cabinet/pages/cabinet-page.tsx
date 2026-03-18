import { CabinetReservationsPage } from "./cabinet-reservations-page";
import { CabinetLoansPage } from "./cabinet-loans-page";

export function CabinetPage() {
  return (
    <div className="space-y-8">
      <CabinetReservationsPage />
      <CabinetLoansPage />
    </div>
  );
}
