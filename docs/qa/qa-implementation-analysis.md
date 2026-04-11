# QA Implementation & Empirical Analysis Package

## Short direct answer
This repository is already a **web-based digital library platform** with a working QA baseline. The package below reframes the live repo as a professional QA implementation and empirical analysis set using the project’s real tests, CI/CD workflow, metrics, and evidence artifacts. The polished primary report now lives at `docs/qa/final-qa-report.md`, with `docs/qa/technical-qa-report.md` retained as a supporting analytical companion.

---

## Assumptions
- The deliverable should be **repo-specific**, not a generic textbook plan.
- A professional review can defend **risk-based automation** instead of pretending full-monolith coverage.
- Evidence should come from **real repository logs, GitHub Actions artifacts, Playwright traces, PHPUnit/JUnit XML, and Clover coverage**.
- The strongest deliverable is a **reproducible engineering package**: tests + CI + metrics + documentation + evidence.

---

## Part A — System analysis for automation

### System type
**Type:** web application / digital library platform  
**Stack:** Laravel 13 + Blade + React/Vite + PostgreSQL + Docker Compose + GitHub Actions

### Main modules and high-risk analysis

| Module / Feature | High-Risk Function | Risk Level | Test Priority | Why Risky | Expected Outcome |
|---|---|---:|---:|---|---|
| Authentication & session boundary | login, logout, session integrity, CRM-bound auth handoff | High | High | incorrect auth can expose protected reader/staff data | only valid users gain access; no leakage on failures |
| Reader account & reservations | `/account`, loans, renewals, reservations | High | High | directly affects real reader state and trust | authenticated readers see correct state; guests are blocked |
| Internal staff boundary | `/internal/*`, circulation, review, stewardship | High | High | improper role handling exposes staff tools to readers/guests | only librarian/admin roles are allowed |
| Catalog discovery & book detail | `/catalog`, `/api/v1/catalog-db`, `/api/v1/book-db/{isbn}` | High | High | this is the core public library journey | search, filters, sort, ISBN and detail routes remain stable |
| Integration boundary | `/api/integration/v1/*` reservations/documents endpoints | High | Medium-High | incorrect boundary behavior breaks external workflows and auditability | token checks, idempotency, and safe errors remain stable |
| Public shell & homepage | homepage, resources, contacts, login redirect behavior | Medium | Medium | visible regressions damage confidence immediately | public shell stays usable and localized |
| External resource and digital materials access | licensed resources, streaming access | Medium | Medium | access and entitlement issues affect research workflows | correct visibility and safe access behavior |

### Top 5 critical modules with highest ROI
1. **Authentication & session boundary** — highest security and trust impact.
2. **Reader account & reservations** — core user-value flow, directly visible to readers.
3. **Internal staff boundary** — role-based access is mission-critical.
4. **Catalog discovery & book detail** — the main public value proposition of the library.
5. **Integration boundary** — protects future interoperability and reproducibility claims.

---

## Part B — Automation tool selection

### Tool selection
- **UI automation:** `Playwright`
- **API / backend automation:** `PHPUnit` via Laravel feature/API tests
- **Test reporting:** JUnit XML, Clover XML, Playwright HTML report
- **CI/CD:** GitHub Actions (`.github/workflows/ci.yml`)
- **Coverage / metrics:** Clover, PHPUnit counts, execution logs, JSON evidence
- **Logging / artifacts:** `build/test-results/`, `playwright-report/`, `test-results/playwright-artifacts/`, `evidence/verification/`
- **Notifications / alerting:** GitHub Actions status checks and PR blocking

### Rationale
| Tool | Why it fits this repo |
|---|---|
| Playwright | real-browser coverage for public reader flows with traces, screenshots, and CI-friendly execution |
| PHPUnit | native Laravel test runner already used across the repo; low friction and high maintainability |
| GitHub Actions | the repository already lives on GitHub and needs visible PR/push gates |
| Clover + JUnit | accepted, portable formats for research evidence and CI artifact publishing |
| Docker fallback | required because contributor environments may not have PHP 8.4+ |

### Alternatives considered
| Alternative | Why not chosen as primary |
|---|---|
| Cypress | good UI tooling, but Playwright provides better trace support and cleaner CI ergonomics here |
| Postman/Newman | useful for external API collections, but current repo-native feature/API coverage is already stronger in PHPUnit |
| Jenkins / GitLab CI | unnecessary operational overhead because GitHub Actions already satisfies the delivery model |
| Pest | viable for Laravel, but the existing repo baseline is already well-structured on PHPUnit |

### Final recommendation
Keep **Playwright + PHPUnit + GitHub Actions** as the defended automation baseline and treat external additions as optional enhancements rather than mandatory rewrites.

---

## Part C — Automated test scope

| Module / Feature | High-Risk Function | Test Priority | Automation Type | Notes / Expected Outcome |
|---|---|---|---|---|
| Auth | login success/failure, rate limiting, logout, session lookup | High | API / Integration | validate both positive and negative paths |
| Reader account | summary, loans, reservations, redirects | High | Feature / API / E2E | protect personal state and guest boundary |
| Internal staff tools | access control for dashboard/review/stewardship/circulation | High | Feature / API | reject guest/reader sessions, allow staff |
| Catalog | search, filters, sort, empty-result behavior, detail lookup | High | Feature / API | core discovery flow must remain stable |
| Public shell | homepage, catalog shell, resources, language surface | Medium | E2E / Feature | visible smoke coverage for first impression |
| Integration APIs | reservation approve/reject and document management boundary | Medium-High | API / Integration | idempotency, token rules, safe errors |

---

## Part D — Detailed automated test cases

| Test Case ID | Module | Description | Precondition | Input Data | Expected Result | Scenario Type | Automation Level |
|---|---|---|---|---|---|---|---|
| `TC-AUTH-01` | Auth | valid login returns sanitized user context | auth API reachable | valid credentials | `200 OK`, no token leakage in payload | Positive | API |
| `TC-AUTH-02` | Auth | invalid login does not leak CRM detail | auth API stubbed | wrong password | controlled error message, safe logging | Negative | API |
| `TC-AUTH-03` | Auth | blank credentials are rejected | none | empty email/login/password | `422` with validation errors | Negative | API |
| `TC-ACC-01` | Reader account | guest visiting `/account` is redirected to `/login` | no session | guest user | login redirect is shown | Negative | E2E / Feature |
| `TC-ACC-02` | Reservations | authenticated user reads reservation data | reader session exists | reader session | reservation payload returns expected shape | Positive | API |
| `TC-CAT-01` | Catalog | catalog search returns paginated results | PostgreSQL available | keyword query | `200 OK`, populated `data[]` and `meta` | Positive | API |
| `TC-CAT-02` | Catalog | no-result query returns empty payload | PostgreSQL available | impossible query string | empty `data[]`, `meta.total = 0` | Negative | API |
| `TC-INT-01` | Internal boundary | guest cannot open `/internal/dashboard` | no session | guest request | `403 Forbidden` | Negative | Feature |
| `TC-INT-02` | Internal boundary | librarian can open internal pages | librarian session | staff session | `200 OK` for protected staff views | Positive | Feature |
| `TC-E2E-01` | Public shell | homepage shows KazTBU search-first hero | public site reachable | `/?lang=en` | hero heading and search bar visible | Positive | UI / E2E |
| `TC-E2E-02` | Public shell | catalog keeps critical filters visible | public site reachable | `/catalog` | language chips, sort, availability visible | Positive | UI / E2E |
| `TC-API-INT-01` | Integration boundary | invalid token/idempotency handling stays safe | integration middleware active | bad token / malformed org context | request rejected with stable reason code | Negative | Integration |
| `TC-AUTH-04` | Auth | CRM success without a bearer token must fail closed | auth stubbed without token | valid-looking user body, missing token | `502` and no authenticated session is created | Negative | API |
| `TC-INT-03` | Internal boundary | authenticated sessions without staff role stay blocked | session exists but role is blank | `role = ''` | `403 Forbidden` on core `/internal/*` routes | Negative | Feature |
| `TC-DOC-03` | Document management | invalid document identifiers and empty mutations are rejected | integration middleware active | invalid UUID / empty PATCH body | `400` with stable `reason_code` | Negative | Integration |
| `TC-CONC-01` | Reservation mutation | idempotent replay preserves traceability fields | replay-safe service contract active | repeated approve/reject with same idempotency key | `200` with original `request_id` / `correlation_id` | Edge / Concurrency | Integration |

---

## Part E — Script design and implementation plan

### Practical folder structure
```text
/tests
  /Feature
    /Api
  /e2e
/scripts/dev
/docs/qa
/evidence/verification
/build/test-results
```

### Reusable automation architecture
- **Feature/API layer:** Laravel HTTP tests under `tests/Feature/` and `tests/Feature/Api/`
- **Browser smoke layer:** Playwright specs under `tests/e2e/`
- **Execution wrappers:** `scripts/dev/run-ci-gates.sh`, `scripts/dev/run-verification-evidence.sh`
- **Reports:** JUnit/Clover in `build/test-results/`, Playwright HTML + traces in configured output directories
- **Evidence index:** `docs/qa/evidence-index.md`

### Implemented script inventory

| Script ID | Module / Feature | Framework | File / Location | Status | Reusability Notes |
|---|---|---|---|---|---|
| `SCR-AUTH-01` | auth boundary | PHPUnit | `tests/Feature/Api/AuthHardeningTest.php` | Implemented | reusable auth failure and validation checks |
| `SCR-ACC-01` | reader account | PHPUnit | `tests/Feature/Api/AccountReservationsTest.php` | Implemented | covers summary + reservation flows |
| `SCR-BOUND-01` | internal access | PHPUnit | `tests/Feature/InternalAccessBoundaryTest.php` | Implemented | stable role-based boundary assertions |
| `SCR-CAT-01` | catalog API | PHPUnit | `tests/Feature/Api/CatalogDbSearchTest.php` | Implemented | reusable for filters, sort, empty payload |
| `SCR-BOOK-01` | book detail | PHPUnit | `tests/Feature/Api/BookDetailDbTest.php` | Implemented | detail lookup regression coverage |
| `SCR-E2E-01` | public shell smoke | Playwright | `tests/e2e/public-smoke.spec.ts` | Implemented | browser-level smoke pack for core routes |
| `SCR-CI-01` | QA entry point | Bash | `scripts/dev/run-ci-gates.sh` | Implemented | one-command local gate |
| `SCR-EVID-01` | evidence generation | Bash | `scripts/dev/run-verification-evidence.sh` | Implemented | reproducible logs and traceability output |

### Maintainability rules
- prefer **one assertion cluster per risk scenario**
- keep selectors anchored to stable text or explicit attributes
- treat Playwright smoke tests as **thin critical-path checks**, not UI over-automation
- parameterize language or role cases where useful instead of cloning files

---

## Part F — Version control tracking

### Branching strategy
- `main` = protected verified branch
- feature branches recommended for larger QA hardening tasks (`feat/qa-*`, `fix/qa-*`, `docs/qa-*`)
- keep evidence/doc updates separated from application behavior changes when possible

### Commit naming convention
- `test: add auth/session regression coverage`
- `fix: stabilize Playwright output permissions`
- `docs: add QA implementation analysis`
- `ci: harden verification workflow`

### Example traceable history

| Commit ID | Date | Module / Feature | Description of Changes | Why Important |
|---|---|---|---|---|
| `81344dc` | 2026-04-08 | CI stability | stabilized CI when Vite manifest was missing | fixed clean-runner regressions |
| `734f2d9` | 2026-04-08 | QA gate | installed frontend deps in QA gate | made local/CI execution reproducible |
| `cea7d76` | 2026-04-08 | backend verification | stabilized backend CI workflow | improved coverage artifact reliability |
| `afa1598` | 2026-04-08 | coverage parser | fixed Clover threshold compatibility | made metrics trustworthy |
| `3e8ff3d` | 2026-04-08 | QA evidence/docs | completed the earlier QA audit package | added documentation traceability |

---

## Part G — Evidence for the report

### Evidence structure
```text
evidence/verification/
  qa-gates-*.txt
  playwright-smoke-*.txt
  ci-traceability-*.txt
  remote-ci-summary-*.txt
  quality-metrics.json
build/test-results/
  phpunit-feature.xml
  clover.xml
```

### Evidence table

| Evidence ID | Module / Feature | Type | Description | File Location | Why It Matters |
|---|---|---|---|---|---|
| `E1` | local QA gate | Log | fresh full gate output from `composer qa:ci` | `evidence/verification/qa-gates-20260411-010532.txt` | proves backend/style/build checks really ran |
| `E2` | browser smoke | Log | fresh Playwright run | `evidence/verification/playwright-smoke-20260411-010532.txt` | proves UI automation executed successfully |
| `E3` | traceability | Log | git history around QA/CI/doc files | `evidence/verification/ci-traceability-20260411-010532.txt` | shows evolution of automation work |
| `E4` | remote CI | Log | recent GitHub Actions summary | `evidence/verification/remote-ci-summary-20260411-010532.txt` | proves CI/CD integration exists remotely |
| `E5` | structured test report | XML | JUnit export for backend verification | `build/test-results/phpunit-feature.xml` | suitable for reproducible reporting |
| `E6` | coverage report | XML | Clover line-coverage artifact | `build/test-results/clover.xml` | supports metrics and threshold checks |
| `E7` | Playwright artifacts | HTML / trace / screenshot | report, trace, video on failures | configured output folder from Playwright | supports reproducibility and debugging |

---

## Part H — Quality gates definition

| Quality Gate ID | Metric / Criterion | Threshold / Requirement | Why this threshold | Blocker? |
|---|---|---|---|---|
| `QG-01` | Style verification | `0` Pint violations on defended files | style noise should not enter verified scope | Yes |
| `QG-02` | Critical-path backend pass rate | `100%` pass | P1 flows are not optional | Yes |
| `QG-03` | Browser smoke pass rate | `100%` pass | each smoke test maps to a visible user journey | Yes |
| `QG-04` | Coverage artifact floor | Clover line coverage `>= 4.0%` | catches empty/regressed coverage artifacts while honest about scoped suite | Yes |
| `QG-05` | Critical defects in defended scope | `0` open critical defects | critical regressions must block release confidence | Yes |
| `QG-06` | Flaky test tolerance | `<= 2%` repeated instability across recent runs | keeps trust in automation high | Yes |
| `QG-07` | Pipeline stability | green PR / main verification run required | protects merge quality | Yes |

### Why not force 80% global code coverage?
Because this repo currently uses a **risk-based defended suite**, not full-monolith line coverage. The honest metric is:
- **critical-scenario coverage is high for P1 flows**, while
- **global Clover coverage is still modest** because it is measured against the full `app/` namespace.

---

## Part I — CI/CD integration

### Pipeline steps

| Pipeline Step | Description | Tool / Framework | Trigger | Output Artifact |
|---|---|---|---|---|
| `secret-scan` | scan for committed secrets | Gitleaks | push / PR / manual | workflow logs |
| `backend-quality` | install PHP deps, run `composer qa:ci`, export JUnit/Clover | GitHub Actions + Composer + PHPUnit | push / PR / manual | `build/test-results/*` |
| `browser-smoke` | install Node 22, build assets, run Playwright | GitHub Actions + Playwright | push / PR / manual | Playwright HTML/traces |
| `release-package` | package source artifacts on tags | GitHub Actions | tag push | `dist/` package |

### Execution policy
- **On PR:** secret scan + backend-quality + browser-smoke
- **On main:** same gates, required to stay green
- **Nightly (recommended next step):** longer regression suite and trend metrics export
- **Release candidate:** full suite + evidence refresh + manual review of uploaded artifacts

### Workflow file
- `.github/workflows/ci.yml`

---

## Part J — Alerting and failure handling

| Scenario / Event | Alert Type | Recipient / Channel | Severity | Required Action | Escalation |
|---|---|---|---|---|---|
| backend test failure | red GitHub Actions job | PR author / maintainer | High | inspect JUnit log, fix or revert | block merge |
| critical regression in auth/account/internal boundary | red CI + failed local gate | maintainer | Critical | immediate triage, root-cause fix, rerun | block merge and release |
| browser smoke failure | red CI + Playwright trace | frontend / QA owner | High | inspect trace/screenshot, fix selector or UI regression | block merge |
| coverage below floor | failed coverage step | maintainer | Medium-High | inspect Clover generation and scope drift | block merge until explained/fixed |
| flaky test detected | repeated rerun mismatch | QA owner | Medium | isolate instability, quarantine only with explicit note | escalate if repeated 3x |
| CI infrastructure failure | workflow timeout or runner issue | maintainer | Medium | rerun once, then label infra issue separately | no silent ignore |
| report generation failure | missing HTML/XML/JSON outputs | QA owner | Medium | repair artifact path and rerun | must be green before final submission |

### Triage flow
1. reproduce locally with the same command;
2. classify as product bug, test drift, or infrastructure issue;
3. fix **one root cause at a time**;
4. rerun the exact failed gate;
5. only then reopen merge/release.

---

## Part K — Metrics collection plan

### 1) Automation coverage (critical-scenario coverage)

| Module / Feature | High-Risk Function | Test Automated? | Coverage %* | Notes |
|---|---|---|---:|---|
| Auth & session | login/logout/session | Yes | 100 | planned P1 scenarios are automated |
| Reader account & reservations | summary, redirects, reservations | Yes | 89 | strong defended coverage |
| Catalog discovery & detail | search, sort, filters, empty results, detail | Yes | 88 | core search paths are automated |
| Internal staff boundary | protected views and staff-only behavior | Yes | 85 | boundary is covered with admin/librarian and deny-by-default checks |
| Integration boundary & idempotent mutations | document CRUD guards, rate limits, replay-safe reservation mutations | Yes | 92 | invalid input, replay, and boundary safety are now explicitly automated |
| Public shell smoke | homepage, catalog shell, guest redirect | Yes | 100 | full smoke scope currently automated |

> `*Coverage %` here means **critical-scenario coverage**, not monolith-wide line coverage.

### 2) Execution time tracking (fresh verified baseline)

| Module / Step | Number of Tests | Execution Time | Environment | Notes |
|---|---:|---:|---|---|
| `composer qa:ci` backend critical path | 125 | `8.05s` | local verified run (2026-04-11) | includes 550 assertions |
| Vite production build | — | `1.25s` | local verified run (2026-04-11 evidence capture) | part of `composer qa:ci` after a clean-runner `npm ci` bootstrap |
| `npm run test:e2e` | 3 | `4.7s` | local verified run (2026-04-11) | Playwright smoke baseline after output-path hardening |

### 3) Defects vs expected risk

| Module / Feature | High-Risk Level | Expected Defects | Defects Found | Pass / Fail | Interpretation |
|---|---:|---:|---:|---|---|
| Auth/session | High | Medium | 0 open in defended scope | Pass | stable under current suite |
| Reader account | High | Medium | 0 open in defended scope | Pass | protected route behavior verified |
| Catalog/search | High | Medium | 0 open in defended scope | Pass | filter and no-result behavior verified |
| Playwright infrastructure | Medium | 1 | 1 fixed | Pass | report-path permission issue resolved in config |
| Smoke expectation drift | Medium | 1 | 1 fixed | Pass | stale homepage text assertion updated |

### 4) Test execution log example

| Test Case ID | Module | Execution Date | Environment | Result | Defects Found | Execution Time |
|---|---|---|---|---|---:|---:|
| `TC-AUTH-01` | Auth | 2026-04-11 | local / Docker-backed | Pass | 0 | included in `8.50s` gate |
| `TC-CAT-01` | Catalog | 2026-04-11 | local / PostgreSQL | Pass | 0 | included in `8.50s` gate |
| `TC-E2E-01` | Homepage smoke | 2026-04-11 | Playwright Chromium | Pass | 0 | `~1.3s` |

### Metric formulas
- **Critical-scenario coverage %** = `(automated high-risk scenarios / planned high-risk scenarios) × 100`
- **Pass rate %** = `(passed tests / executed tests) × 100`
- **Execution time** = wall-clock time from verified command logs
- **Defects vs risk** = compare predicted-risk areas against defects discovered during verification/hardening

---

## Part L — Metrics reporting and visualization

### Recommended visuals for the paper
- **Bar chart:** automated checks by high-risk module
- **Line chart:** execution time trend across local and CI runs
- **Table:** defects found vs predicted risk area
- **Pie chart:** pass/fail distribution of the defended suite

### Report-ready captions
- **Figure 1.** Risk-based automation coverage across the highest-priority Digital Library modules.
- **Figure 2.** Verified execution-time profile for backend and browser quality gates.
- **Table X.** Mapping between predicted system risk and defects discovered during the automation hardening cycle.

---

## Part M — QA Test Strategy Document mapping
A full outline is provided in:
- `docs/qa/qa-test-strategy-document.md`

Use that file as the **Methods + Implementation + Reproducibility** backbone for the written submission.

---

## Part N — Defense preparation notes

| Defense Topic | What to show | What to say |
|---|---|---|
| Why these modules are high risk | `routes/web.php`, `routes/api.php`, current tests | they map to security, reader trust, and core discovery value |
| Why Playwright + PHPUnit | test files + CI workflow + fresh run logs | this stack fits the real repo and minimizes tool-switch overhead |
| Why quality gates are reasonable | `docs/qa/quality-gates.md` + actual pass thresholds | gates are anchored to verified repo behavior, not invented numbers |
| How CI/CD works | `.github/workflows/ci.yml` | push/PR automation continuously verifies backend + browser flows |
| How reproducibility is proven | `evidence/verification/*`, JUnit, Clover, Playwright reports | another reviewer can rerun the same commands and compare outputs |

### Likely questions and short answers
- **Why not 80% overall line coverage?**  
  Because this package uses a truthful **risk-based automation scope**; global line coverage on the whole monolith would misrepresent the real maturity of the defended suite.
- **How do you know the tests are maintainable?**  
  They follow repo-native frameworks, stable route contracts, and thin smoke coverage instead of brittle UI over-automation.
- **How is CI connected to release confidence?**  
  PRs and pushes must stay green across secret scan, backend verification, and browser smoke before code is considered safe to ship.

---

## Part O — Deliverables checklist mapping

| Deliverable | What exists in the project | Suggested File / Folder | Status | Evidence Needed |
|---|---|---|---|---|
| Automated Test Scripts | PHPUnit + Playwright suites | `tests/Feature/`, `tests/e2e/` | Ready | test files + run logs |
| QA Test Strategy Document | formal outline and repo-specific scope | `docs/qa/qa-test-strategy-document.md` | Ready | doc screenshot / PDF export |
| Quality Gate Report | gate definitions and thresholds | `docs/qa/quality-gates.md` | Ready | `composer qa:ci` output |
| Metrics Report | timings, coverage, risk/defect mapping | `docs/qa/metrics-report.md` | Ready | JUnit/Clover/log files |
| CI/CD Pipeline Evidence | GitHub Actions workflow and remote summaries | `.github/workflows/ci.yml`, `evidence/verification/remote-ci-summary-*.txt` | Ready | workflow screenshots/logs |
| Logs | local QA and browser smoke logs | `evidence/verification/*.txt` | Ready | timestamped text logs |
| Screenshots / traces | Playwright HTML/traces on failure or review | Playwright report directory | Ready | trace zip / HTML report |
| Repository Link | GitHub repo on `main` | `almazmurat/digital-library` | Ready | repo URL |
| Code Snippets | workflow, tests, scripts | `tests/`, `scripts/dev/`, `.github/workflows/` | Ready | excerpt in paper/report |
| Charts / exported reports | Clover/JUnit + optional SVG/CSV exports | `build/test-results/`, `evidence/verification/charts/` | Ready with optional refresh | attach generated assets |
