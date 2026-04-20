#!/usr/bin/env bash
set -euo pipefail

MSG_FILE="${1:-}"
COMMIT_SOURCE="${2:-}"

if [[ -z "$MSG_FILE" || ! -f "$MSG_FILE" ]]; then
  exit 0
fi

case "$COMMIT_SOURCE" in
  merge|squash|commit|message)
    exit 0
    ;;
esac

REMINDER_FILE_CONTENT='# [KazUTB vault reminder]
# - Use meaningful commit prefixes when relevant: feat, fix, auth, rbac, migration, schema, decision, breaking
# - Important commit keywords are mirrored into the Obsidian second brain automatically
# - Keep the message concise and factual

'

current_content="$(cat "$MSG_FILE")"
if [[ "$current_content" == *"# [KazUTB vault reminder]"* ]]; then
  exit 0
fi

printf '%s%s' "$REMINDER_FILE_CONTENT" "$current_content" > "$MSG_FILE"
