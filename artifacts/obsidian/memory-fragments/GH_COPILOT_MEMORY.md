# GH_COPILOT_MEMORY.md

## Source-of-Truth для Copilot/LLM-агентов

- Все ключевые настройки, workflows, архитектурные решения, conventions и meta-знания versioned и доступны в репозитории.
- Для поиска знаний и контекста используйте:
  - `/docs/`
  - `/artifacts/obsidian/memory-fragments/`
  - `/docs/ONBOARDING_NEXT_ENGINEER.md`
  - `/docs/ARCHITECTURE.md`
  - `/docs/qa/`
- Любой новый инженер или агент должен:
  1. Клонировать репозиторий
  2. Ознакомиться с `README.md` и `/docs/`
  3. Скопировать `.env.example` → `.env` и заполнить секреты
  4. Запустить dev-окружение и проверить CI/CD
  5. Изучить vault и meta-доки
- Если workflow, conventions или память не видны — дополни vault или `/docs/` и закоммить.

---

## Пример строгого промпта для автоматизации онбординга

> "Onboard new advanced Pro+ users by pulling the repo, following `/docs/README.md` and `/artifacts/obsidian/`, and all settings/knowledge/workflows will be picked up automatically."

---

**Этот файл — якорь для Copilot/LLM-агентов.**
