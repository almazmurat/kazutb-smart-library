# Frontend i18n Guidelines

## Purpose

This document defines how UI text is organized for multilingual support in KazUTB Smart Library.

Supported locales:

- `kk`
- `ru`
- `en`

## Current Structure

- `frontend/src/shared/i18n/config.ts` — locale registry
- `frontend/src/shared/i18n/dictionary.ts` — dictionary mapping
- `frontend/src/shared/i18n/locales/*.ts` — locale texts
- `frontend/src/shared/i18n/i18n-context.tsx` — provider and language state
- `frontend/src/shared/i18n/language-switcher.tsx` — UI switch placeholder

## Style Requirement

All texts must follow formal institutional style:

- formal tone
- academic wording
- suitable for university library workflows

Avoid colloquial text and entertainment-style wording.

## How to Add New Texts

1. Add a key to all locale files under `frontend/src/shared/i18n/locales/`.
2. Use `useI18n().t('key')` in components.
3. Keep keys concise and domain-oriented.
4. Do not hardcode user-facing strings directly in feature components.
