import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";

export function LoginPage() {
  const { t } = useI18n();

  return (
    <section className="space-y-4">
      <PageIntro
        eyebrow={t("shellSecureLabel")}
        title={t("loginTitle")}
        description={t("loginDescription")}
      />
      <div className="mt-6 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
        {t("loginScaffoldNote")}
      </div>
    </section>
  );
}
