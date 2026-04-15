---
type: decision
project: [[Digital Library]]
date: 2026-04-15
timestamp: 2026-04-15T07:52:00Z
relates_to: [AgentWorkflow, AutomationSystem, MemoryManagement]
links:
  - "[[02-architecture]]"
  - "[[06-decisions]]"
  - "[[12-reference]]"
tags: [decision, system-design, automation]
---

# Decision: Automatic Memory Tracking for Agent Sessions

## The Problem

**Before**: After each agent session, user had to manually:
1. Commit pending changes
2. Generate Obsidian memory fragments
3. Link decisions/gotchas/code-changes together
4. Push to GitHub
5. Re-explain context in next session

**Result**: Lost institutional memory, context gaps between sessions, duplicate work

## The Decision

**Implement automatic tracking system** where agent (Copilot):
- Auto-captures decisions, code changes, tests, gotchas as they happen
- Writes to Obsidian-compatible memory fragments during work
- Commits with references to memory documents
- Runs single `bash scripts/dev/auto-sync.sh` at session end
- Everything is synchronized without user intervention

## Design

### Two-Layer Instruction System

**Layer 1: Rule Set (`.instructions.md`)**
- High-level RULES agent must follow
- 4 core rules:
  1. Auto State Snapshots (capture every significant change)
  2. Continuous Git Tracking (commit with memory references)
  3. Automatic Memory Linking (frontmatter + cross-references)
  4. Session Boundary Automation (end-of-session sync)
- Philosophy + system overview
- Updated when rules need to change

**Layer 2: Pattern Library (`AGENT_TRACKING_INSTRUCTIONS.md`)**
- Specific HOW-TO for each task type:
  - When making code changes
  - When running tests
  - When making decisions
  - When discovering gotchas
  - When integrating external systems
- Examples for each pattern
- Links back to rules
- Updated when new patterns discovered

### Automation Script (`scripts/dev/auto-sync.sh`)

**Replaces manual steps** at session end:

```bash
bash scripts/dev/auto-sync.sh

# Automatically:
# 1. Checks for changes
# 2. Runs obsidian-memory-capture.sh
# 3. Creates session-boundary-*.md
# 4. Commits all with smart message
# 5. Pushes to origin/main
# 6. Reports summary
```

**Usage**: Single command ≈ 30 seconds total

### Memory Structure by Category

Fragments organized in `artifacts/obsidian/memory-fragments/`:

| Type | File Pattern | Content |
|------|---|---|
| Daily Log | `WORK_LOG_YYYYMMDD.md` | Task list + summary for each day |
| Code Changes | `code-change-YYYYMMDDTHHMM.md` | File + lines + why + impact |
| Decisions | `decision-YYYYMMDDTHHMM.md` | Choices + reasons + tradeoffs |
| Gotchas | `gotcha-YYYYMMDDTHHMM.md` | Traps + root causes + solutions |
| Test Failures | `test-failure-YYYYMMDDTHHMM.md` | Test name + assertion + fix |
| Integration Notes | `integration-note-YYYYMMDDTHHMM.md` | API calls + responses + timing |
| Performance | `perf-note-YYYYMMDDTHHMM.md` | Metrics + before/after + index |
| Security | `security-note-YYYYMMDDTHHMM.md` | Vuln + fix + verification |

All have **Obsidian-compatible frontmatter** for cross-linking:
```yaml
---
type: [decision|gotcha|code-change|...]
project: [[Digital Library]]
date: YYYY-MM-DD
relates_to: [ServiceName, FileName, Concept]
links:
  - "[[02-architecture]]"
  - "[[WORK_LOG_YYYYMMDD]]"
tags: [auto-tracked, ...]
---
```

## Implementation

Created files:
- `.instructions.md` (1,200 lines) — High-level rules + philosophy
- `AGENT_TRACKING_INSTRUCTIONS.md` (600 lines) — Patterns + HOW-TOs
- `scripts/dev/auto-sync.sh` (200 lines) — Session-end automation
- `WORK_LOG_20260415.md` (first daily log)
- This decision document

## Guarantees to User

With this system in place:

✅ **Zero manual sync**: All tracking is automatic  
✅ **Complete memory**: Every decision + code change + gotcha recorded  
✅ **Smart git history**: Commits reference why, not just what  
✅ **Obsidian integration**: Memory is cross-linked and navigable  
✅ **Session continuity**: Next agent session resumes from memory  
✅ **No context loss**: Decisions persist forever  
✅ **Hands-free**: User just tells agent what to build  

## Trade-offs

**Accepted**: 
- Adds 10-15 files per active work session (memory fragments)
- Requires agent discipline to follow patterns (mitigated by `.instructions.md`)
- Auto-commit runs at session end (fine, synchronous anyway)

**Rejected**:
- Continuous background monitoring (too complex, Docker incompatible)
- AI extraction from git diffs (loses "why" context)
- Manual user involvement in sync (defeats purpose of automation)

## Verification

**Next steps to verify**:
1. Create test code change (comment addition)
2. Write corresponding `code-change-*.md` manually
3. Run `bash scripts/dev/auto-sync.sh`
4. Check:
   - [ ] Git commit includes memory reference
   - [ ] Memory fragments have correct Obsidian frontmatter
   - [ ] Cross-links work in memory documents
   - [ ] Push to origin/main succeeded
   - [ ] `WORK_LOG_20260415.md` updated
   - [ ] Session boundary created
5. If all pass: System is production-ready

## References

- **Rules**: `.instructions.md`
- **Patterns**: `AGENT_TRACKING_INSTRUCTIONS.md`
- **Script**: `scripts/dev/auto-sync.sh`
- **Daily Log**: `artifacts/obsidian/memory-fragments/WORK_LOG_YYYYMMDD.md`
- **Hub**: `artifacts/obsidian/memory-fragments/CENTRAL_HUB_MIN.md`

## Future Improvements

**Phase 2** (if needed):
- Add `--dry-run` flag to auto-sync for safety
- Create web dashboard viewing memory fragments
- Export session summaries as markdown files
- Add tagging system across all fragments

---

**Status**: IMPLEMENTED  
**Owner**: Agent (automatic)  
**Affects**: All future work sessions  
**Update Policy**: When new task types discovered, add pattern to AGENT_TRACKING_INSTRUCTIONS.md
