#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
HOOK_DIR="$ROOT/.git/hooks"
VAULT_ROOT="${OBSIDIAN_VAULT_ROOT:-${1:-}}"

mkdir -p "$HOOK_DIR"

write_hook() {
  local hook_name="$1"

  cat > "$HOOK_DIR/$hook_name" <<EOF
#!/usr/bin/env bash
ROOT="\$(git rev-parse --show-toplevel 2>/dev/null)"
export OBSIDIAN_VAULT_ROOT="${VAULT_ROOT}"
bash "\$ROOT/scripts/dev/vault-sync.sh" --trigger "$hook_name" >/dev/null 2>&1 || true
EOF

  chmod +x "$HOOK_DIR/$hook_name"
}

write_hook post-commit
write_hook post-merge
write_hook post-checkout

printf 'Installed Obsidian vault hooks into %s\n' "$HOOK_DIR"
