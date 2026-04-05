#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/../.."

PASS=true

must_exist() {
  local path="$1"
  if [[ -f "$path" ]]; then
    echo "[ok] $path"
  else
    echo "[error] Missing required file: $path"
    PASS=false
  fi
}

warn_if_exists() {
  local path="$1"
  local message="$2"
  if [[ -e "$path" ]]; then
    echo "[warn] $message ($path)"
  fi
}

must_contain() {
  local needle="$1"
  local path="$2"
  local label="$3"
  if grep -Fq -- "$needle" "$path"; then
    echo "[ok] $label"
  else
    echo "[error] Missing expected content: $label"
    PASS=false
  fi
}

echo "== Canonical repo truth checks =="
must_exist "AGENT_START_HERE.md"
must_exist "project-context/00-project-truth.md"
must_exist "project-context/01-current-stage.md"
must_exist "project-context/02-active-roadmap.md"
must_exist "project-context/03-api-contracts.md"
must_exist "project-context/04-known-risks.md"
must_exist "project-context/05-agent-working-rules.md"
must_exist "project-context/06-current-focus.md"
must_exist "project-context/98-product-master-context.md"
must_exist ".github/copilot-instructions.md"
must_exist "docs/developer/REPO_NORMALIZATION_PLAN.md"
must_exist "docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md"
must_exist "docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md"
must_exist "docs/developer/AGENT_AUTOMATION_WORKFLOW.md"
must_exist "prompts/next-step.md"

echo
echo "== Startup flow checks =="
must_exist "scripts/dev/show-startup-context.sh"
must_exist "scripts/dev/session-start-checklist.sh"
must_exist "scripts/dev/session-closeout.sh"
must_exist "scripts/dev/export-context-to-vault.sh"
must_exist "scripts/dev/sync-vault-index.sh"

must_contain "project-context/00-project-truth.md" "AGENT_START_HERE.md" "startup includes project truth"
must_contain "project-context/06-current-focus.md" "AGENT_START_HERE.md" "startup includes current focus"
must_contain "Repository files remain the operational source of truth" "docs/developer/AI_WORKFLOW.md" "repo-truth boundary in workflow doc"
must_contain "Do not treat Obsidian as execution truth" "prompts/next-step.md" "next-step prompt boundary rule"

echo
echo "== Legacy/transitional context warnings =="
warn_if_exists "project-context/99-master-project-context.md" "Legacy context file still present; keep archived only"
warn_if_exists "docs/PHASED_DEVELOPMENT_ROADMAP.md" "Legacy roadmap file present outside archive"
warn_if_exists "docs/99-master-project-context.md" "Legacy master context present outside archive"

echo
if [[ "$PASS" == true ]]; then
  echo "Result: PASS (core context and automation layer look consistent)."
  exit 0
fi

echo "Result: FAIL (context drift or missing canonical files detected)."
exit 1
