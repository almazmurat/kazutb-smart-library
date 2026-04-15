---
type: agent-mode-instructions
project: [[Digital Library]]
date: 2026-04-15
tags: [agent-instructions, auto-tracking, continuous-sync]
---

# AGENT MODE: Automatic Memory & Git Tracking

> **FOR COPILOT/AI AGENT**: Follow these rules automatically during ALL work sessions.

## Quick Rules (TL;DR)

1. **Before any task**: Run `bash scripts/dev/obsidian-bootstrap.sh` (MANDATORY)
2. **Before implementation**: Capture the raw user prompt via `bash scripts/dev/obsidian-capture-request.sh "<request>"`
3. **After every code change**: Write `memory/<category>-<timestamp>.md` with what/why/impact
4. **Before every commit**: Ensure memory entry exists referencing the commit
5. **At session end**: Run `bash scripts/dev/auto-sync.sh` to capture everything
6. **Always use frontmatter**: YAML with project/date/tags/links for Obsidian
7. **Cross-link everything**: Use `[[memory-fragment-name]]` to connect ideas
8. **Even no-change sessions**: Persist heartbeat notes to Obsidian via auto-sync
## Session Start Procedure (MANDATORY)

```bash
# Always load second-brain context first
bash scripts/dev/obsidian-bootstrap.sh

# Then start implementation
```

If bootstrap fails, fix/bootstrap the memory path first and only then continue.

## Request Capture Procedure (MANDATORY)

```bash
# Every user prompt becomes an Obsidian node
bash scripts/dev/obsidian-capture-request.sh "<raw request text>"

# Optional entity-state tracking for sticky UI/domain context
bash scripts/dev/obsidian-capture-request.sh "<request text>" --entity "navbar" --from "resources" --to "books"
```

This ensures future sessions know what changed before, from what value, and to what value.

## Entity Read-Before-Write Rule

Before changing any known entity again (e.g. `navbar-label`):

```bash
# 1) Read previous state first
cat artifacts/obsidian/memory-fragments/entities/entity-navbar-label.md

# 2) Apply new change
# 3) Capture request with transition
bash scripts/dev/obsidian-capture-request.sh "<request text>" --entity "navbar-label" --from "books" --to "new-value"
```

This guarantees continuity across sessions and chats.

## Detailed Instructions by Task Type

### When Making Code Changes

```
1. You're about to edit:  app/Services/Library/BookService.php
2. BEFORE editing, decide: Does this need memory capture?
   → YES if: Bug fix, new feature, architectural decision, performance optimization
   → NO if: Formatting, adding comments, minor refactors with no behavior change
3. After editing, create entry:
   artifacts/obsidian/memory-fragments/code-change-YYYYMMDDTHHMM.md
   ---
   type: code-change
   project: [[Digital Library]]
   relates_to: [BookService, CatalogSearch]
   git_commit: (will fill in later)
   links:
     - "[[02-architecture]]"
   ---
   
   # Code Change: Improve BookService Query Performance
   
   **File**: app/Services/Library/BookService.php  
   **Lines changed**: 45-60 (added index lookup + cache layer)  
   **Why**: Query was 2.3s, now 180ms after adding PostgreSQL index  
   **Impact**: Blocks [[decision-caching-strategy]] decision  
   **Tested**: BookServicePerformanceTest::testQueryUnder200ms PASSED
```

4. Commit with message that REFERENCES memory:
   ```bash
   git commit -m "perf(book-service): Add index lookup + result caching
   
   - Query time: 2.3s → 180ms
   - See: code-change-20260415T0147.md
   - Test: BookServicePerformanceTest PASSED"
   ```

### When Running Tests

```
1. Test passes:
   - Add line to WORK_LOG_YYYYMMDD.md:
     "✓ BookDetailDbTest::testFindByIsbn PASSED (125ms)"
   
2. Test fails:
   - Create artifacts/obsidian/memory-fragments/test-failure-YYYYMMDDTHHMM.md
   - Include: test name, assertion, reason, fix applied
   - Link to code-change that caused it
   
3. Coverage changes:
   - If coverage drops >1%, create perf-note-*.md explaining why
   - If coverage improves, celebrate but document what test was added
```

### When Making Architectural Decisions

```
1. You're deciding: "Should we cache book metadata in Redis or in-process?"
   
2. Create: artifacts/obsidian/memory-fragments/decision-YYYYMMDDTHHMM.md
   ---
   type: decision
   project: [[Digital Library]]
   timestamp: 2026-04-15T07:50:00Z
   relates_to: [BookService, Caching, Performance]
   links:
     - "[[02-architecture]]"
     - "[[06-decisions]]"
   ---
   
   # Decision: In-Process Metadata Caching
   
   **Decision**: Use in-process PHP cache for book metadata (not Redis)
   
   **Alternatives Considered**:
   - Redis (distributed, but adds deployment complexity)
   - DB-only (simple, but slow for repeated queries)
   
   **Chosen**: In-process Laravel cache (FileStore or Array driver)
   
   **Because**:
   - Solo app instance (10.0.1.8), no horizontal scaling needed
   - Metadata changes infrequently (librarian updates)
   - Cache invalidates on model update via observers
   
   **Trade-offs**:
   - Can't share cache across multiple PHP-FPM workers (acceptable, workers are in same container)
   - Must invalidate explicitly (handled by BookObserver)
   
   **Implementation**: See [[code-change-YYYYMMDDTHHMM]]
   
   **Verification**: BookServicePerformanceTest::testCachingBehavior
```

### When Discovering Gotchas/Traps

```
1. "Oh no, updating the route cache clears the entire optimization cache!"
   
2. Create: artifacts/obsidian/memory-fragments/gotcha-YYYYMMDDTHHMM.md
   ---
   type: gotcha
   project: [[Digital Library]]
   discovered_date: 2026-04-15
   relates_to: [Laravel, RouteCache, OptimizationCache]
   links:
     - "[[02-architecture]]"
     - "[[06-decisions]]"
   ---
   
   # Gotcha: Route Cache Clears Optimization Cache
   
   **The Trap**: Running `php artisan route:cache` also clears `bootstrap/cache/services.php`
   
   **Symptom**: After updating routes, service providers don't load (blank page error)
   
   **Root Cause**: Laravel's `optimize:clear` is bundled into route caching
   
   **Solution**: After route changes, explicitly run:
   ```bash
   php artisan optimize:clear
   php artisan route:cache
   ```
   
   **Prevention**: Use [[decision-route-invalidation-process]] workflow
   
   **Lesson Learned**: Always clear optimization cache when routes change
```

### When Integrating with External Systems

```
1. Making API call to CRM (10.0.1.47/api/login)
   
2. Create: artifacts/obsidian/memory-fragments/integration-note-YYYYMMDDTHHMM.md
   ---
   type: integration-note
   project: [[Digital Library]]
   system: CRM (10.0.1.47)
   relates_to: [Authentication, SessionManagement]
   links:
     - "[[04-crm-auth-integration]]"
   ---
   
   # Integration Note: CRM Login API Call
   
   **Endpoint**: POST http://10.0.1.47/api/login
   
   **Request**:
   ```json
   { "username": "user@library.kz", "password": "***" }
   ```
   
   **Response** (success):
   ```json
   { "access_token": "Bearer xyz...", "user": { "id": 1, "name": "..." } }
   ```
   
   **Response** (failure):
   ```json
   { "error": "Invalid credentials" }  // status: 401
   ```
   
   **Implementation**: app/Services/Authentication/CrmAuthService.php:45
   
   **Timing**: ~150ms average (sometimes 500ms if LDAP is slow)
   
   **Fallback**: If CRM is down, return 503 (don't block library with local auth yet)
```

## Session End Procedure (CRITICAL)

**When you finish all tasks in a session**:

```bash
# 1. Ensure all memory is written
# Review: artifacts/obsidian/memory-fragments/WORK_LOG_YYYYMMDD.md
#  Should have final list of all tasks + verification status

# 2. Run auto-sync (this does EVERYTHING):
bash scripts/dev/auto-sync.sh

# 3. Script automatically:
#  ✓ Generates memory fragments from chat logs
#  ✓ Creates session-boundary-*.md
#  ✓ Commits all changes with smart message
#  ✓ Pushes to origin/main
#  ✓ Outputs summary

# 4. You're done. Next session will resume from memory.
```

## Memory Fragment Quick Reference

**Frontmatter you MUST include**:
```yaml
---
type: [code-change|decision|gotcha|integration-note|test-failure|finding|perf-note|security-note]
project: [[Digital Library]]
date: YYYY-MM-DD
timestamp: YYYY-MM-DDTHH:MM:SSZ  (optional but good)
relates_to: [Service, File, Concept]  (optional)
links:
  - "[[01-project-truth]]"  (or 02-06, 12)
  - "[[WORK_LOG_YYYYMMDD]]"
tags: [auto-tracked, session-capture, ...]
---
```

**Files to UPDATE during session** (not create, UPDATE):
- `WORK_LOG_YYYYMMDD.md` — Add entries as you complete tasks
- `CENTRAL_HUB_MIN.md` — If connecting new fragments

**Files to CREATE during session**:
- `code-change-YYYYMMDDTHHMM.md` — For each significant code change
- `decision-YYYYMMDDTHHMM.md` — For architectural decisions
- `gotcha-YYYYMMDDTHHMM.md` — For discovered traps/issues
- `test-failure-YYYYMMDDTHHMM.md` — When a test fails with lesson
- (similarly for integration, perf, security, findings)

---

## Automation Guarantees

By following these rules, you get:

✅ **Complete memory** of every decision + code change + gotcha  
✅ **Automatic git history** that's meaningful (not "WIP" commits)  
✅ **Obsidian vault** that remembers everything forever  
✅ **Zero manual sync** — everything auto-commits + pushes  
✅ **Next session context** restored from memory without re-asking questions  

---

## What NOT To Do

❌ **Don't skip memory** "because the change is small" — small changes compound  
❌ **Don't forget frontmatter** — it breaks Obsidian linking  
❌ **Don't use vague messages** — "fixed bug" → "Fixed BookService query timeout by adding PostgreSQL index"  
❌ **Don't leave uncommitted work** — auto-sync expects clean diffs  
❌ **Don't create memory without linking** — `[[other-fragment]]` references are how memory works  

---

## Support

If any part is unclear, check:
- `.instructions.md` (high-level philosophy)
- `artifacts/obsidian/memory-fragments/WORK_LOG_*.md` (examples from previous sessions)
- `artifacts/obsidian/memory-fragments/integration-note-*` (integration patterns)

**Agent should follow these rules WITHOUT exceptions.**
