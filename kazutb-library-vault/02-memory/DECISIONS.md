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

## Links
- [[PROJECT_CONTEXT]]
- [[CURRENT_STATE]]
- [[OPEN_QUESTIONS]]
