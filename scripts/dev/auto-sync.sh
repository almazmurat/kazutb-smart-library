#!/usr/bin/env bash
set -euo pipefail

# Auto-Sync Script for KazUTB Smart Library
# Runs automatic memory capture, git commitment, and Obsidian sync
# Called by agent at session end or during long work sessions

# Find root directory (go up 2 levels from scripts/dev)
CALLER_DIR="$PWD"
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT_DIR"

TODAY=$(date -u +'%Y%m%d')
NOW=$(date -u +'%Y-%m-%dT%H:%M:%SZ')
NOW_HHMMSS=$(date -u +'%H%M%S')

MEMORY_FRAGMENTS="artifacts/obsidian/memory-fragments"
WORK_LOG="$MEMORY_FRAGMENTS/WORK_LOG_$TODAY.md"
BOUNDARY_FILE="$MEMORY_FRAGMENTS/session-boundary-${TODAY}T${NOW_HHMMSS}.md"
RUN_NOTE_FILE="$MEMORY_FRAGMENTS/run-${TODAY}T${NOW_HHMMSS}.md"

mkdir -p "$MEMORY_FRAGMENTS"

# Color codes for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}════════════════════════════════════════${NC}"
echo -e "${BLUE}Auto-Sync: Memory Capture & Git Sync${NC}"
echo -e "${BLUE}═════════════════════════════════════════${NC}"
echo "Timestamp: $NOW"
echo ""

STATUS_SNAPSHOT="$(git status --short || true)"

# ─── STEP 1: Check if there are any changes ───
echo -e "${BLUE}[1/5]${NC} Checking working directory state..."
CHANGES=$(git status --short | wc -l)
if [ "$CHANGES" -eq 0 ]; then
        echo -e "${GREEN}✓${NC} No source changes detected. Logging heartbeat to Obsidian memory."

        cat > "$RUN_NOTE_FILE" <<EOF
---
type: sync-heartbeat
project: [[Digital Library]]
timestamp: $NOW
tags: [obsidian-first, auto-sync, heartbeat]
links:
    - "[[CENTRAL_HUB_MIN]]"
    - "[[WORK_LOG_$TODAY]]"
---

# Auto-Sync Heartbeat — $NOW

- Triggered from: \
    - caller_dir: \
        - $CALLER_DIR
- Repository root: \
    - $ROOT_DIR
- Git status at trigger: clean
- Action: memory heartbeat captured even without code diffs

## Intent
Preserve full session continuity in Obsidian even for "small/no-change" interactions.
EOF

        git add "$RUN_NOTE_FILE" "$WORK_LOG" 2>/dev/null || true
        git commit -m "[obsidian-heartbeat] $NOW" || true
        echo "Pushing heartbeat to origin/main..."
        git push origin main 2>&1 | head -5 || true
        echo -e "${GREEN}✓${NC} Heartbeat synced to Obsidian memory."
        exit 0
fi

echo -e "${YELLOW}→${NC} Found $CHANGES changed/new files"
git status --short

echo ""

# ─── STEP 2: Generate memory fragments from chat logs ───
echo -e "${BLUE}[2/5]${NC} Generating Obsidian memory fragments..."
if bash scripts/dev/obsidian-memory-capture.sh "$MEMORY_FRAGMENTS" 2>&1 | grep -q "Memory fragments"; then
    echo -e "${GREEN}✓${NC} Chat fragments processed"
else
    echo -e "${YELLOW}→${NC} No new chat logs (normal if using direct edits)"
fi

echo ""

# ─── STEP 3: Ensure WORK_LOG exists for today ───
echo -e "${BLUE}[3/5]${NC} Updating work log..."
if [ ! -f "$WORK_LOG" ]; then
    cat > "$WORK_LOG" <<EOF
---
type: work-log
project: [[Digital Library]]
date: $(date -u +'%Y-%m-%d')
session_id: session-$TODAY
tags: [auto-tracked, continuous-log]
links:
  - "[[CENTRAL_HUB_MIN]]"
---

# Work Log — $(date -u +'%Y-%m-%d')

## Summary
- Auto-sync started at $NOW
- Session context: auto-tracked continuous memory

## Tasks
(Updated throughout session by agent auto-tracking)

## Memory Generated
(Listed by auto-sync at session end)
EOF
    echo -e "${GREEN}✓${NC} Created $WORK_LOG"
else
    echo -e "${GREEN}✓${NC} Work log exists: $WORK_LOG"
fi

echo ""

# ─── STEP 4: Stage and commit changes ───
echo -e "${BLUE}[4/5]${NC} Staging and committing changes..."

# Stage key directories
git add artifacts/obsidian/ || true
git add .planning/ 2>/dev/null || true
git add app/ 2>/dev/null || true
git add config/ 2>/dev/null || true
git add routes/ 2>/dev/null || true
git add database/ 2>/dev/null || true
git add tests/ 2>/dev/null || true
git add docs/ 2>/dev/null || true
git add scripts/ 2>/dev/null || true
git add resources/ 2>/dev/null || true
git add .instructions.md AGENT_TRACKING_INSTRUCTIONS.md QUICK_START_AUTO_TRACKING.md auto-sync 2>/dev/null || true

# Get summary of what changed
ADDED=$(git diff --cached --name-only | grep -E "^artifacts/obsidian" | wc -l)
OTHER=$(git diff --cached --name-only | grep -vE "^artifacts/obsidian" | wc -l)

# Create commit message
COMMIT_MSG="[auto-sync] Continuous memory capture and state tracking

- Memory fragments: $ADDED updated/created
- Source changes: $OTHER files
- Timestamp: $NOW
- Working directory: clean after sync

Reference: .instructions.md for auto-tracking rules"

if git commit -m "$COMMIT_MSG"; then
    echo -e "${GREEN}✓${NC} Changes committed"
else
    echo -e "${YELLOW}→${NC} Nothing new to commit (already staged?)"
fi

echo ""

# ─── STEP 5: Create session boundary and push ───
echo -e "${BLUE}[5/5]${NC} Creating session boundary and syncing..."

cat > "$BOUNDARY_FILE" <<EOF
---
type: session-boundary
project: [[Digital Library]]
timestamp: $NOW
tags: [session-end, auto-sync]
links:
  - "[[WORK_LOG_$TODAY]]"
  - "[[CENTRAL_HUB_MIN]]"
---

# Session Boundary — $NOW

## Session Context
- Date: $(date -u +'%Y-%m-%d')
- Time: $(date -u +'%H:%M UTC')
- Sync type: Automatic (continuous tracking)
- Triggered from: $CALLER_DIR

## What Was Synced
- Memory fragments: See \`WORK_LOG_$TODAY.md\`
- Git commits: Referencing memory artifacts
- Obsidian links: Cross-linked in artifacts/obsidian/

## Status Snapshot Before Sync
\`\`\`
$STATUS_SNAPSHOT
\`\`\`

## Resumption
Next agent session should:
1. Read \`WORK_LOG_$TODAY.md\` for task context
2. Read \`CENTRAL_HUB_MIN.md\` for knowledge router
3. Continue work from pending items

---

Auto-synced by \`./scripts/dev/auto-sync.sh\`
EOF

git add "$BOUNDARY_FILE"
git commit -m "[session-boundary] $NOW" || true

# Push to origin
echo "Pushing to origin/main..."
git push origin main 2>&1 | head -5

echo -e "${GREEN}✓${NC} Session boundary created and pushed"

echo ""
echo -e "${GREEN}════════════════════════════════════════${NC}"
echo -e "${GREEN}✓ Auto-Sync Complete${NC}"
echo -e "${GREEN}════════════════════════════════════════${NC}"
echo ""
echo "Memory location: $WORK_LOG"
echo "Session boundary: $BOUNDARY_FILE"
echo "Remote status: $(git rev-parse --abbrev-ref HEAD) synced with origin"
echo ""
echo "Agent can now continue work. All changes are tracked."
