#!/usr/bin/env bash
set -euo pipefail

# Installs global shell aliases/functions for Obsidian-first workflow.

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
BASHRC="$HOME/.bashrc"
MARK_START="# >>> kazutb-obsidian-aliases >>>"
MARK_END="# <<< kazutb-obsidian-aliases <<<"

BLOCK=$(cat <<EOF
${MARK_START}
# KazUTB Smart Library aliases
auto-sync() {
  bash "${ROOT_DIR}/scripts/dev/auto-sync.sh"
}
obs-bootstrap() {
  bash "${ROOT_DIR}/scripts/dev/obsidian-bootstrap.sh"
}
obs-capture() {
  if [[ \$# -lt 1 ]]; then
    echo "Usage: obs-capture \"request text\" [--entity NAME] [--from OLD] [--to NEW]" >&2
    return 1
  fi
  bash "${ROOT_DIR}/scripts/dev/obsidian-capture-request.sh" "\$@"
}
${MARK_END}
EOF
)

if [[ -f "$BASHRC" ]] && grep -q "$MARK_START" "$BASHRC"; then
  awk -v s="$MARK_START" -v e="$MARK_END" '
    $0==s {skip=1; next}
    $0==e {skip=0; next}
    !skip {print}
  ' "$BASHRC" > "$BASHRC.tmp"
  mv "$BASHRC.tmp" "$BASHRC"
fi

{
  echo ""
  echo "$BLOCK"
} >> "$BASHRC"

echo "Aliases installed in $BASHRC"
echo "Run: source ~/.bashrc"
