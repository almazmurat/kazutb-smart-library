#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
HOOK_DIR="$ROOT/.git/hooks"
HELPER="$ROOT/scripts/dev/git-vault-hook.sh"

mkdir -p "$HOOK_DIR"
chmod +x "$HELPER"

write_hook() {
  local hook_name="$1"

  cat > "$HOOK_DIR/$hook_name" <<EOF
#!/usr/bin/env bash
set -euo pipefail
ROOT="\$(git rev-parse --show-toplevel 2>/dev/null || pwd)"
bash "\$ROOT/scripts/dev/git-vault-hook.sh" "$hook_name" "\$@" >/dev/null 2>&1 || true
EOF

  chmod +x "$HOOK_DIR/$hook_name"
}

write_hook post-commit
write_hook post-merge
write_hook post-checkout

printf 'Installed KazUTB vault git hooks into %s\n' "$HOOK_DIR"
