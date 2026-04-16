# ONBOARDING_NEXT_ENGINEER.md

## Быстрый старт для новых инженеров и Copilot Pro+

1. **Клонируйте репозиторий:**
   ```sh
git clone <repo-url>
cd kazutb-smart-library-main
```
2. **Ознакомьтесь с `README.md` и `/docs/`** — там описаны основные команды, структура и архитектура.
3. **Скопируйте `.env.example` → `.env`** и заполните секреты по инструкции в `/docs/`.
4. **Запустите bootstrap/dev-окружение:**
   ```sh
make dev up
# или
./scripts/bootstrap.sh
```
5. **Проверьте CI/CD:**
   - Все workflows в `.github/workflows/`.
   - Тесты и сборка должны проходить локально и на GitHub Actions.
6. **Изучите архитектуру и workflow:**
   - `/docs/ARCHITECTURE.md`, `/docs/qa/`, `/docs/design-exports/`
   - Vault с памятью и meta-знаниями: `/artifacts/obsidian/memory-fragments/`
   - Все conventions, примеры, edge cases — там же.
7. **Copilot/LLM-агенты:**
   - Для поиска знаний используйте `/docs/`, `/artifacts/obsidian/`, `GH_COPILOT_MEMORY.md` (если есть).
   - Все правила, workflow, onboarding — versioned и доступны.

---

## Строгий промпт (source-of-truth)

> "Для полного онбординга нового инженера или Copilot Pro+:
> - Все настройки, workflows, vault с памятью, архитектурные решения и инструкции versioned и лежат в репозитории.
> - После клонирования: читайте `README.md`, `/docs/`, копируйте `.env.example`, запускайте dev-окружение, проверяйте CI/CD, изучайте vault и meta-доки.
> - Любой агент или человек сразу видит все conventions, примеры, архитектуру и может работать без ручной передачи знаний."

---

**Если чего-то не хватает — дополни vault или `/docs/` и закоммить!**
