#!/usr/bin/env bash
# scripts/dev/show-startup-context.sh
# Prints the canonical agent startup context map.
# Run before starting a new session to know what to read.

set -e

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

echo ""
echo "╔══════════════════════════════════════════════════════════════╗"
echo "║         KazUTB Smart Library — Agent Startup Context         ║"
echo "╚══════════════════════════════════════════════════════════════╝"
echo ""

echo "── LAYER 1: Always read (policy / truth) ──────────────────────"
files=(
  "AGENT_START_HERE.md"
  "project-context/00-project-truth.md"
  "project-context/01-current-stage.md"
  "project-context/06-current-focus.md"
  "project-context/05-agent-working-rules.md"
)
for f in "${files[@]}"; do
  full="$REPO_ROOT/$f"
  if [ -f "$full" ]; then
    size=$(wc -l < "$full")
    modified=$(date -r "$full" '+%Y-%m-%d')
    printf "  ✅ %-52s %s  (%d lines)\n" "$f" "$modified" "$size"
  else
    printf "  ❌ %-52s NOT FOUND\n" "$f"
  fi
done

echo ""
echo "── LAYER 1: Read on planning/architecture tasks ────────────────"
files=(
  "project-context/02-active-roadmap.md"
  "project-context/03-api-contracts.md"
  "project-context/04-known-risks.md"
  "project-context/98-product-master-context.md"
)
for f in "${files[@]}"; do
  full="$REPO_ROOT/$f"
  if [ -f "$full" ]; then
    size=$(wc -l < "$full")
    modified=$(date -r "$full" '+%Y-%m-%d')
    printf "  📋 %-52s %s  (%d lines)\n" "$f" "$modified" "$size"
  else
    printf "  ❌ %-52s NOT FOUND\n" "$f"
  fi
done

echo ""
echo "── LAYER 2: Execution / tooling ────────────────────────────────"
echo "  prompts/                  CLI task templates"
echo "  .github/prompts/          VS Code Copilot Chat adapters"
echo "  .github/instructions/     File-scoped Copilot rules"
echo "  scripts/dev/              Dev scripts (test-*, check-*, session-*)"
echo "  .github/workflows/ci.yml  CI pipeline (PHP tests + Pint)"

echo ""
echo "── LAYER 3: Reference only (do NOT use as truth) ───────────────"
echo "  docs/archive/             Historical phase records"
echo "  docs/developer/           Developer workflow docs"
echo "  Obsidian vault            Personal notes — not repo truth"

echo ""
echo "── Normalization plan ──────────────────────────────────────────"
echo "  docs/developer/REPO_NORMALIZATION_PLAN.md"

echo ""
echo "── CI status ───────────────────────────────────────────────────"
if command -v gh &>/dev/null; then
  gh run list --limit 3 --repo almazmurat/kazutb-smart-library 2>/dev/null || echo "  (gh CLI available but run list failed)"
else
  echo "  gh CLI not available — check GitHub Actions manually"
fi

echo ""
