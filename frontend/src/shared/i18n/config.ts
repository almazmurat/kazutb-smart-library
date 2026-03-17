export const supportedLocales = ["kk", "ru", "en"] as const;

export type Locale = (typeof supportedLocales)[number];

export const defaultLocale: Locale = "ru";

export const localeLabels: Record<Locale, string> = {
  kk: "Қазақша",
  ru: "Русский",
  en: "English",
};
