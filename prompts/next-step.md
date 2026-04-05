1. Executive summary
Следующий шаг — зафиксировать каноническую карту репозитория в одном документе и только после этого трогать структуру файлов.

Почему:

у тебя уже есть правильная модель 3 слоёв;
в репо уже есть truth-документы;
сейчас главный риск — не отсутствие понимания, а drift и двусмысленность;
если начать “чистить” без канонической карты, появится новый шум вместо уменьшения шума.
Значит сейчас нужен один артефакт:
docs/developer/REPO_NORMALIZATION_PLAN.md
или
project-context/07-repo-normalization-rules.md

Я бы рекомендовал docs/developer/REPO_NORMALIZATION_PLAN.md как operational doc, а не truth-файл.

2. Repository layer map
Ниже — строгая карта слоёв, как это должно пониматься.

Layer 1 — Policy / truth
Это единственный слой, который задаёт каноническую картину проекта.

Сюда относятся:

AGENT_START_HERE.md
project-context/00-project-truth.md
project-context/01-current-stage.md
project-context/02-active-roadmap.md
project-context/03-api-contracts.md
project-context/04-known-risks.md
project-context/05-agent-working-rules.md
project-context/06-current-focus.md
project-context/98-product-master-context.md
.github/copilot-instructions.md
Назначение
product truth
stage truth
scope truth
agent behavior truth
domain boundary truth
Правило
Если что-то спорит с этим слоем — этот слой побеждает.

Layer 2 — Execution / tooling
Это инструменты исполнения, а не истина проекта.

Сюда относятся:

prompts/
.github/prompts/
.github/instructions/
scripts/dev/
CI/workflow-файлы в .github/workflows/
возможно agents/ — если там именно execution helpers
Назначение
запуск задач,
шаблоны промптов,
локальные dev-процедуры,
session workflows,
automated checks.
Правило
Этот слой не должен переопределять truth.
Он должен опираться на truth.

Layer 3 — Reference / archive
Это история, rationale, вспомогательные заметки, архив и внешняя мыслительная среда.

Сюда относятся:

docs/
docs/archive/
Obsidian vault
старые phase docs
decision notes
review notes
historical snapshots
Назначение
объяснять, почему что-то было сделано,
хранить историю,
поддерживать размышление,
сохранять прошлые фазы без смешения с current truth.
Правило
Этот слой нельзя использовать как default source of execution truth.

Product/runtime code layer
Отдельно от документных слоёв:

app/
routes/
database/
resources/
config/
tests/
public/
Это реальность исполнения.
Если truth-док пишет одно, а код делает другое — значит truth надо обновить или пометить расхождение как debt.

3. Canonical file map
Каноника по major areas
Master project context
Canonical: project-context/98-product-master-context.md
Not canonical by default: 99-master-project-context.md — уже legacy reference по смыслу.

Current stage / focus
Canonical:

project-context/01-current-stage.md
project-context/06-current-focus.md
Current roadmap
Canonical: project-context/02-active-roadmap.md

API/product boundaries
Canonical: project-context/03-api-contracts.md

Risks
Canonical: project-context/04-known-risks.md

Agent execution rules
Canonical: project-context/05-agent-working-rules.md

Startup routing for agents
Canonical: AGENT_START_HERE.md

Copilot repository behavior
Canonical: .github/copilot-instructions.md

Prompt templates
Нужно выбрать один canonical prompt layer, а второй сделать subordinate:

либо prompts/ = repo-owned operational prompts
а .github/prompts/ = editor/Copilot-chat convenience mirrors
либо наоборот
Мой совет:
canonical prompt layer = prompts/
.github/prompts/ = thin entry points / convenience wrappers / IDE-facing layer.

Developer scripts
Canonical: scripts/dev/

Historical docs
Canonical archive location: docs/archive/

Knowledge vault boundary
Canonical boundary:
Obsidian = personal/team knowledge workspace,
но не source of truth.

4. Duplication/conflict analysis
Вот где сейчас реальные проблемы.

4.1 98-product-master-context.md vs 99-master-project-context.md
Это самый очевидный конфликт.

Уже по AGENT_START_HERE.md видно:

98 = canonical
99 = legacy reference
Решение
оставить 98 каноном;
99 либо переместить в archive/reference area,
либо явно переименовать как legacy:
project-context/archive/99-legacy-master-context.md
или docs/archive/context/99-legacy-master-context.md
Проблема сейчас: файл всё ещё лежит рядом с каноникой и продолжает выглядеть как равноправный.

4.2 README шумный и неканоничный
README.md сейчас:

частично про проект,
частично про Copilot workflow,
частично стандартный Laravel scaffold.
Это слабое место.

Решение
README должен стать:

что это за проект,
как его запускать,
где truth,
где developer workflow,
где docs.
А всё Laravel boilerplate — убрать.

4.3 Два prompt-слоя
Сейчас уже по структуре видно, что есть:

prompts/
.github/prompts/
Это допустимо, но только если роли жёстко разведены.

Риск
Оба слоя начнут содержать:

похожие task prompts,
похожие audit prompts,
похожие planning prompts,
и агент перестанет понимать, что первично.
Решение
Нужно в normalization plan зафиксировать:

Вариант A — рекомендуемый

prompts/ = canonical repo task prompts
.github/prompts/ = GitHub/Copilot UX adapters, короткие wrappers
То есть:

полные шаблоны живут в prompts/
IDE-facing shortcuts живут в .github/prompts/
4.4 Workflow docs overlap
Есть:

README workflow hints
docs/DEVELOPER_COPILOT_WORKFLOW.md
docs/developer/AI_WORKFLOW.md
.github/copilot-instructions.md
AGENT_START_HERE.md
Это уже многослойно.

Правильное разведение
AGENT_START_HERE.md = startup router
.github/copilot-instructions.md = repo behavior rules for Copilot
project-context/* = truth
docs/developer/AI_WORKFLOW.md = human-readable developer workflow
docs/DEVELOPER_COPILOT_WORKFLOW.md — либо merge, либо move to archive if superseded
4.5 Root-level phase artifacts / snapshots
Ты уже сам правильно указал:

root-level snapshots,
phase artifacts,
phase docs in root
Это почти всегда шум, если они не runtime-critical.

Правило
В корне должны оставаться только:

runtime files,
setup files,
top-level repo entrypoints,
truly active ops docs.
Всё фазовое:

либо docs/archive/
либо docs/developer/
либо scripts/dev/
либо удалить после подтверждения.
4.6 Obsidian boundary risk
Сейчас риск не в самом vault, а в том, что он может стать параллельной truth-системой.

Главный конфликт
Если в vault дублируются:

roadmap,
current stage,
API truth,
agent rules, то через пару недель начнётся расхождение.
Решение
Obsidian должен хранить:

notes,
decision logs,
work logs,
synthesis,
meeting notes,
investigation maps.
Но не:

canonical execution truth,
current roadmap authority,
source-of-truth API rules.
5. Default vs task-specific agent context
Default startup context
Это то, что агент должен читать почти всегда перед нетривиальной задачей:

AGENT_START_HERE.md
project-context/00-project-truth.md
project-context/01-current-stage.md
project-context/06-current-focus.md
project-context/03-api-contracts.md
project-context/04-known-risks.md
project-context/05-agent-working-rules.md
.github/copilot-instructions.md
Иногда default+:
project-context/02-active-roadmap.md
project-context/98-product-master-context.md
Я бы сказал так:

для обычной рабочей сессии: читать 00,01,03,04,05,06 + AGENT_START_HERE + copilot-instructions
98 читать для архитектурных, продуктовых, planning-задач
Task-specific context
Читать только по задаче:

prompts/* — когда нужен конкретный workflow
.github/prompts/* — when invoked from Copilot UX
docs/developer/* — для human workflow/process
docs/archive/* — только если нужен historical rationale
Obsidian vault — только как note/reference, не как truth
tests/*, app/*, routes/*, database/* — когда задача касается реального поведения системы
6. Obsidian boundary rules
Вот здесь нужна железная дисциплина.

Что Obsidian должен содержать
session notes
investigation notes
architecture sketches
decisions with links back to repo docs
meeting notes
personal synthesis
future ideas / parking lot
work logs
Что можно зеркалить в vault
Можно mirror:

ссылки на truth docs
ссылки на roadmap
ссылки на API contracts
summaries этих файлов
Но не сами файлы как alternate truth.

Что Obsidian не должен содержать как authority
Нельзя, чтобы vault был каноническим местом для:

current stage
active roadmap
API contract truth
agent rules
canonical startup instructions
Жёсткое правило
Если есть расхождение между Obsidian и repo:

repo wins
vault update required
7. Cleanup plan
Keep
Оставить как есть, но явно пометить канонику:

AGENT_START_HERE.md
project-context/00..06
project-context/98-product-master-context.md
.github/copilot-instructions.md
scripts/dev/
docs/archive/
runtime code directories
Merge
Скорее всего нужно объединить/свести:

workflow docs:

docs/DEVELOPER_COPILOT_WORKFLOW.md
docs/developer/AI_WORKFLOW.md
prompt layer description:

добавить один doc, который объясняет разницу между
prompts/
.github/prompts/
.github/instructions/
README:

переписать в нормальный project README
Archive
Нужно архивировать:

legacy master context (99...)
superseded phase docs
root-level phase artifacts
obsolete snapshots
old planning docs, если они уже не operational
Delete after confirmation
Кандидаты:

stale root snapshots
duplicate generated structure dumps
obsolete root phase files
prompt duplicates after canonicalization
Ignore for default startup
Агент по умолчанию не должен читать:

docs/archive/
vault
старые phase docs
legacy context files
generated snapshots
historical review notes
8. Extensibility plan for MCP/tools/agents
Сейчас об этом думать уже полезно, но не делать из этого новый шум.

MCP servers — позже, как execution enhancement
Подход правильный:

MCP не должен диктовать architecture truth
MCP должен расширять:
framework docs lookup
code intelligence
database inspection
test execution helpers
issue/PR context
Что реально полезно потом
Laravel / PHP docs MCP
PostgreSQL schema introspection MCP
OpenAPI contract inspection MCP
Test/runtime verification helper
Repo-context retriever по canonical files
Custom agents
Имеет смысл позже вводить специализированных агентов:

catalog-convergence-agent
runtime-verification-agent
stewardship-agent
integration-contract-agent
Но только после нормализации канонического контекста.

Frontend tooling
React shell уже есть в коммитах, но truth-слой всё ещё описывает UI как Blade-driven baseline.
Поэтому сначала надо зафиксировать:

что реально active,
что transitional,
что canonical public path, а уже потом расширять frontend tooling.
Test automation
Теперь, когда CI уже добавлен последним коммитом, следующий зрелый шаг:

привязать CI к canonical verification matrix
а не просто “запускать всё подряд”
То есть CI должен проверять именно то, что сейчас важно по 06-current-focus.md:

catalog path
book detail
account identity
reservation flows
internal circulation critical paths
Repo-aware context tools
Позже полезно добавить один инструментальный слой:

команда/скрипт, который выводит canonical startup context,
список “read this first”,
active vs archived docs.
Например:

scripts/dev/show-startup-context.sh
scripts/dev/context-audit.sh
Это уменьшит шанс, что агент или разработчик полезет в legacy.

9. Next best step
Один лучший следующий шаг: создать и утвердить docs/developer/REPO_NORMALIZATION_PLAN.md с 5 обязательными таблицами:

Layer map
path | layer | role | canonical? | default startup?

File disposition
path | keep | merge | archive | delete-after-confirmation | ignore-by-default

Canonical map
area | canonical file | legacy/duplicates | note

Prompt boundary map
prompts/ vs .github/prompts/ vs .github/instructions/

Obsidian boundary rules
allowed | mirror-only | forbidden-as-truth

После этого — отдельной cleanup-сессией:

move/archive legacy,
remove duplicates,
rewrite README,
add explicit doc about prompt layers.
