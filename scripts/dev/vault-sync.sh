#!/usr/bin/env bash
set -euo pipefail

REPO_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
VAULT_DIR="${VAULT_DIR:-/home/admlibrary/knowledge/kazutb-library-vault}"
TODAY="$(date +%F)"
NOW_UTC="$(date -u +"%Y-%m-%dT%H:%M:%SZ")"
DAILY_NOTE="$VAULT_DIR/09-daily-notes/$TODAY.md"

mkdir -p "$VAULT_DIR/09-daily-notes"

if [[ ! -f "$DAILY_NOTE" ]]; then
  cat > "$DAILY_NOTE" <<EOF
# $TODAY

## What was done
-

## Important findings
-

## Problems / blockers
-

## Commits
-

## Next likely step
-
EOF
fi

STATUS_SHORT="$(git -C "$REPO_DIR" status --short || true)"
LAST_COMMITS="$(git -C "$REPO_DIR" --no-pager log --oneline -5 || true)"
CHANGED_FILES="$(git -C "$REPO_DIR" diff --name-only || true)"
BRANCH="$(git -C "$REPO_DIR" rev-parse --abbrev-ref HEAD || echo unknown)"

{
  echo
  echo "---"
  echo
  echo "### Session sync ($NOW_UTC)"
  echo "- Branch: $BRANCH"
  echo "- Repo: $REPO_DIR"
  echo
  echo "#### Git status (short)"
  if [[ -n "$STATUS_SHORT" ]]; then
    echo '```text'
    echo "$STATUS_SHORT"
    echo '```'
  else
    echo "- clean working tree"
  fi
  echo
  echo "#### Recent commits"
  if [[ -n "$LAST_COMMITS" ]]; then
    echo '```text'
    echo "$LAST_COMMITS"
    echo '```'
  else
    echo "- no commits found"
  fi
  echo
  echo "#### Changed files (unstaged diff)"
  if [[ -n "$CHANGED_FILES" ]]; then
    echo '```text'
    echo "$CHANGED_FILES"
    echo '```'
  else
    echo "- no unstaged file diffs"
  fi
} >> "$DAILY_NOTE"

echo "Vault daily note synced: $DAILY_NOTE"
