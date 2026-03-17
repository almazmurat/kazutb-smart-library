import { createContext, PropsWithChildren, useMemo, useState } from "react";

import { defaultLocale, Locale, supportedLocales } from "./config";
import { dictionary, TranslationKey } from "./dictionary";

interface I18nContextValue {
  locale: Locale;
  setLocale: (next: Locale) => void;
  t: (key: TranslationKey) => string;
}

export const I18nContext = createContext<I18nContextValue>({
  locale: defaultLocale,
  setLocale: () => undefined,
  t: (key) => key,
});

export function I18nProvider({ children }: PropsWithChildren) {
  const saved = localStorage.getItem("kazutb_locale");
  const initialLocale =
    saved && (supportedLocales as readonly string[]).includes(saved)
      ? (saved as Locale)
      : defaultLocale;

  const [locale, setLocaleState] = useState<Locale>(initialLocale);

  const setLocale = (next: Locale) => {
    setLocaleState(next);
    localStorage.setItem("kazutb_locale", next);
  };

  const value = useMemo<I18nContextValue>(
    () => ({
      locale,
      setLocale,
      t: (key) => dictionary[locale][key],
    }),
    [locale],
  );

  return <I18nContext.Provider value={value}>{children}</I18nContext.Provider>;
}
