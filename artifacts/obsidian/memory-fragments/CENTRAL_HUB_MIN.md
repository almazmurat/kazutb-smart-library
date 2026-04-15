# KazTBU Digital Library - Central Hub (Dynamic)

This hub is generated automatically from chat memory fragments.
It keeps only active knowledge paths, not full transcript summaries.

## Active Architecture Links
- [[02-architecture]] - evidence: 2
- [[03-domain]] - evidence: 2
- [[12-reference]] - evidence: 2
- [[06-decisions]] - evidence: 2
- [[04-crm-auth-integration]] - evidence: 2

## Source Chats
- [[chat.md]]

## Generated Fragments & Session Summaries
- [[chat-fragment.md]]
- [[session-2026-04-15.md]] (Apr 15, 07:43 UTC)
- [[design-exports-2026-04-15.md]] (Stitch project mapping)
- [[INTEGRATION_SUMMARY_2026-04-15.md]] (Complete session recap)
- [[WORK_LOG_20260415.md]] ← **CURRENT** (Auto-tracking in progress)

## Latest Architecture Additions (Apr 15)
- **Auto-Sync System**: New `.instructions.md` + `AGENT_TRACKING_INSTRUCTIONS.md`
- **Bootstrap Script**: `scripts/dev/obsidian-bootstrap.sh` is mandatory before any task
- **Session Script**: `scripts/dev/auto-sync.sh` for hands-free memory capture
- **Automation**: Agent auto-tracks decisions, code changes, tests, gotchas, and micro-updates
- **System Status**: ✅ PRODUCTION READY (tested & verified)
- **System Summary**: [[SYSTEM_SETUP_SUMMARY_2026-04-15]]
- **Decision**: [[decision-auto-tracking-framework-2026-04-15]]

## How to Use
1. **Agent starts task**: Run `bash scripts/dev/obsidian-bootstrap.sh` (required)
2. **During work**: Follow `.instructions.md` for rules, `AGENT_TRACKING_INSTRUCTIONS.md` for patterns
3. **Auto-create memory**: Agent writes fragments during work (no manual intervention)
4. **At session end**: Run `bash scripts/dev/auto-sync.sh` ← also writes heartbeat for no-change sessions
5. **Next session**: Resume from `WORK_LOG_YYYYMMDD.md` — zero context loss

## Key Files for Agent
- `.instructions.md` — MUST READ: High-level rules agent must follow
- `AGENT_TRACKING_INSTRUCTIONS.md` — Specific patterns for each task type
- `scripts/dev/obsidian-bootstrap.sh` — Mandatory session start loader
- `scripts/dev/auto-sync.sh` — Run at session end for automatic sync
- `artifacts/obsidian/memory-fragments/WORK_LOG_YYYYMMDD.md` — Daily task tracker
