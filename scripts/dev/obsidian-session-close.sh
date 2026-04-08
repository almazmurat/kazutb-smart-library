#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
VAULT_DIR="${OBSIDIAN_VAULT_DIR:-/home/admlibrary/knowledge/kazutb-library-vault}"
TODAY="$(date +%F)"
NOW="$(date +'%F %H:%M')"
STAMP="$(date +'%Y%m%d-%H%M%S')"
TODAY_FILE="$VAULT_DIR/09-daily-notes/${TODAY}.md"
HANDOFF_FILE="$VAULT_DIR/11-handoffs/latest-agent-handoff.md"
TASK_LOG_DIR="$VAULT_DIR/11-handoffs/task-log"

summary="Session work completed."
verification="Not recorded."
next_step="Review the latest workstream note and choose the next concrete implementation step."
risks="None recorded."
mode="manual"

slugify() {
  python3 - "$1" <<'PY'
import re
import sys
text = sys.argv[1].strip().lower()
slug = re.sub(r'[^a-z0-9]+', '-', text).strip('-') or 'task'
print(slug[:80])
PY
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --summary)
      summary="$2"
      shift 2
      ;;
    --verification)
      verification="$2"
      shift 2
      ;;
    --next)
      next_step="$2"
      shift 2
      ;;
    --risks)
      risks="$2"
      shift 2
      ;;
    --mode)
      mode="$2"
      shift 2
      ;;
    *)
      echo "Unknown argument: $1" >&2
      exit 1
      ;;
  esac
done

mkdir -p "$VAULT_DIR/09-daily-notes" "$VAULT_DIR/11-handoffs" "$TASK_LOG_DIR"

map_status="skipped (generator not found)"
if [[ -f "$ROOT_DIR/scripts/dev/generate-obsidian-code-maps.sh" ]]; then
  if bash "$ROOT_DIR/scripts/dev/generate-obsidian-code-maps.sh" >/dev/null 2>&1; then
    map_status="refreshed from current repo paths"
  else
    map_status="refresh failed (run generator manually for details)"
  fi
fi

git_status="$(git -C "$ROOT_DIR" status --short 2>/dev/null | head -n 20 || true)"
changed_files="$(git -C "$ROOT_DIR" diff --name-only 2>/dev/null | head -n 40 || true)"
changed_count="$(git -C "$ROOT_DIR" diff --name-only 2>/dev/null | wc -l | tr -d ' ' || echo 0)"
last_commit="$(git -C "$ROOT_DIR" log -1 --pretty=format:'%h %s' 2>/dev/null || echo 'no commits yet')"
branch="$(git -C "$ROOT_DIR" rev-parse --abbrev-ref HEAD 2>/dev/null || echo unknown)"
task_slug="$(slugify "$summary")"
TASK_NOTE_BASENAME="${STAMP}-${task_slug}.md"
TASK_NOTE_FILE="$TASK_LOG_DIR/$TASK_NOTE_BASENAME"
TASK_NOTE_WIKI="${TASK_NOTE_BASENAME%.md}"

if [[ ! -f "$TODAY_FILE" ]]; then
  bash "$ROOT_DIR/scripts/dev/obsidian-session-start.sh" >/dev/null
fi

cat > "$TASK_NOTE_FILE" <<EOF
# Task log — ${NOW}

## Summary
- ${summary}

## Verification
- ${verification}

## Risks / regressions
- ${risks}

## Next step
- ${next_step}

## Repo snapshot
- branch: ${branch}
- last commit: ${last_commit}
- generated code maps: ${map_status}

## Read with
- [[../latest-agent-handoff]]
- [[../../00-index/read this first before any new agent session]]
- [[../../00-index/current-next-step]]
- [[../../08-workstreams/current-workstreams]]
- [[../../07-bugs-and-incidents/active problem register - runtime, data, and integration]]
EOF

{
  echo
  echo "## ${NOW} — session closeout (${mode})"
  echo "- branch: ${branch}"
  echo "- summary: ${summary}"
  echo "- verification: ${verification}"
  echo "- risks: ${risks}"
  echo "- next: ${next_step}"
  echo "- task log node: [[../11-handoffs/task-log/${TASK_NOTE_WIKI}]]"
  echo "- last commit: ${last_commit}"
  echo "- generated code maps: ${map_status}"
  echo "- changed files (up to 40 shown; total ${changed_count}):"
  if [[ -n "$changed_files" ]]; then
    while IFS= read -r line; do
      [[ -n "$line" ]] && echo "  - ${line}"
    done <<< "$changed_files"
  else
    echo "  - no unstaged/uncommitted file list captured"
  fi
  echo "- git status snapshot (up to 20 lines shown):"
  if [[ -n "$git_status" ]]; then
    while IFS= read -r line; do
      [[ -n "$line" ]] && echo "  - ${line}"
    done <<< "$git_status"
  else
    echo "  - working tree clean"
  fi
} >> "$TODAY_FILE"

cat > "$HANDOFF_FILE" <<EOF
# Latest agent handoff

## When
- ${NOW}

## Summary
- ${summary}

## Verification
- ${verification}

## Risks / regressions
- ${risks}

## Next step
- ${next_step}

## Task log node
- [[task-log/${TASK_NOTE_WIKI}]]

## Context to read first
- [[00-index/read this first before any new agent session]]
- [[00-index/root context graph - kazutb smart library]]
- [[00-index/current-next-step]]
- [[08-workstreams/current-workstreams]]
- [[07-bugs-and-incidents/active problem register - runtime, data, and integration]]
- [[09-daily-notes/${TODAY}]]
EOF

echo "Obsidian session memory updated:"
echo "- $TODAY_FILE"
echo "- $HANDOFF_FILE"
echo "- $TASK_NOTE_FILE"
echo "- generated maps: $map_status"
