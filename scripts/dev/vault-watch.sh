#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
INTERVAL="${OBSIDIAN_SYNC_INTERVAL:-10}"

printf 'Watching Copilot transcript updates and repository changes every %ss\n' "$INTERVAL"

while true; do
  bash "$ROOT/scripts/dev/vault-sync.sh" --trigger watcher >/dev/null 2>&1 || true
  sleep "$INTERVAL"
done
