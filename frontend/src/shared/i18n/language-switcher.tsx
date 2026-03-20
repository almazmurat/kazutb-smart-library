import { localeLabels, supportedLocales } from "./config";
import { useI18n } from "./use-i18n";

export function LanguageSwitcher() {
  const { locale, setLocale, t } = useI18n();

  return (
    <div
      className="app-header-action app-lang-switcher"
      role="group"
      aria-label={t("language")}
    >
      <span className="app-lang-label">{t("language")}</span>
      <div className="app-lang-segmented">
        {supportedLocales.map((item) => {
          const isActive = locale === item;

          return (
            <button
              key={item}
              type="button"
              className={`app-lang-option ${isActive ? "app-lang-option-active" : ""}`}
              onClick={() => setLocale(item)}
              aria-pressed={isActive}
              title={localeLabels[item]}
            >
              {item.toUpperCase()}
            </button>
          );
        })}
      </div>
    </div>
  );
}
