const locationMap: Record<string, string> = {
  COLLEGE_MAIN: "Колледж",
  UNIVERSITY_ECONOMIC: "Университет Экономический",
  UNIVERSITY_TECHNOLOGICAL: "Университет Технологический",
  UNIVERSITY_CENTRAL: "Университет Центральный",
  ECONOMIC_LIBRARY: "Университет Экономический",
  TECHNOLOGICAL_LIBRARY: "Университет Технологический",
  COLLEGE_LIBRARY: "Колледж",
};

export function toReadableLocation(value?: string | null): string {
  if (!value) {
    return "-";
  }

  if (locationMap[value]) {
    return locationMap[value];
  }

  return value
    .replace(/_/g, " ")
    .toLowerCase()
    .replace(/\b\w/g, (char) => char.toUpperCase());
}
