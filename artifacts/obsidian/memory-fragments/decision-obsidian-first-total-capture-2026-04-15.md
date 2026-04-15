---
type: decision
project: [[Digital Library]]
date: 2026-04-15
timestamp: 2026-04-15T08:10:00Z
tags: [decision, obsidian-first, total-capture]
links:
  - "[[02-architecture]]"
  - "[[06-decisions]]"
  - "[[WORK_LOG_20260415]]"
---

# Decision: Obsidian-First Total Capture

## User Requirement
Obsidian is the second brain and must retain all operational memory from agent sessions, including tiny updates.

## Decision
Adopt strict `Obsidian-first` workflow:
1. Mandatory bootstrap from Obsidian before any implementation.
2. Auto-sync writes memory even for no-change sessions (heartbeat).
3. Micro-updates are considered first-class memory events.

## Implemented Controls
- Added required startup loader: `scripts/dev/obsidian-bootstrap.sh`.
- Updated rules in `.instructions.md` and `AGENT_TRACKING_INSTRUCTIONS.md`.
- Hardened `scripts/dev/auto-sync.sh` to create heartbeat note on clean tree.
- Updated `CENTRAL_HUB_MIN.md` and quick-start with mandatory bootstrap flow.

## Scope Clarifier
"Absolute everything" is implemented for project-operational memory.
Sensitive secrets and huge binary artifacts should still be referenced by metadata, not duplicated verbatim.

## Operational Rule
Agent must read Obsidian first, write Obsidian during work, and sync Obsidian at session end.
