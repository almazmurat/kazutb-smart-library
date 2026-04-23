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

## 2026-04-22 — External references are inspiration, not canonical product truth
**Decision:** Externally provided example pages/screenshots/exports are inspiration and quality references only; they are not canonical source-of-truth contracts.
**Reason:** Product coherence and domain correctness must be governed by `PROJECT_CONTEXT.md` and ratified decision memory, not by one-to-one mimicry of third-party examples.
**Alternatives considered:** Treat every incoming reference screen as authoritative implementation spec.
**Impact:** Future planning and implementation must adapt references to KazUTB Smart Library goals, preserving product integrity over strict visual copying.

---

## 2026-04-22 — Public Phase 3 is governed by cluster decomposition, not local-code gravity
**Decision:** The remaining public Phase 3 work is managed as five explicit clusters (A–E), not a linear page-by-page sequence derived from what already exists in the codebase.
**Reason:** New product scope (leadership, rules, events, latest arrivals, location/map, knowledge-map) entered the canonical truth after the last implementation session. Continuing with "resources next because it exists" without acknowledging the broader decomposition would cause the local code state to silently override the product plan.
**Alternatives considered:** Continue directly with resources refinement without reconciliation.
**Impact:** DELIVERY_ROADMAP.md Phase 3 is now cluster-structured. Next implementation = Cluster A.1 (Resources refinement), but explicitly ratified within the cluster plan — not by default.

---

## 2026-04-22 — Events are a distinct public module from news
**Decision:** Public events/calendar will be tracked as a separate surface (`/events`, `/events/{slug}`) and not merged blindly into `/news`.
**Reason:** News and events have different user intents, information structure, and lifecycle needs; combining them degrades discoverability.
**Alternatives considered:** Keep only news and embed event posts as regular news items.
**Impact:** Events are Phase 3 Cluster C. Requires new Stitch cycle before implementation.

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


## 2026-04-21 — feat(phase-0): role-based login redirect + /internal/* auth + design-export cleanup
**Type:** feat (Routing)
**Files changed:** routes/web.php
**What changed:** Route definition update
**Commit:** 0dd6dcb on main
**Impact:** Routing change — check page map
**Keywords:** feat, auth
**Source:** Git hook auto-capture from commit 0dd6dcb

---


## 2026-04-21 — feat(phase-1.1): librarian shell + /librarian overview page
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/layouts/librarian.blade.php, resources/views/librarian/overview.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 9a6e8b0 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 9a6e8b0

---


## 2026-04-21 — feat(phase-1.2): canonical librarian circulation / data-cleanup / repository
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/layouts/librarian.blade.php, resources/views/librarian/circulation.blade.php, resources/views/librarian/data-cleanup.blade.php, resources/views/librarian/repository.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 74e3531 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 74e3531

---


## 2026-04-21 — feat(phase-1.4): 301 redirects /internal/* -> /librarian/* + canonical UI cleanup
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/layouts/admin.blade.php, resources/views/librarian/circulation.blade.php, resources/views/librarian/overview.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** d0b9310 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat, ui
**Source:** Git hook auto-capture from commit d0b9310

---


## 2026-04-21 — feat(phase-2a): canonical member shell + /dashboard overview / reservations / list
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/layouts/member.blade.php, resources/views/member/dashboard.blade.php, resources/views/member/list.blade.php, resources/views/member/reservations.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 8ec69ff on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 8ec69ff

---


## 2026-04-21 — fix(stabilization): member logout + admin/librarian shell polish
**Type:** fix (UI/Frontend)
**Files changed:** resources/views/layouts/admin.blade.php, resources/views/layouts/librarian.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 33bc9e6 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** fix
**Source:** Git hook auto-capture from commit 33bc9e6

---


## 2026-04-21 — feat(phase-2b): complete canonical member module — history/notifications/messages + /account transitional banner
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/account.blade.php, resources/views/layouts/member.blade.php, resources/views/member/history.blade.php, resources/views/member/messages.blade.php, resources/views/member/notifications.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 069b71f on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 069b71f

---


## 2026-04-22 — feat(phase-3.1): canonical homepage — KazUTB Smart Library brand + /dashboard workspace routing
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/welcome.blade.php
**What changed:** Blade view modification — library UI
**Commit:** 7d7e137 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 7d7e137

---


## 2026-04-22 — feat(phase-3.2): canonical about + contacts — consolidated KazUTB Smart Library informational surface
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/about.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** f23721d on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit f23721d

---


## 2026-04-22 — feat(phase-3.3): canonical public news index + detail surfaces
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/news/index.blade.php, resources/views/news/show.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 3f244fe on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 3f244fe

---


## 2026-04-22 — fix(phase-3.3): stabilize public news brand and image assets
**Type:** fix (UI/Frontend)
**Files changed:** resources/views/layouts/public.blade.php, resources/views/partials/footer.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 4fa272f on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** fix
**Source:** Git hook auto-capture from commit 4fa272f

---


## 2026-04-22 — feat(phase-3.b.2): add public /rules page
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/partials/footer.blade.php, resources/views/rules.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** ac3884a on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit ac3884a

---


## 2026-04-22 — feat(phase-3.b.3): embed location / fund rooms / visit notes into /contacts
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/about.blade.php
**What changed:** Blade view modification — library UI
**Commit:** d376c3b on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit d376c3b

---


## 2026-04-22 — feat(phase-3.b.4): embed collection profile + institutional directory into /about
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/about.blade.php
**What changed:** Blade view modification — library UI
**Commit:** f5775b6 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit f5775b6

---


## 2026-04-22 — feat(phase-3.b.5): canonical-exact /about rebuild per about_library_canonical
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/about.blade.php
**What changed:** Blade view modification — library UI
**Commit:** 3f3cf8b on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 3f3cf8b

---


## 2026-04-22 — feat(phase-3.b.6): canonical-exact /contacts rebuild per contacts_canonical
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/contacts.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 50a44d7 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 50a44d7

---


## 2026-04-22 — feat(phase-3.c.1): public /events index per events_index_canonical
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/events/index.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** 41c8755 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 41c8755

---


## 2026-04-22 — feat(phase-3.c.2): public /events/{slug} detail per event_detail_canonical
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/events/show.blade.php, routes/web.php
**What changed:** Blade view modification — library UI
**Commit:** ed1ea9d on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit ed1ea9d

---


## 2026-04-22 — feat(phase-3.d): public /resources canonical-exact rebuild per institutional_resources_canonical
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/resources.blade.php
**What changed:** Blade view modification — library UI
**Commit:** 6fb3607 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 6fb3607

---


## 2026-04-22 — feat(phase-3.e): public /discover canonical-led rebuild per academic_discovery_hub_canonical
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/discover.blade.php
**What changed:** Blade view modification — library UI
**Commit:** b0319cf on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit b0319cf

---


## 2026-04-22 — feat(phase-3.f): public /news index canonical-exact rebuild per news_index_canonical
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/news/index.blade.php
**What changed:** Blade view modification — library UI
**Commit:** 2b9173b on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 2b9173b

---


## 2026-04-23 — feat(phase-3.g): public /news/{slug} detail canonical-exact rebuild per news_detail_canonical
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/news/show.blade.php
**What changed:** Blade view modification — library UI
**Commit:** 37a0467 on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 37a0467

---


## 2026-04-23 — feat(phase-3.h): public homepage canonical-exact rebuild
**Type:** feat (UI/Frontend)
**Files changed:** resources/views/layouts/public.blade.php, resources/views/welcome.blade.php
**What changed:** Blade view modification — library UI
**Commit:** 378cf1a on main
**Impact:** Frontend visual change — verify in browser after deploy
**Keywords:** feat
**Source:** Git hook auto-capture from commit 378cf1a

---

## Links
- [[PROJECT_CONTEXT]]
- [[CURRENT_STATE]]
- [[OPEN_QUESTIONS]]




## 2026-04-22 — Post-Cluster-C public-layer cleanup pass
**Decision:** `resources/views/about.blade.php` is now a single-variant, about-only view (885 lines). The `$activePage === 'contacts'` branch is retired; `/contacts` is served exclusively by the standalone canonical `resources/views/contacts.blade.php` (Cluster B.6). Orphaned Cluster-B.4 secondary-copy keys and CSS (zero HTML consumers) were removed at the same time as superseded-by-B.5 dead code.

**Also decided:** `tests/Feature/PublicShellTest.php::test_contacts_page_can_render_in_english` asserts against the current public brand `KazUTB Smart Library` and the canonical `Support Channels` section heading — not the legacy `KazTBU Digital Library` / `How to reach the library` strings, which no longer appear in the rendered `/contacts` output.

**Also decided:** `docs/design-exports/athenaeum_digital/` is removed from the filesystem to match `docs/design-exports/canonical-design-map.md:98` ("deleted 2026-04-21") and [[PROJECT_CONTEXT]] §31.2. It must not be reintroduced.

**Reason:** Eliminate drift between canonical design docs, tests, and views after Clusters B+C. Keep the public layer tightly consolidated before any next implementation wave begins.

**Files affected:** `resources/views/about.blade.php`, `tests/Feature/PublicShellTest.php`, `docs/design-exports/athenaeum_digital/` (deleted), vault: `02-memory/CURRENT_STATE.md`, `02-memory/TASK_LOG.md`, `02-memory/DECISIONS.md`.

**Verification:** 14-class targeted public suite — 154 passed (826 assertions, 16.44s), 0 failures.

## 2026-04-23 | Wave 1 — `/account` retired from public shell, `/dashboard` is canonical | Why | Copilot CLI (Wave 1)
**Decision:** `/account` is no longer linked from any public shell surface (navbar, footer, post-login redirect, JS fallbacks, account icon). All member-reader entry points now route to `/dashboard`. The `/account` route itself is **retained as a hidden backward-compatibility surface**, not redirected.
**Why:** The product mandate is single-canonical user landing on `/dashboard`. ~30 pre-existing functional tests (LoanVisibility, AccountRenewal, ReaderAccessProtection, Consolidation, AccountReservations, ReaderAccountCompletion, IdentityMappingE2E, DigitalMaterial, ReaderReservation, AccountPage) hit `/account` directly with auth session and assert 200; converting to a redirect would mass-break out-of-Wave-1 functional surface. Compromise = "remove all shell usage and leave hidden compatibility route" (option 3 from the spec).
**Files affected:** routes/web.php, resources/views/{partials/navbar,partials/footer,auth,shortlist,reader}.blade.php

## 2026-04-23 | Wave 1 — Navbar IA model: 5 primary + Institution disclosure | Why | Copilot CLI (Wave 1)
**Decision:** Public navbar uses **5 primary links + 1 disclosure dropdown** model:
- Primary (always visible): Catalog · Discover · Resources · News · Events
- "Institution" `<details>` disclosure (auto-opens on active page): About · Leadership · Rules · Contacts
- Right side: visible pill locale switcher (kk/ru/en) + Sign in (guest) OR Dashboard + Sign out + account icon→/dashboard (auth)
**Why:** Compact primary keeps shell scannable; institution group is editorial and benefits from grouping. `<details>` is pure HTML/CSS — no JS dependency, accessible, auto-opens when current route is one of the four. Avoids navbar overload while making every major public surface discoverable.
**Files affected:** resources/views/partials/navbar.blade.php

## 2026-04-23 | Wave 1 — Footer IA: 4 columns + secondary language row | Why | Copilot CLI (Wave 1)
**Decision:** Footer is now a structured 4-column system footer:
1. **Navigation / Навигация / Басқару** — Home, Catalog, Discover, Resources
2. **Updates / Обновления / Жаңартулар** — News, Events
3. **Institution / Институт** — About, Leadership, Rules, Contacts
4. **Support / Поддержка / Қолдау** — Shortlist, Open portal (→/dashboard), Contact Librarian, +Sign in (guest)
Bottom row: copyright + secondary language switcher using full names (Қазақша / Русский / English).
**Why:** Mirrors and reinforces navbar IA, ensures every major public surface is reachable from any page, exposes a fallback locale switcher, and makes "Open portal" → /dashboard explicit. Preserved exact label strings asserted by PublicShellTest (Главная/Подборка/Открыть кабинет + KK/EN equivalents).
**Files affected:** resources/views/partials/footer.blade.php

## 2026-04-23 | Wave 1 — Language switching: fullUrlWithQuery preserves route + safe query | Why | Copilot CLI (Wave 1)
**Decision:** Both the navbar pill switcher and the footer secondary switcher generate per-locale targets using `request()->fullUrlWithQuery(['lang' => $code])`. This:
- preserves the current path (including slug-bearing routes like `/news/{slug}`, `/events/{slug}`),
- preserves all other current query parameters (e.g. `?udc=33` on /catalog),
- only overwrites the `lang` key,
- works for ru (default → bare path), kk, en (path + ?lang=…),
- gracefully falls through to the default locale when a label has no custom translation (existing $copy fallback pattern).
The locale-resolution layer (lang query → app()->setLocale → $pageLang whitelist) was NOT changed — only the switcher visibility was promoted from sr-only to a visible pill row, and a duplicate secondary switcher was added to the footer.
**Files affected:** resources/views/partials/{navbar,footer}.blade.php

## 2026-04-23 | Wave 1 — Explicit scope boundaries | Why | Copilot CLI (Wave 1)
**Decision:** Wave 1 is shell/IA/localization/route-cleanup ONLY. Out of scope (deferred to later waves): guest/user/librarian/admin feature completion, dashboard feature completion, profile management, deep DB/news/log architecture, full catalog functional repair, book-detail logic redesign, admin/librarian panel redesign, new design generation for private surfaces. The 32KB legacy `account.blade.php` view is untouched (still served by the compat route). The 56-test pre-existing failure baseline is unchanged.
**Files affected:** none (scope statement)
