#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT_DIR"

OUT_DIR="${1:-artifacts/obsidian/memory-fragments}"

python3 obsidian_brain_sync.py --fragments-only --out-dir "$OUT_DIR"

echo "Memory fragments generated in: $OUT_DIR"
echo "Use: $OUT_DIR/LATEST_FRAGMENT.md"
