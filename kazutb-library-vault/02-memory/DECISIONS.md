# Decision Log — KazUTB Library Platform

> Decisions already encoded in [[PROJECT_CONTEXT]] are NOT repeated here.
> This file captures implementation-level and process-level decisions only.

## Format
Each entry: Date | Decision | Why | Who

---

## 2026-04-17 — Export-first UI delivery rule
**Decision:** Implement exported screens in the order design fidelity first, then content adaptation, then logic wiring.
**Reason:** This keeps the visual reset coherent while still allowing real product behavior to be wired safely afterward.
**Alternatives considered:** Logic-first reconstruction with loose styling.
**Impact:** Frontend work should preserve the verified design anchors while layering real domain behavior underneath.

---

## 2026-04-17 — Canonical memory startup rule
**Decision:** Treat the active master and memory layers as the current operating truth and treat old archive material as historical reference only.
**Reason:** Archive-heavy navigation was polluting the graph and confusing session recovery.
**Alternatives considered:** Continue reading live and archived notes equally.
**Impact:** Session startup now begins from [[START_HERE]], [[CURRENT_STATE]], and [[PROJECT_CONTEXT]].

---

## 2026-04-19 — Admin shell is the next major product slice
**Decision:** Prioritize the admin overview and admin shell before adding secondary staff modules.
**Reason:** Governance, announcements, feedback handling, reporting, and repository oversight all depend on a real admin workspace.
**Alternatives considered:** Expanding isolated public pages first.
**Impact:** The next implementation wave should center on admin navigation, overview metrics, and operational controls.

---

## 2026-04-19 — Direct script fallback for vault sync
**Decision:** Keep the vault maintenance flow runnable directly through shell scripts when Composer is unavailable on a workstation.
**Reason:** Missing Composer is a local environment issue, not a reason to block memory upkeep.
**Alternatives considered:** Requiring Composer for every memory update.
**Impact:** Developer docs and routine operations can rely on the underlying scripts when needed.


## 2026-04-20 — Git-derived decision signal: feat: capture decision keywords in vault hooks
**Decision:** The commit message matched strategic keywords: feat, decision.
**Reason:** The change was auto-captured from Git history to preserve important implementation context in the second brain.
**Alternatives considered:** Not captured automatically by the hook.
**Impact:** Changed files: README.md, scripts/dev/git-vault-hook.sh
**Source:** Git hook auto-capture from commit 1bb3a8e

---

## 2026-04-21 — Hybrid shell architecture ratified
**Decision:** The platform adopts a four-shell hybrid architecture: public (`/`), member (`/dashboard/*`), librarian (`/librarian/*`), admin (`/admin/*`), plus a repository surface (`/repository/*`). Each staff shell has its own layout and middleware chain.
**Reason:** The admin shell (`layouts.admin`) is already built and governance-focused; librarian operations are distinct enough to need their own sibling shell; a unified staff shell would dilute both. This matches [[PROJECT_CONTEXT]] §30.
**Alternatives considered:** (A) Single unified staff shell; (B) keep `/internal/*` as permanent staff namespace.
**Impact:** Downstream work creates `layouts.librarian`, a new `librarian.staff` middleware, and migrates `/internal/*` to `/librarian/*`. Reference: [[DELIVERY_ROADMAP]] Phase 1.

---

## 2026-04-21 — `/internal/*` → `/librarian/*` rename with 301 redirects
**Decision:** The `/internal/*` route family (dashboard, circulation, stewardship, review, ai-chat) migrates to canonical `/librarian/*` paths. Every old path returns a 301 to its new equivalent during and after the migration cycle.
**Reason:** Canonical page map ([[PROJECT_CONTEXT]] §30.3) uses `/librarian/*`. Keeping `/internal/*` creates a permanent drift between code and canonical truth.
**Alternatives considered:** Keep `/internal/*` as the stable name; rename only when canonical doc changes.
**Impact:** One planning cycle of rename work (Phase 1 of [[DELIVERY_ROADMAP]]). Bookmarks preserved via 301s. Future librarian work targets `/librarian/*`.

---

## 2026-04-21 — Role-based post-login redirect is mandatory
**Decision:** The `POST /login` handler must route each role to its canonical landing: admin → `/admin`, librarian → `/librarian` (interim: `/internal/dashboard`), member → `/dashboard` (interim: `/account`).
**Reason:** Current implementation redirects all roles to `/account`, causing admin and librarian to land on the member dashboard with a "Good Morning" greeting. This is a functional and perception defect.
**Alternatives considered:** Keep universal `/account` landing and add an in-app role switcher.
**Impact:** Immediate edit in `routes/web.php` at both demo-auth and CRM success branches. Covered by new Playwright smoke test in Phase 0 of [[DELIVERY_ROADMAP]].

---

## 2026-04-21 — `docs/design-exports/athenaeum_digital/` is approved for deletion
**Decision:** The `athenaeum_digital` design export folder is deleted from the repo.
**Reason:** [[PROJECT_CONTEXT]] §31.2 explicitly identifies this direction as garbage and mandates permanent removal. Keeping it in the repo risks future agents treating it as guidance.
**Alternatives considered:** Retain as historical reference; move to archive outside repo.
**Impact:** One commit removing the folder; references in canonical-design-map cleared; no functional change to running application.

---

## 2026-04-21 — `/admin/*` is admin-only; `/librarian/*` is librarian + admin
**Decision:** Admin users can access librarian routes (operational oversight). Librarian users cannot access admin routes. Middleware: `library.auth` + `admin.staff` (admin only) versus `library.auth` + `librarian.staff` (librarian or admin).
**Reason:** Admins supervise librarians in practice; excluding admins from librarian tooling creates artificial silos.
**Alternatives considered:** Strict separation — admin accesses librarian work only via impersonation.
**Impact:** `EnsureLibrarianStaff` middleware accepts both roles; admin sidebar may link to librarian surfaces in later phases.

---

## 2026-04-21 — Admin shell mock data remains until Phase 6
**Decision:** The seven implemented admin pages keep their hard-coded demo values until Phase 6 of [[DELIVERY_ROADMAP]]. Architecture work (Phases 0–5) ships first.
**Reason:** Visual + route structure is the blocking dependency for every downstream workstream; backend wiring can follow once structure is stable.
**Alternatives considered:** Wire real data per page as each page is built.
**Impact:** Admin pages visibly demo-only until Phase 6; tests assert literal canonical content rather than live data.

---




## 2026-04-20 — Git-derived decision signal: fix: add route-aware state change hook
**Decision:** The commit message matched strategic keywords: fix.
**Reason:** The change was auto-captured from Git history to preserve important implementation context in the second brain.
**Alternatives considered:** Not captured automatically by the hook.
**Impact:** Changed files: kazutb-library-vault/02-memory/DECISIONS.md, kazutb-library-vault/02-memory/TASK_LOG.md, routes/web.php, scripts/dev/git-vault-hook.sh, scripts/dev/prepare-commit-msg-hook.sh
**Source:** Git hook auto-capture from commit b136c45

---


## 2026-04-20 — Vault helper workflow
**Decision:** Manual decision logging is available from the vault scripts.
**Reason:** Session continuity should be one command away.
**Alternatives considered:** Manually capture later.
**Impact:** Recorded through log_decision.ps1 for future session continuity.

---

## 2026-04-20 — Git-derived decision signal: feat: add vault session helper scripts and watcher
**Decision:** The commit message matched strategic keywords: feat.
**Reason:** The change was auto-captured from Git history to preserve important implementation context in the second brain.
**Alternatives considered:** Not captured automatically by the hook.
**Impact:** Changed files: .githooks/post-checkout, .githooks/post-commit, .githooks/post-merge, .githooks/prepare-commit-msg, .github/copilot-instructions.md, .gitignore, kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/DECISIONS.md, kazutb-library-vault/02-memory/OPEN_QUESTIONS.md, kazutb-library-vault/02-memory/TASK_LOG.md, kazutb-library-vault/scripts/LAST_GRAPH_HEALTH.md, kazutb-library-vault/scripts/WATCHER.ps1
**Source:** Git hook auto-capture from commit db8337e

---


## 2026-04-20 — Git-derived decision signal: fix: preserve watched vault state updates
**Decision:** The commit message matched strategic keywords: fix.
**Reason:** The change was auto-captured from Git history to preserve important implementation context in the second brain.
**Alternatives considered:** Not captured automatically by the hook.
**Impact:** Changed files: kazutb-library-vault/02-memory/CURRENT_STATE.md, kazutb-library-vault/02-memory/DECISIONS.md, kazutb-library-vault/02-memory/TASK_LOG.md, kazutb-library-vault/scripts/LAST_GRAPH_HEALTH.md, routes/web.php, scripts/dev/git-vault-hook.sh
**Source:** Git hook auto-capture from commit f9b581a

---


## 2026-04-20 — Git-derived decision signal: feat: add animated catalog book covers
**Decision:** The commit message matched strategic keywords: feat.
**Reason:** The change was auto-captured from Git history to preserve important implementation context in the second brain.
**Alternatives considered:** Not captured automatically by the hook.
**Impact:** Changed files: resources/views/catalog.blade.php
**Source:** Git hook auto-capture from commit a890a7c

---


## 2026-04-20 — Git-derived decision signal: fix: refine animated catalog cover hinge
**Decision:** The commit message matched strategic keywords: fix.
**Reason:** The change was auto-captured from Git history to preserve important implementation context in the second brain.
**Alternatives considered:** Not captured automatically by the hook.
**Impact:** Changed files: resources/views/catalog.blade.php
**Source:** Git hook auto-capture from commit b566e20

---


## 2026-04-20 — Animated book covers in catalog
**Decision:** Replaced placeholder images with CSS animated covers. Open 90deg on hover. Metadata shown on cover. Real uploaded images will replace color fills later without changing animation.
**Reason:** Performance: no external image requests. Better UX. Future-proof for real cover uploads.
**Alternatives considered:** Manually capture later.
**Impact:** Recorded through log_decision.ps1 for future session continuity.

---

## 2026-04-20 — Animated book covers in catalog
**Decision:** Replaced placeholder images with CSS animated covers. Open 90deg on hover. Metadata shown on cover. Real uploaded images will replace color fills later without changing animation.
**Reason:** Performance: no external image requests. Better UX. Future-proof for real cover uploads.
**Alternatives considered:** Manually capture later.
**Impact:** Recorded through log_decision.ps1 for future session continuity.

---

## 2026-04-20 — fix: test semantic hook capture
**Type:** fix (UI/Frontend)
**Files changed:** resources/views/catalog.blade.php
**What changed:** Blade view modification — catalog UI
**Commit:** 8807adf on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** fix
**Source:** Git hook auto-capture from commit 8807adf

---


## 2026-04-20 — Revert "fix: test semantic hook capture"
**Type:** change (UI/Frontend)
**Files changed:** resources/views/catalog.blade.php
**What changed:** Blade view modification — catalog UI
**Commit:** 524dabb on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** fix, revert
**Source:** Git hook auto-capture from commit 524dabb

---


## 2026-04-20 — feat: implement admin overview governance shell
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/admin/overview.blade.php, resources/views/admin/placeholder.blade.php, resources/views/layouts/admin.blade.php, routes/web.php
**What changed:** Blade view modification — admin panel UI
**Commit:** 61b9285 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 61b9285

---


## 2026-04-20 — feat: implement user & role management admin page (/admin/users)
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/admin/users.blade.php, routes/web.php
**What changed:** Blade view modification — admin panel UI
**Commit:** f059215 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit f059215

---


## 2026-04-20 — feat: implement governance & logs admin page (/admin/logs)
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/admin/governance.blade.php, resources/views/layouts/admin.blade.php, routes/web.php
**What changed:** Blade view modification — admin panel UI
**Commit:** abf720c on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit abf720c

---


## 2026-04-21 — feat: implement feedback inbox admin page (/admin/feedback)
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/admin/feedback.blade.php, routes/web.php
**What changed:** Blade view modification — admin panel UI
**Commit:** c59d42a on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit c59d42a

---


## 2026-04-21 — feat: implement news management admin page (/admin/news)
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/admin/news.blade.php, routes/web.php
**What changed:** Blade view modification — admin panel UI
**Commit:** 0f2d43c on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 0f2d43c

---


## 2026-04-21 — feat: implement system settings admin page (/admin/settings)
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/admin/settings.blade.php, routes/web.php
**What changed:** Blade view modification — admin panel UI
**Commit:** 43b00ba on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 43b00ba

---


## 2026-04-21 — feat: implement reports & analytics admin page (/admin/reports)
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/admin/reports.blade.php, routes/web.php
**What changed:** Blade view modification — admin panel UI
**Commit:** 9c97e4e on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 9c97e4e

---

## Links
- [[PROJECT_CONTEXT]]
- [[CURRENT_STATE]]
- [[OPEN_QUESTIONS]]



