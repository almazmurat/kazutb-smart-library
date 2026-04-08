#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$ROOT_DIR"

mkdir -p .githooks
chmod +x .githooks/post-commit 2>/dev/null || true
git config core.hooksPath .githooks

echo "Git hooks configured: core.hooksPath=.githooks"
echo "Post-commit snapshots will now write to the Obsidian vault."
