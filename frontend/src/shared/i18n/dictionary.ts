import { en } from "./locales/en";
import { ru } from "./locales/ru";
import { kk } from "./locales/kk";

import { Locale } from "./config";

export type TranslationDict = {
  appTitle: string;
  navCatalog: string;
  navSearch: string;
  navCabinet: string;
  navLibrarian: string;
  navCatalogBooksMgmt: string;
  navCatalogAuthorsMgmt: string;
  navCatalogCategoriesMgmt: string;
  navCatalogCopiesMgmt: string;
  navAdmin: string;
  navAnalytics: string;
  navReports: string;
  login: string;
  language: string;
  catalogAuthorsTitle: string;
  catalogAuthorsDescription: string;
  catalogCategoriesTitle: string;
  catalogCategoriesDescription: string;
  catalogBooksTitle: string;
  catalogBooksDescription: string;
  catalogCopiesTitle: string;
  catalogCopiesDescription: string;
  catalogScaffoldNote: string;
};

export const dictionary: Record<Locale, TranslationDict> = {
  kk,
  ru,
  en,
};

export type TranslationKey = keyof TranslationDict;
