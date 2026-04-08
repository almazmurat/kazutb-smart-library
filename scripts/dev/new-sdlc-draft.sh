#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
SDLC_DIR="$ROOT_DIR/docs/sdlc"
CURRENT_DIR="$SDLC_DIR/current"
TARGET_FILE="$CURRENT_DIR/draft.md"
VAULT_ARCHIVE_DIR="/home/admlibrary/knowledge/kazutb-library-vault/10-archive/sdlc-history"
DRY_RUN=false
DRAFT_TYPE="feature"

usage() {
  cat <<'EOF'
Usage:
  bash scripts/dev/new-sdlc-draft.sh [--dry-run] [--type feature|context-update] "улучшить фильтр каталога"

Modes:
- feature (default): prepares a normal implementation draft and points you to `/clarify`
- context-update: prepares a raw project-memory note and points you to `/remember`

What it does:
- archives the previous `docs/sdlc/current/` trace to Obsidian if one exists
- refreshes `docs/sdlc/current/draft.md`
- leaves the repo ready for the next Copilot slash command
EOF
}

slugify() {
  python3 - "$1" <<'PY'
from __future__ import annotations
import datetime as dt
import re
import sys

text = sys.argv[1].strip().lower()
translit = {
    'а':'a','ә':'a','б':'b','в':'v','г':'g','ғ':'g','д':'d','е':'e','ё':'e','ж':'zh','з':'z','и':'i','й':'i',
    'к':'k','қ':'k','л':'l','м':'m','н':'n','ң':'n','о':'o','ө':'o','п':'p','р':'r','с':'s','т':'t','у':'u',
    'ұ':'u','ү':'u','ф':'f','х':'h','һ':'h','ц':'ts','ч':'ch','ш':'sh','щ':'sh','ы':'y','і':'i','э':'e','ю':'yu','я':'ya',
    'ь':'','ъ':''
}
slug = ''.join(translit.get(ch, ch) for ch in text)
slug = re.sub(r'[^a-z0-9]+', '-', slug).strip('-')
if not slug:
    slug = f"task-{dt.datetime.now():%Y%m%d-%H%M%S}"
print(slug)
PY
}

current_archive_slug() {
  local draft_file="$CURRENT_DIR/draft.md"
  if [[ -f "$draft_file" ]]; then
    local idea
    idea="$(awk '/^## (Idea|Raw note)/{flag=1; next} flag && /^- / {sub(/^- /, ""); print; exit}' "$draft_file")"
    if [[ -n "${idea:-}" ]]; then
      slugify "$idea"
      return 0
    fi
  fi
  echo "current-task"
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --help|-h)
      usage
      exit 0
      ;;
    --dry-run)
      DRY_RUN=true
      shift
      ;;
    --type)
      DRAFT_TYPE="${2:-}"
      shift 2
      ;;
    --)
      shift
      break
      ;;
    *)
      break
      ;;
  esac
done

case "$DRAFT_TYPE" in
  feature|context-update)
    ;;
  *)
    echo "Unknown draft type: $DRAFT_TYPE" >&2
    usage >&2
    exit 1
    ;;
esac

TITLE="${*:-}"
if [[ -z "$TITLE" ]]; then
  usage >&2
  exit 1
fi

NEXT_COMMAND="/clarify"
if [[ "$DRAFT_TYPE" == "context-update" ]]; then
  NEXT_COMMAND="/remember"
  CONTENT=$(cat <<EOF
# draft.md

## Type
- context-update

## Raw note
- $TITLE

## Optional notes
- why it matters:
- affected areas:
- examples:
EOF
)
else
  CONTENT=$(cat <<EOF
# draft.md

## Idea
- $TITLE

## Optional notes
- pain:
- desired result:
- constraints:
EOF
)
fi

HAS_CURRENT=false
ARCHIVE_PATH=""
if [[ -d "$CURRENT_DIR" ]] && find "$CURRENT_DIR" -mindepth 1 -maxdepth 1 -print -quit | grep -q .; then
  HAS_CURRENT=true
  ARCHIVE_PATH="$VAULT_ARCHIVE_DIR/$(date +%Y%m%d-%H%M%S)-$(current_archive_slug)"
fi

if [[ "$DRY_RUN" == true ]]; then
  if [[ "$HAS_CURRENT" == true ]]; then
    echo "Would archive current trace to: $ARCHIVE_PATH"
  fi
  echo "Would write: $TARGET_FILE"
  echo
  echo "$CONTENT"
  exit 0
fi

mkdir -p "$CURRENT_DIR"
if [[ "$HAS_CURRENT" == true ]]; then
  mkdir -p "$ARCHIVE_PATH"
  cp -a "$CURRENT_DIR"/. "$ARCHIVE_PATH"/
  find "$CURRENT_DIR" -mindepth 1 -maxdepth 1 -exec rm -rf {} +
fi

printf '%s
' "$CONTENT" > "$TARGET_FILE"

if [[ "$HAS_CURRENT" == true ]]; then
  echo "Archived previous current trace to: $ARCHIVE_PATH"
fi
echo "Ready: $TARGET_FILE"
echo "Next step: run $NEXT_COMMAND"
