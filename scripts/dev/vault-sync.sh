#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
TRIGGER="manual"
FORCE=""
VAULT_OVERRIDE="${OBSIDIAN_VAULT_ROOT:-}"
TRANSCRIPT_OVERRIDE="${COPILOT_TRANSCRIPT_FILE:-}"

normalize_path() {
  local input="$1"

  if [[ -z "$input" ]]; then
    return 0
  fi

  input="${input//\\//}"

  if [[ "$input" =~ ^([A-Za-z]):/(.*)$ ]]; then
    local drive="${BASH_REMATCH[1],,}"
    local rest="${BASH_REMATCH[2]}"
    printf '/mnt/%s/%s' "$drive" "$rest"
    return 0
  fi

  printf '%s' "$input"
}

resolve_vault_root() {
  local env_path="${VAULT_OVERRIDE:-}"
  if [[ -n "$env_path" ]]; then
    normalize_path "$env_path"
    return 0
  fi

  local fallback="$ROOT/artifacts/obsidian/vault-mirror"
  local -a candidates=(
    "/mnt/y/kazutb-library-vault"
    "/mnt/Y/kazutb-library-vault"
    "$HOME/kazutb-library-vault"
    "$fallback"
  )

  for candidate in "${candidates[@]}"; do
    if [[ -d "$candidate" || "$candidate" == "$fallback" ]]; then
      printf '%s' "$candidate"
      return 0
    fi
  done

  printf '%s' "$fallback"
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --trigger)
      TRIGGER="${2:-manual}"
      shift 2
      ;;
    --vault-root)
      VAULT_OVERRIDE="${2:-}"
      shift 2
      ;;
    --transcript)
      TRANSCRIPT_OVERRIDE="${2:-}"
      shift 2
      ;;
    --force)
      FORCE="--force"
      shift
      ;;
    *)
      shift
      ;;
  esac
done

VAULT_ROOT="$(resolve_vault_root)"
mkdir -p "$VAULT_ROOT"

cmd=(
  php "$ROOT/scripts/dev/vault-sync.php"
  "--root=$ROOT"
  "--vault=$VAULT_ROOT"
  "--trigger=$TRIGGER"
)

if [[ -n "$TRANSCRIPT_OVERRIDE" ]]; then
  cmd+=("--transcript=$TRANSCRIPT_OVERRIDE")
fi

if [[ -n "$FORCE" ]]; then
  cmd+=("$FORCE")
fi

"${cmd[@]}"
