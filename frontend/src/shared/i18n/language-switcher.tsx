import { localeLabels, supportedLocales } from "./config";
import { useI18n } from "./use-i18n";

export function LanguageSwitcher() {
  const { locale, setLocale, t } = useI18n();

  return (
    <label className="flex items-center gap-2 text-xs text-slate-600">
      <span>{t("language")}</span>
      <select
        className="rounded-md border border-slate-300 bg-white px-2 py-1"
        value={locale}
        onChange={(event) => setLocale(event.target.value as typeof locale)}
      >
        {supportedLocales.map((item) => (
          <option key={item} value={item}>
            {localeLabels[item]}
          </option>
        ))}
      </select>
    </label>
  );
}
