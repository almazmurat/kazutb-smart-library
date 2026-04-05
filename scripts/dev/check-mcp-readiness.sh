#!/usr/bin/env bash
set -euo pipefail

check_cmd() {
  local name="$1"
  if command -v "$name" >/dev/null 2>&1; then
    echo "[ok] $name"
  else
    echo "[missing] $name"
  fi
}

echo "== MCP baseline readiness =="
check_cmd copilot
check_cmd node
check_cmd npx

echo
echo "== Versions (if available) =="
command -v copilot >/dev/null 2>&1 && copilot --version || true
command -v node >/dev/null 2>&1 && node -v || true
command -v npm >/dev/null 2>&1 && npm -v || true

echo
echo "== GitHub MCP baseline =="
echo "[info] In Copilot CLI interactive mode, run: /mcp list"
echo "[info] Keep GitHub MCP enabled as baseline."

echo
echo "== Optional Context7 check =="
if command -v npx >/dev/null 2>&1; then
  if npx ctx7 --help >/dev/null 2>&1; then
    echo "[ok] ctx7 entrypoint available"
  else
    echo "[warn] ctx7 unavailable. If Node is below 20, upgrade Node and retry."
  fi
else
  echo "[warn] npx unavailable; cannot validate ctx7"
fi

echo
echo "== Deferred by policy in this phase =="
echo "- Browser MCP"
echo "- UI generation MCP (21st Magic or equivalent)"
echo "- Additional repo-local MCP server configs"
