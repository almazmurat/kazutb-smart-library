#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"

bash "$ROOT_DIR/scripts/dev/session-snapshot.sh"
bash "$ROOT_DIR/scripts/dev/vault-sync.sh"

echo
echo "Now run: Read @prompts/session-closeout.md and execute it."
