#!/usr/bin/env bash
set -euo pipefail

REPO_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
VAULT_DIR="${VAULT_DIR:-/home/admlibrary/knowledge/kazutb-library-vault}"
MIRROR_DIR="$VAULT_DIR/09-reference-mirrors/repo-context"

mkdir -p "$MIRROR_DIR"

FILES=(
  "AGENT_START_HERE.md"
  "README.md"
  "project-context/00-project-truth.md"
  "project-context/01-current-stage.md"
  "project-context/02-active-roadmap.md"
  "project-context/03-api-contracts.md"
  "project-context/04-known-risks.md"
  "project-context/05-agent-working-rules.md"
  "project-context/06-current-focus.md"
  "project-context/98-product-master-context.md"
  "docs/developer/REPO_NORMALIZATION_PLAN.md"
  "docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md"
  "docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md"
  "docs/developer/AGENT_AUTOMATION_WORKFLOW.md"
)

for rel in "${FILES[@]}"; do
  src="$REPO_DIR/$rel"
  dst="$MIRROR_DIR/$rel"
  if [[ -f "$src" ]]; then
    mkdir -p "$(dirname "$dst")"
    cp "$src" "$dst"
    echo "[ok] mirrored $rel"
  else
    echo "[warn] missing in repo, skipped: $rel"
  fi
done

echo "Mirror complete: $MIRROR_DIR"
