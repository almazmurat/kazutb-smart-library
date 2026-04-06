#!/usr/bin/env bash
# export-context-to-vault.sh — mirrors high-value repo context to Obsidian vault
#
# Selection criteria for mirrored files:
#   - Agent startup truth (mandatory reads)
#   - Project context layer (00-06, 98)
#   - Canonical developer reference docs
#   - Runtime verification matrix
# NOT mirrored: archive, prompts, scripts, code, tests, process docs.
# Vault is synthesis/mirror only — if vault disagrees with repo, repo wins.
#
# Usage: bash scripts/dev/export-context-to-vault.sh [--dry-run]
set -euo pipefail

DRY_RUN=false
[[ "${1:-}" == "--dry-run" ]] && DRY_RUN=true

REPO_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
VAULT_DIR="${VAULT_DIR:-/home/admlibrary/knowledge/kazutb-library-vault}"
MIRROR_DIR="$VAULT_DIR/09-reference-mirrors/repo-context"

# High-value canonical files only — no process/archive noise
FILES=(
  # Agent startup truth
  "AGENT_START_HERE.md"
  "README.md"
  # Project context layer
  "project-context/00-project-truth.md"
  "project-context/01-current-stage.md"
  "project-context/02-active-roadmap.md"
  "project-context/03-api-contracts.md"
  "project-context/04-known-risks.md"
  "project-context/05-agent-working-rules.md"
  "project-context/06-current-focus.md"
  "project-context/98-product-master-context.md"
  # Canonical developer references
  "docs/developer/REPO_NORMALIZATION_PLAN.md"
  "docs/developer/RUNTIME_VERIFICATION_MATRIX.md"
  "docs/developer/PUBLIC_CATALOG_CONVERGENCE_AUDIT.md"
)

if $DRY_RUN; then
  echo "== DRY RUN — no files will be copied =="
fi

copied=0
skipped=0
for rel in "${FILES[@]}"; do
  src="$REPO_DIR/$rel"
  dst="$MIRROR_DIR/$rel"
  if [[ -f "$src" ]]; then
    if $DRY_RUN; then
      echo "[dry] would mirror $rel"
    else
      mkdir -p "$(dirname "$dst")"
      cp "$src" "$dst"
      echo "[ok] mirrored $rel"
    fi
    copied=$((copied + 1))
  else
    echo "[warn] missing in repo, skipped: $rel"
    skipped=$((skipped + 1))
  fi
done

echo ""
echo "Mirror ${DRY_RUN:+would be }complete: $copied files → $MIRROR_DIR"
if [[ $skipped -gt 0 ]]; then
  echo "  ($skipped files skipped — missing in repo)"
fi
