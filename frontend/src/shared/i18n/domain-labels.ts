import type { Locale } from "./config";

type DomainMap = Record<string, string>;

const appReviewSeverityLabels: Record<Locale, DomainMap> = {
  ru: {
    CRITICAL: "Критический",
    HIGH: "Высокий",
    MEDIUM: "Средний",
    LOW: "Низкий",
  },
  kk: {
    CRITICAL: "Сындарлы",
    HIGH: "Жоғары",
    MEDIUM: "Орташа",
    LOW: "Төмен",
  },
  en: {
    CRITICAL: "Critical",
    HIGH: "High",
    MEDIUM: "Medium",
    LOW: "Low",
  },
};

const appReviewEntityTypeLabels: Record<Locale, DomainMap> = {
  ru: {
    document: "Документ",
    book_copy: "Экземпляр книги",
    reader: "Читатель",
    service_point: "Пункт обслуживания",
    storage_sigla: "Код хранения",
  },
  kk: {
    document: "Құжат",
    book_copy: "Кітап данасы",
    reader: "Оқырман",
    service_point: "Қызмет көрсету пункті",
    storage_sigla: "Сақтау коды",
  },
  en: {
    document: "Document",
    book_copy: "Book copy",
    reader: "Reader",
    service_point: "Service point",
    storage_sigla: "Storage code",
  },
};

const appReviewFlagStatusLabels: Record<Locale, DomainMap> = {
  ru: {
    OPEN: "Открыто",
    RESOLVED: "Решено",
    REJECTED: "Отклонено",
    COMPLETED: "Завершено",
    CANCELLED: "Отменено",
  },
  kk: {
    OPEN: "Ашық",
    RESOLVED: "Шешілді",
    REJECTED: "Қабылданбады",
    COMPLETED: "Аяқталды",
    CANCELLED: "Бас тартылды",
  },
  en: {
    OPEN: "Open",
    RESOLVED: "Resolved",
    REJECTED: "Rejected",
    COMPLETED: "Completed",
    CANCELLED: "Cancelled",
  },
};

const appReviewIssueCodeLabels: Record<Locale, DomainMap> = {
  ru: {
    missing_title: "Отсутствует название",
    missing_isbn: "Отсутствует ISBN",
    invalid_isbn: "Некорректный ISBN",
    invalid_language_code: "Некорректный код языка",
    missing_author_link: "Отсутствует связь с автором",
    orphan_copy_document: "Экземпляр не привязан к документу",
    missing_inventory_number: "Отсутствует инвентарный номер",
    missing_branch_mapping: "Отсутствует привязка к филиалу",
    location_mapping_conflict: "Конфликт привязки локации",
    location_mapping_requires_confirmation: "Требуется подтверждение локации",
    missing_reader_name: "Отсутствует имя читателя",
    missing_reader_email: "Отсутствует email читателя",
    reader_location_mapping_requires_confirmation:
      "Требуется подтверждение локации читателя",
  },
  kk: {
    missing_title: "Атауы жоқ",
    missing_isbn: "ISBN жоқ",
    invalid_isbn: "ISBN қате",
    invalid_language_code: "Тіл коды қате",
    missing_author_link: "Автор байланысы жоқ",
    orphan_copy_document: "Дана құжатқа байланыспаған",
    missing_inventory_number: "Инвентарлық нөмір жоқ",
    missing_branch_mapping: "Филиалға байланыс жоқ",
    location_mapping_conflict: "Локация байланысында қайшылық бар",
    location_mapping_requires_confirmation: "Локацияны растау қажет",
    missing_reader_name: "Оқырман аты жоқ",
    missing_reader_email: "Оқырман email-ы жоқ",
    reader_location_mapping_requires_confirmation:
      "Оқырман локациясын растау қажет",
  },
  en: {
    missing_title: "Missing title",
    missing_isbn: "Missing ISBN",
    invalid_isbn: "Invalid ISBN",
    invalid_language_code: "Invalid language code",
    missing_author_link: "Missing author link",
    orphan_copy_document: "Copy is not linked to a document",
    missing_inventory_number: "Missing inventory number",
    missing_branch_mapping: "Missing branch mapping",
    location_mapping_conflict: "Location mapping conflict",
    location_mapping_requires_confirmation:
      "Location mapping requires confirmation",
    missing_reader_name: "Missing reader name",
    missing_reader_email: "Missing reader email",
    reader_location_mapping_requires_confirmation:
      "Reader location mapping requires confirmation",
  },
};

const dataQualityIssueClassLabels: Record<Locale, DomainMap> = {
  ru: {
    IDENTITY: "Идентификация",
    REFERENTIAL: "Связность",
    SEMANTIC: "Семантика",
    FORMAT: "Формат",
    GOVERNANCE: "Управление",
    DERIVED: "Производные данные",
  },
  kk: {
    IDENTITY: "Сәйкестендіру",
    REFERENTIAL: "Байланыстылық",
    SEMANTIC: "Семантика",
    FORMAT: "Формат",
    GOVERNANCE: "Басқару",
    DERIVED: "Туынды деректер",
  },
  en: {
    IDENTITY: "Identity",
    REFERENTIAL: "Referential",
    SEMANTIC: "Semantic",
    FORMAT: "Format",
    GOVERNANCE: "Governance",
    DERIVED: "Derived",
  },
};

const dataQualityDetectionRuleLabels: Record<Locale, DomainMap> = {
  ru: {
    missing_title: "Отсутствует название",
    missing_author: "Отсутствует автор",
    missing_publication_year: "Отсутствует год издания",
    missing_language_code: "Отсутствует код языка",
    malformed_isbn: "Некорректный ISBN",
    incomplete_publication_metadata: "Неполные выходные данные",
    suspiciously_sparse_record: "Подозрительно неполная запись",
  },
  kk: {
    missing_title: "Атауы жоқ",
    missing_author: "Авторы жоқ",
    missing_publication_year: "Жарияланған жылы жоқ",
    missing_language_code: "Тіл коды жоқ",
    malformed_isbn: "ISBN қате",
    incomplete_publication_metadata: "Жарияланым деректері толық емес",
    suspiciously_sparse_record: "Жазба күмәнді түрде толық емес",
  },
  en: {
    missing_title: "Missing title",
    missing_author: "Missing author",
    missing_publication_year: "Missing publication year",
    missing_language_code: "Missing language code",
    malformed_isbn: "Malformed ISBN",
    incomplete_publication_metadata: "Incomplete publication metadata",
    suspiciously_sparse_record: "Suspiciously sparse record",
  },
};

const locationLabels: Record<Locale, DomainMap> = {
  ru: {
    COLLEGE_MAIN: "Колледж",
    UNIVERSITY_ECONOMIC: "Университет Экономический",
    UNIVERSITY_TECHNOLOGICAL: "Университет Технологический",
    UNIVERSITY_CENTRAL: "Университет Центральный",
    ECONOMIC_LIBRARY: "Университет Экономический",
    TECHNOLOGICAL_LIBRARY: "Университет Технологический",
    COLLEGE_LIBRARY: "Колледж",
  },
  kk: {
    COLLEGE_MAIN: "Колледж",
    UNIVERSITY_ECONOMIC: "Экономикалық университет",
    UNIVERSITY_TECHNOLOGICAL: "Технологиялық университет",
    UNIVERSITY_CENTRAL: "Орталық университет",
    ECONOMIC_LIBRARY: "Экономикалық университет",
    TECHNOLOGICAL_LIBRARY: "Технологиялық университет",
    COLLEGE_LIBRARY: "Колледж",
  },
  en: {
    COLLEGE_MAIN: "College",
    UNIVERSITY_ECONOMIC: "University Economic",
    UNIVERSITY_TECHNOLOGICAL: "University Technological",
    UNIVERSITY_CENTRAL: "University Central",
    ECONOMIC_LIBRARY: "University Economic",
    TECHNOLOGICAL_LIBRARY: "University Technological",
    COLLEGE_LIBRARY: "College",
  },
};

function humanizeCode(value: string): string {
  return value
    .replace(/_/g, " ")
    .toLowerCase()
    .replace(/\b\w/g, (char) => char.toUpperCase());
}

function getLabel(
  map: Record<Locale, DomainMap>,
  locale: Locale,
  value?: string | null,
) {
  if (!value) {
    return "-";
  }

  return map[locale][value] ?? humanizeCode(value);
}

export function getAppReviewSeverityLabel(
  locale: Locale,
  value?: string | null,
) {
  return getLabel(appReviewSeverityLabels, locale, value);
}

export function getAppReviewEntityTypeLabel(
  locale: Locale,
  value?: string | null,
) {
  return getLabel(appReviewEntityTypeLabels, locale, value);
}

export function getAppReviewFlagStatusLabel(
  locale: Locale,
  value?: string | null,
) {
  return getLabel(appReviewFlagStatusLabels, locale, value);
}

export function getAppReviewIssueCodeLabel(
  locale: Locale,
  value?: string | null,
) {
  return getLabel(appReviewIssueCodeLabels, locale, value);
}

export function getDataQualitySeverityLabel(
  locale: Locale,
  value?: string | null,
) {
  return getLabel(appReviewSeverityLabels, locale, value);
}

export function getDataQualityIssueClassLabel(
  locale: Locale,
  value?: string | null,
) {
  return getLabel(dataQualityIssueClassLabels, locale, value);
}

export function getDataQualityDetectionRuleLabel(
  locale: Locale,
  value?: string | null,
) {
  return getLabel(dataQualityDetectionRuleLabels, locale, value);
}

export function getLocationLabel(locale: Locale, value?: string | null) {
  return getLabel(locationLabels, locale, value);
}
