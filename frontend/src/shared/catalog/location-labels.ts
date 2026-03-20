import type { Locale } from "@shared/i18n/config";
import { getLocationLabel } from "@shared/i18n/domain-labels";

export function toReadableLocation(
  value?: string | null,
  locale: Locale = "ru",
): string {
  return getLocationLabel(locale, value);
}
