#!/usr/bin/env bash
set -euo pipefail

# Obsidian-First bootstrap for agent sessions
# Reads the latest memory artifacts so every session starts from the second brain.

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT_DIR"

MEMORY_DIR="artifacts/obsidian/memory-fragments"
TODAY=$(date -u +'%Y%m%d')
TODAY_LOG="$MEMORY_DIR/WORK_LOG_${TODAY}.md"

if [[ ! -d "$MEMORY_DIR" ]]; then
  echo "Memory directory not found: $MEMORY_DIR"
  exit 1
fi

echo "=== Obsidian Bootstrap ==="
echo "Project root: $ROOT_DIR"
echo "Memory dir:   $MEMORY_DIR"
echo "Timestamp:    $(date -u +'%Y-%m-%dT%H:%M:%SZ')"
echo ""

echo "--- CENTRAL HUB ---"
if [[ -f "$MEMORY_DIR/CENTRAL_HUB_MIN.md" ]]; then
  sed -n '1,140p' "$MEMORY_DIR/CENTRAL_HUB_MIN.md"
else
  echo "CENTRAL_HUB_MIN.md not found"
fi

echo ""
echo "--- TODAY WORK LOG ---"
if [[ -f "$TODAY_LOG" ]]; then
  sed -n '1,200p' "$TODAY_LOG"
else
  echo "No work log for today yet: $TODAY_LOG"
fi

echo ""
echo "--- LATEST MEMORY FILES ---"
ls -1t "$MEMORY_DIR" | head -10

echo ""
echo "Bootstrap complete: session context loaded from Obsidian memory."
