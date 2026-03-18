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
  catalogInstitutionalLabel: string;
  catalogPublicTitle: string;
  catalogPublicDescription: string;
  catalogFilterTitle: string;
  catalogFilterAuthor: string;
  catalogFilterCategory: string;
  catalogFilterBranch: string;
  catalogFilterLanguage: string;
  catalogFilterReset: string;
  catalogAllCategories: string;
  catalogAllBranches: string;
  catalogAllLanguages: string;
  catalogLoading: string;
  catalogError: string;
  catalogEmpty: string;
  catalogCardYear: string;
  catalogCardLanguage: string;
  catalogCardBranch: string;
  catalogCardAvailable: string;
  catalogCardTotalCopies: string;
  catalogOpenDetails: string;
  catalogResults: string;
  catalogPrevPage: string;
  catalogNextPage: string;
  catalogBookNotFound: string;
  catalogBackToList: string;
  catalogMetadataTitle: string;
  catalogAvailabilityTitle: string;
  catalogDescriptionTitle: string;
  catalogDescriptionEmpty: string;
  catalogScopeLabel: string;
  catalogDigitalAccessNotice: string;
  reservationRequestButton: string;
  reservationSignInRequired: string;
  reservationSuccess: string;
  reservationError: string;
  reservationAlreadyPending: string;
  reservationStatusPending: string;
  reservationStatusReady: string;
  reservationStatusFulfilled: string;
  reservationStatusCancelled: string;
  reservationStatusExpired: string;
  cabinetReservationsTitle: string;
  cabinetReservationsEmpty: string;
  cabinetReservationColumn_Book: string;
  cabinetReservationColumn_Status: string;
  cabinetReservationColumn_Date: string;
  cabinetReservationColumn_ExpiresAt: string;
  cabinetReservationColumn_Actions: string;
  cabinetReservationCancel: string;
  librarianQueueTitle: string;
  librarianQueueEmpty: string;
  librarianQueueColumn_User: string;
  librarianQueueColumn_Book: string;
  librarianQueueColumn_Status: string;
  librarianQueueColumn_Date: string;
  librarianQueueColumn_Actions: string;
  librarianQueueConfirm: string;
  librarianQueueReject: string;
  librarianQueueReady: string;
};

export const dictionary: Record<Locale, TranslationDict> = {
  kk,
  ru,
  en,
};

export type TranslationKey = keyof TranslationDict;
