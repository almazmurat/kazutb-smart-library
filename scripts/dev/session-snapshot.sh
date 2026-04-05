#!/usr/bin/env bash
set -euo pipefail

REPO_DIR="/home/admlibrary/kazutb-smart-library-main"
VAULT_DIR="/home/admlibrary/knowledge/kazutb-library-vault"
TODAY="$(date +%F)"
OUT="$VAULT_DIR/09-daily-notes/${TODAY}-snapshot.md"

mkdir -p "$VAULT_DIR/09-daily-notes"

{
  echo "# Session Snapshot - $TODAY"
  echo
  echo "## Git status"
  git -C "$REPO_DIR" status --short || true
  echo
  echo "## Recent commits"
  git -C "$REPO_DIR" --no-pager log --oneline -5 || true
  echo
  echo "## Modified files"
  git -C "$REPO_DIR" diff --name-only || true
} > "$OUT"

echo "Snapshot written to $OUT"