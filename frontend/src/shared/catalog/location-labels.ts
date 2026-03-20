const locationMap: Record<string, string> = {
  COLLEGE_MAIN: "College",
  UNIVERSITY_ECONOMIC: "University Economic",
  UNIVERSITY_TECHNOLOGICAL: "University Technological",
  UNIVERSITY_CENTRAL: "University Central",
  ECONOMIC_LIBRARY: "University Economic",
  TECHNOLOGICAL_LIBRARY: "University Technological",
  COLLEGE_LIBRARY: "College",
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
