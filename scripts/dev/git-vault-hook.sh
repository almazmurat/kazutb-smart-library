#!/usr/bin/env bash
set -euo pipefail

ROOT="$(git rev-parse --show-toplevel 2>/dev/null || pwd)"
VAULT_ROOT="$ROOT/kazutb-library-vault"
TASK_LOG="$VAULT_ROOT/02-memory/TASK_LOG.md"
CURRENT_STATE="$VAULT_ROOT/02-memory/CURRENT_STATE.md"
GRAPH_SCRIPT="$VAULT_ROOT/scripts/rebuild_graph.ps1"

EVENT="${1:-post-commit}"
shift || true

if [[ ! -d "$VAULT_ROOT" ]]; then
  exit 0
fi

utc_day="$(date -u '+%Y-%m-%d')"
utc_stamp="$(date -u '+%Y-%m-%d %H:%M:%S UTC')"
branch="$(git branch --show-current 2>/dev/null || echo detached)"
commit_hash="$(git rev-parse --short HEAD 2>/dev/null || echo none)"
commit_subject="$(git log -1 --pretty=format:'%s' 2>/dev/null || echo 'No commit message available')"

collect_changed_files() {
  case "$EVENT" in
    post-commit)
      git diff-tree --no-commit-id -r --name-only HEAD 2>/dev/null || true
      ;;
    post-merge)
      if git rev-parse --verify ORIG_HEAD >/dev/null 2>&1; then
        git diff --name-only ORIG_HEAD HEAD 2>/dev/null || true
      else
        git diff-tree --no-commit-id -r --name-only HEAD 2>/dev/null || true
      fi
      ;;
    post-checkout)
      local prev_ref="${1:-}"
      local new_ref="${2:-}"
      if [[ -n "$prev_ref" && -n "$new_ref" ]]; then
        git diff --name-only "$prev_ref" "$new_ref" 2>/dev/null || true
      else
        git diff-tree --no-commit-id -r --name-only HEAD 2>/dev/null || true
      fi
      ;;
    *)
      git diff-tree --no-commit-id -r --name-only HEAD 2>/dev/null || true
      ;;
  esac
}

changed_files_raw="$(collect_changed_files "$@")"
mapfile -t changed_files < <(printf '%s\n' "$changed_files_raw" | sed '/^$/d' | head -n 12)

if [[ ${#changed_files[@]} -eq 0 ]]; then
  changed_preview="no file changes detected"
else
  changed_preview="$(printf '%s, ' "${changed_files[@]}")"
  changed_preview="${changed_preview%, }"
fi

case "$EVENT" in
  post-commit)
    done_text="Git post-commit on $branch: $commit_subject"
    left_text="Changed files: $changed_preview"
    ;;
  post-merge)
    done_text="Git post-merge on $branch: $commit_subject"
    left_text="Merged files: $changed_preview"
    ;;
  post-checkout)
    checkout_type="path checkout"
    if [[ "${3:-0}" == "1" ]]; then
      checkout_type="branch checkout"
    fi
    done_text="Git post-checkout on $branch: $checkout_type"
    left_text="Changed context: $changed_preview"
    ;;
  *)
    done_text="Git $EVENT on $branch"
    left_text="Changed files: $changed_preview"
    ;;
 esac

python3 - "$TASK_LOG" "$CURRENT_STATE" "$utc_day" "$utc_stamp" "$EVENT" "$branch" "$commit_hash" "$done_text" "$left_text" <<'PY'
from pathlib import Path
import re
import sys

task_log = Path(sys.argv[1])
current_state = Path(sys.argv[2])
day = sys.argv[3]
stamp = sys.argv[4]
event = sys.argv[5]
branch = sys.argv[6]
commit_hash = sys.argv[7]
done_text = sys.argv[8]
left_text = sys.argv[9]

log_entry = f"{day} | {done_text} | {left_text}"

if task_log.exists():
    text = task_log.read_text(encoding='utf-8')
else:
    text = "# Task Log — KazUTB Library Platform\n\n> One-line entries per session. Newest at top.\n> Format: YYYY-MM-DD | What was done | What was left\n\n---\n\n## Links\n- [[CURRENT_STATE]]\n- [[DECISIONS]]\n"

if log_entry not in text:
    marker = "---"
    if marker in text:
        head, tail = text.split(marker, 1)
        text = f"{head}{marker}\n\n{log_entry}\n" + tail.lstrip("\n")
    else:
        text += "\n\n" + log_entry + "\n"
    task_log.write_text(text, encoding='utf-8')

if current_state.exists():
    state = current_state.read_text(encoding='utf-8')
else:
    state = "# Current State — KazUTB Library Platform\n\n## Links\n- [[PROJECT_CONTEXT]]\n- [[TASK_LOG]]\n"

block = (
    "## Latest Git Automation\n"
    f"- Time: {stamp}\n"
    f"- Event: {event}\n"
    f"- Branch: {branch}\n"
    f"- Commit: {commit_hash}\n"
    f"- Update: {done_text}\n"
    f"- Detail: {left_text}\n"
    "- Links: [[TASK_LOG]], [[GRAPH_INDEX]]\n"
)

pattern = re.compile(r"## Latest Git Automation\n.*?(?=\n## |\Z)", re.S)
if pattern.search(state):
    state = pattern.sub(block + "\n", state, count=1)
else:
    links_marker = "\n## Links"
    if links_marker in state:
        state = state.replace(links_marker, "\n\n" + block + links_marker, 1)
    else:
        state = state.rstrip() + "\n\n" + block + "\n"

current_state.write_text(state, encoding='utf-8')
PY

if command -v pwsh >/dev/null 2>&1 && [[ -f "$GRAPH_SCRIPT" ]]; then
  pwsh -File "$GRAPH_SCRIPT" >/dev/null 2>&1 || true
fi

exit 0
