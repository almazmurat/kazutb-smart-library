#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
HELPER="$ROOT/scripts/dev/git-vault-hook.sh"
HOOK_DIRS=("$ROOT/.git/hooks")

configured_path="$(git -C "$ROOT" config --get core.hooksPath || true)"
if [[ -n "$configured_path" ]]; then
  if [[ "$configured_path" = /* ]]; then
    HOOK_DIRS+=("$configured_path")
  else
    HOOK_DIRS+=("$ROOT/$configured_path")
  fi
fi

chmod +x "$HELPER"

write_hook() {
  local hook_dir="$1"
  local hook_name="$2"

  mkdir -p "$hook_dir"

  cat > "$hook_dir/$hook_name" <<EOF
#!/usr/bin/env bash
set -euo pipefail
ROOT="\$(git rev-parse --show-toplevel 2>/dev/null || pwd)"
bash "\$ROOT/scripts/dev/git-vault-hook.sh" "$hook_name" "\$@" >/dev/null 2>&1 || true
EOF

  chmod +x "$hook_dir/$hook_name"
}

for hook_dir in "${HOOK_DIRS[@]}"; do
  write_hook "$hook_dir" post-commit
  write_hook "$hook_dir" post-merge
  write_hook "$hook_dir" post-checkout
  printf 'Installed KazUTB vault git hooks into %s\n' "$hook_dir"
done
