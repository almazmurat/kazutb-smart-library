#!/usr/bin/env bash
set -euo pipefail

ROOT="$(git rev-parse --show-toplevel 2>/dev/null || pwd)"
VAULT_ROOT="$ROOT/kazutb-library-vault"
TASK_LOG="$VAULT_ROOT/02-memory/TASK_LOG.md"
CURRENT_STATE="$VAULT_ROOT/02-memory/CURRENT_STATE.md"
DECISIONS_FILE="$VAULT_ROOT/02-memory/DECISIONS.md"
GRAPH_SCRIPT="$VAULT_ROOT/scripts/rebuild_graph.ps1"

EVENT="${1:-post-commit}"
shift || true

if [[ ! -d "$VAULT_ROOT" ]]; then
  exit 0
fi

utc_day="$(date -u '+%Y-%m-%d')"
utc_stamp="$(date -u '+%Y-%m-%d %H:%M:%S UTC')"
branch_stamp="$(date '+%Y-%m-%d %H:%M')"
branch="$(git branch --show-current 2>/dev/null || echo detached)"
commit_hash="$(git rev-parse --short HEAD 2>/dev/null || echo none)"
commit_subject="$(git log -1 --pretty=format:'%s' 2>/dev/null || echo 'No commit message available')"
commit_subject_lower="$(printf '%s' "$commit_subject" | tr '[:upper:]' '[:lower:]')"
decision_keywords=()
for keyword in fix feat decision breaking auth rbac migration schema; do
  if [[ "$commit_subject_lower" =~ (^|[^a-z])${keyword}([^a-z]|$) ]]; then
    decision_keywords+=("$keyword")
  fi
done
decision_keyword_csv="$(IFS=,; echo "${decision_keywords[*]:-}")"

resolve_ref_name() {
  local ref="${1:-}"
  if [[ -z "$ref" ]]; then
    printf 'unknown'
    return 0
  fi

  local name
  name="$(git name-rev --name-only --exclude='tags/*' --exclude='remotes/*' "$ref" 2>/dev/null || true)"
  name="${name%%~*}"
  if [[ -n "$name" && "$name" != "undefined" ]]; then
    printf '%s' "$name"
  else
    printf '%.7s' "$ref"
  fi
}

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
mapfile -t changed_files < <(printf '%s\n' "$changed_files_raw" | sed '/^$/d')
preview_files=("${changed_files[@]:0:12}")

state_change_notes=()
for changed_file in "${changed_files[@]:-}"; do
  case "$changed_file" in
    database/migrations/*)
      state_change_notes+=("DB schema changed")
      ;;
    routes/*)
      state_change_notes+=("Routes changed")
      ;;
    app/Models/*)
      state_change_notes+=("Models changed")
      ;;
    app/Http/Controllers/*)
      state_change_notes+=("Controllers changed")
      ;;
    resources/views/*)
      state_change_notes+=("Views/Blade changed")
      ;;
  esac
done
if [[ ${#state_change_notes[@]} -gt 0 ]]; then
  state_change_csv="$(printf '%s\n' "${state_change_notes[@]}" | awk '!seen[$0]++' | paste -sd '|' -)"
else
  state_change_csv=""
fi

if [[ ${#changed_files[@]} -eq 0 ]]; then
  changed_preview="no file changes detected"
else
  changed_preview="$(printf '%s, ' "${preview_files[@]}")"
  changed_preview="${changed_preview%, }"
fi

prev_ref="${1:-}"
new_ref="${2:-}"
checkout_flag="${3:-0}"
from_branch="$(resolve_ref_name "$prev_ref")"
to_branch="$branch"
if [[ "$EVENT" == "post-checkout" ]]; then
  previous_symbolic="$(git rev-parse --abbrev-ref '@{-1}' 2>/dev/null || true)"
  if [[ -n "$previous_symbolic" && "$previous_symbolic" != '@{-1}' ]]; then
    from_branch="$previous_symbolic"
  fi
  reflog_line="$(git reflog -1 --pretty=%gs 2>/dev/null || true)"
  if [[ "$reflog_line" =~ moving\ from\ (.+)\ to\ (.+)$ ]]; then
    from_branch="${BASH_REMATCH[1]}"
    to_branch="${BASH_REMATCH[2]}"
  elif [[ "$checkout_flag" != "1" ]]; then
    to_branch="$(resolve_ref_name "$new_ref")"
  fi
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
    done_text="Branch switch"
    left_text="From: $from_branch To: $to_branch"
    ;;
  *)
    done_text="Git $EVENT on $branch"
    left_text="Changed files: $changed_preview"
    ;;
 esac

python3 - "$TASK_LOG" "$CURRENT_STATE" "$DECISIONS_FILE" "$utc_day" "$utc_stamp" "$branch_stamp" "$EVENT" "$branch" "$commit_hash" "$done_text" "$left_text" "$commit_subject" "$decision_keyword_csv" "$state_change_csv" "$from_branch" "$to_branch" <<'PY'
from pathlib import Path
import re
import sys

task_log = Path(sys.argv[1])
current_state = Path(sys.argv[2])
decisions_file = Path(sys.argv[3])
day = sys.argv[4]
stamp = sys.argv[5]
branch_stamp = sys.argv[6]
event = sys.argv[7]
branch = sys.argv[8]
commit_hash = sys.argv[9]
done_text = sys.argv[10]
left_text = sys.argv[11]
commit_subject = sys.argv[12]
decision_keywords = [item for item in sys.argv[13].split(',') if item]
state_changes = [item for item in sys.argv[14].split('|') if item]
from_branch = sys.argv[15]
to_branch = sys.argv[16]

if event == 'post-checkout':
    log_entry = f"[{branch_stamp}] Branch switch\nFrom: {from_branch} To: {to_branch}"
else:
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

existing_last_changed = ''
match = re.search(r"\n## Last changed\n.*?(?=\n## |\Z)", state, flags=re.S)
if match:
    existing_last_changed = match.group(0).strip('\n') + "\n\n"

last_changed_block = existing_last_changed
if state_changes:
    bullets = '\n'.join(f'- {item}' for item in state_changes)
    last_changed_block = (
        "## Last changed\n"
        f"- Time: {stamp}\n"
        f"- Commit: {commit_hash}\n"
        f"- Branch: {branch}\n"
        f"{bullets}\n\n"
    )

block = (
    last_changed_block
    + "## Latest Git Automation\n"
    + f"- Time: {stamp}\n"
    + f"- Event: {event}\n"
    + f"- Branch: {branch}\n"
    + f"- Commit: {commit_hash}\n"
    + f"- Update: {done_text}\n"
    + f"- Detail: {left_text}\n"
    + "- Links: [[TASK_LOG]], [[GRAPH_INDEX]]\n"
)

state = re.sub(r"\n## Last changed\n.*?(?=\n## |\Z)", "", state, flags=re.S)
state = re.sub(r"\n## Latest Git Automation\n.*?(?=\n## |\Z)", "", state, flags=re.S)

insertion = "\n\n" + block.rstrip() + "\n\n"
if "\n> Last updated:" in state:
    head, rest = state.split("\n", 2)[:2], state.split("\n", 2)[2] if len(state.split("\n", 2)) > 2 else ""
    state = head[0] + "\n" + head[1] + insertion + rest.lstrip("\n")
else:
    first_newline = state.find("\n")
    if first_newline != -1:
        state = state[:first_newline] + insertion + state[first_newline:].lstrip("\n")
    else:
        state = state + insertion

current_state.write_text(state.rstrip() + "\n", encoding='utf-8')

if event == 'post-commit' and decision_keywords:
    if decisions_file.exists():
        decisions_text = decisions_file.read_text(encoding='utf-8')
    else:
        decisions_text = "# Decision Log — KazUTB Library Platform\n\n## Links\n- [[PROJECT_CONTEXT]]\n- [[CURRENT_STATE]]\n- [[OPEN_QUESTIONS]]\n"

    source_marker = f"**Source:** Git hook auto-capture from commit {commit_hash}"
    if source_marker not in decisions_text:
        keyword_text = ', '.join(decision_keywords)
        decision_block = (
            f"## {day} — Git-derived decision signal: {commit_subject}\n"
            f"**Decision:** The commit message matched strategic keywords: {keyword_text}.\n"
            f"**Reason:** The change was auto-captured from Git history to preserve important implementation context in the second brain.\n"
            f"**Alternatives considered:** Not captured automatically by the hook.\n"
            f"**Impact:** {left_text}\n"
            f"**Source:** Git hook auto-capture from commit {commit_hash}\n\n"
            "---\n"
        )

        links_marker = "\n## Links"
        if links_marker in decisions_text:
            decisions_text = decisions_text.replace(links_marker, "\n\n" + decision_block + links_marker, 1)
        else:
            decisions_text = decisions_text.rstrip() + "\n\n" + decision_block + "\n"

        decisions_file.write_text(decisions_text, encoding='utf-8')
PY

if command -v pwsh >/dev/null 2>&1 && [[ -f "$GRAPH_SCRIPT" ]]; then
  pwsh -File "$GRAPH_SCRIPT" >/dev/null 2>&1 || true
fi

case "$EVENT" in
  post-commit)
    printf '📚 Vault reminder: important decisions → run: ./kazutb-library-vault/scripts/log_decision.ps1\n'
    printf 'Session end? → run: ./kazutb-library-vault/scripts/end_session.ps1 "summary"\n'
    ;;
  post-checkout)
    printf '📚 KazUTB Vault: You switched to branch %s → Check CURRENT_STATE: kazutb-library-vault/02-memory/CURRENT_STATE.md\n' "$to_branch"
    ;;
esac

exit 0
