#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"

bash "$ROOT_DIR/scripts/dev/session-snapshot.sh"
bash "$ROOT_DIR/scripts/dev/vault-sync.sh"
bash "$ROOT_DIR/scripts/dev/obsidian-session-close.sh" "$@"

echo
echo "Session closeout complete."
