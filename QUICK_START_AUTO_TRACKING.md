# 🚀 Quick Start — Auto-Tracking System (Apr 15, 2026)

## System Status: ✅ ACTIVE & VERIFIED

All automatic tracking is now operational. This is your quick reference.

---

## For Immediate Use (Agent Only)

### Start of Session
```bash
# 1. REQUIRED: Load context from Obsidian second brain
bash scripts/dev/obsidian-bootstrap.sh

# 2. Understand what auto-tracking means
cat .instructions.md          # Rules (5 min read)
cat AGENT_TRACKING_INSTRUCTIONS.md  # Patterns (10 min read)

# 3. Start work — memory is captured automatically
```

### During Work
- **User prompts**: Captured as professional request nodes automatically ✓
- **Code changes**: Memory created automatically ✓
- **Tests**: Results logged automatically ✓  
- **Decisions**: Documented automatically ✓
- **Gotchas**: Captured automatically ✓
- **Entity state** (e.g., navbar label history): tracked automatically when provided ✓
- No manual steps required

### End of Session
```bash
# Option 1: From project root
bash scripts/dev/auto-sync.sh

# Option 2: From ANY subdirectory (using wrapper)
/path/to/project/auto-sync

# Automatically:
# ✓ Commits all changes with memory references
# ✓ Creates session boundary
# ✓ Pushes to origin/main
# ✓ Next session has full context
```

---

## File Reference

| File | Purpose | Usage |
|------|---------|-------|
| `.instructions.md` | High-level rules | Read once, understand principles |
| `AGENT_TRACKING_INSTRUCTIONS.md` | How-to patterns | Reference per task type |
| `scripts/dev/obsidian-bootstrap.sh` | Mandatory session bootstrap | Run before any task |
| `scripts/dev/obsidian-capture-request.sh` | Prompt capture + entity state tracking | Run for every user request |
| `scripts/dev/install-auto-sync-alias.sh` | Global shell aliases installer | Run once on machine setup |
| `scripts/dev/auto-sync.sh` | Session-end automation | Run at end of work |
| `artifacts/obsidian/memory-fragments/WORK_LOG_*.md` | Daily task tracker | Read at session start |
| `artifacts/obsidian/memory-fragments/CENTRAL_HUB_MIN.md` | Memory router | Daily navigation hub |
| `artifacts/obsidian/memory-fragments/decision-*.md` | Decision records | Navigate past decisions |
| `artifacts/obsidian/memory-fragments/code-change-*.md` | Change logs | Track code modifications |
| `artifacts/obsidian/memory-fragments/gotcha-*.md` | Lessons learned | Avoid past mistakes |

---

## 3-Minute Daily Checklist

- [ ] Start session: `bash scripts/dev/obsidian-bootstrap.sh`
- [ ] Understand rules: Skim `.instructions.md` (first time only)
- [ ] Capture request: `obs-capture "<raw user request>" [--entity ...]`
- [ ] If entity changed before: `cat artifacts/obsidian/memory-fragments/entities/entity-<slug>.md` before editing
- [ ] Work: Make code changes + auto-capture memory
- [ ] End session: `bash scripts/dev/auto-sync.sh`
- [ ] Done: All changes committed + pushed automatically

---

## Memory Fragment Types

Auto-tracker creates these fragments:

| Type | File Pattern | When Created |
|------|---|---|
| **Work Log** | `WORK_LOG_YYYYMMDD.md` | Daily, updated throughout |
| **Code Change** | `code-change-YYYYMMDDTHHMM.md` | After significant code edit |
| **Decision** | `decision-YYYYMMDDTHHMM.md` | When making architectural choice |
| **Gotcha** | `gotcha-YYYYMMDDTHHMM.md` | When discovering trap/issue |
| **Test Failure** | `test-failure-YYYYMMDDTHHMM.md` | When test reveals bug |
| **Integration Note** | `integration-note-YYYYMMDDTHHMM.md` | When calling external API |
| **Perf Note** | `perf-note-YYYYMMDDTHHMM.md` | When optimizing |
| **Security Note** | `security-note-YYYYMMDDTHHMM.md` | When fixing security vuln |
| **Session Boundary** | `YYYY-MM-DD_HHMMSS__session-boundary__auto-sync.md` | At session end (auto) |
| **Request Node** | `YYYY-MM-DD_HHMMSS__request__<slug>.md` | For every user prompt |
| **Heartbeat** | `YYYY-MM-DD_HHMMSS__sync-heartbeat__no-source-changes.md` | No-change sync run |
| **Entity State** | `entities/entity-<slug>.md` | Persistent state history |

---

## Auto-Sync Script Details

**Location**: `scripts/dev/auto-sync.sh`

**What it does** (auto):
1. ✓ Checks for changes
2. ✓ Generates memory from chat logs
3. ✓ Creates heartbeat note even for no-change runs
4. ✓ Stages all files
5. ✓ Creates smart commits (with memory references)
6. ✓ Pushes to origin/main
7. ✓ Reports summary

**Time**: ~30 seconds

**When to run**: At end of work session (explicit call)

---

## What Changes From Previous Workflow

### BEFORE (Manual)
❌ Run `git add` manually  
❌ Write git commit message (generic)  
❌ Run `python3 obsidian_brain_sync.py` manually  
❌ Update memory files by hand  
❌ Next session: Reexplain context  

### AFTER (Automatic)
✅ Memory created during work (no step needed)  
✅ Git commits include memory references  
✅ Auto-sync handles everything (`bash scripts/dev/auto-sync.sh`)  
✅ Memory linked + categorized automatically  
✅ Next session: Read memory, understand context instantly  

---

## Verification Status

**System Test**: ✅ PASSED  
- Code change created
- Memory fragment created
- Auto-sync executed
- Git commits verified
- Session boundary created
- Push to origin: SUCCESS
- Obsidian frontmatter: VALID

**Last update**: 2026-04-15 08:02 UTC  
**Last commit**: 4187a8c  
**Branch**: main (origin synced)

---

## Troubleshooting

**Q: "bash: scripts/dev/auto-sync.sh: No such file or directory"**
- You're likely in a subdirectory (like `docs/design-exports/`)
- Solution 1: Change to project root: `cd /home/admlibrary/kazutb-smart-library-main && bash scripts/dev/auto-sync.sh`
- Solution 2 (easier): Use wrapper from anywhere: `/home/admlibrary/kazutb-smart-library-main/auto-sync`
- Solution 3: Add wrapper to PATH for ultimate convenience

**Q: Memory fragment not created**
- Check memory file exists in: `artifacts/obsidian/memory-fragments/`
- Frontmatter must include `type:` field
- Valid types: code-change, decision, gotcha, test-failure, etc.

**Q: Git commit didn't include memory reference**
- Edit commit after the fact: `git commit --amend`
- Add reference to memory file in message

**Q: Session boundary missing**
- Auto-created by `bash scripts/dev/auto-sync.sh`
- File: `artifacts/obsidian/memory-fragments/session-boundary-YYYYMMDDTHHMM.md`
- Location: Verify it was pushed (`git log` should show it)

---

## Next Session Quick Start

```bash
# Day 2 — Resume from Apr 15 work

# 1. Read yesterday's log
cat artifacts/obsidian/memory-fragments/WORK_LOG_20260415.md

# 2. Check hub for all related memory
cat artifacts/obsidian/memory-fragments/CENTRAL_HUB_MIN.md

# 3. Start work — system tracks automatically
# No re-explanation needed, full context preserved  

# 4. End of day
bash scripts/dev/auto-sync.sh

# Done!
```

---

## System Components Summary

```
Agent Auto-Tracking System
│
├── Rules (.instructions.md)
│   ├── Rule 1: Auto State Snapshots
│   ├── Rule 2: Continuous Git Tracking
│   ├── Rule 3: Automatic Memory Linking
│   └── Rule 4: Session Boundary Automation
│
├── Patterns (AGENT_TRACKING_INSTRUCTIONS.md)
│   ├── Code changes → code-change-*.md
│   ├── Decisions → decision-*.md
│   ├── Gotchas → gotcha-*.md
│   ├── Tests → WORK_LOG entries
│   └── Integration → integration-note-*.md
│
├── Automation (scripts/dev/auto-sync.sh)
│   ├── Memory generation
│   ├── Git staging + commit
│   ├── Session boundary creation
│   └── Push to remote
│
├── Memory Storage (artifacts/obsidian/memory-fragments/)
│   ├── WORK_LOG_*.md (daily tracking)
│   ├── CENTRAL_HUB_MIN.md (router)
│   ├── decision-*.md (decisions)
│   ├── code-change-*.md (code changes)
│   ├── gotcha-*.md (lessons)
│   └── session-boundary-*.md (session markers)
│
└── Git Integration (commits + pushes)
    ├── Smart commit messages
    ├── Memory references
    └── Session continuity
```

---

**Last Setup**: 2026-04-15  
**Status**: ✅ PRODUCTION READY  
**Questions?**: See `.instructions.md` or `AGENT_TRACKING_INSTRUCTIONS.md`  
**Next session?**: Just run agent — memory handles the rest!
