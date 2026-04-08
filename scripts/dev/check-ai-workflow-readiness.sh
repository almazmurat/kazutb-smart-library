#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$ROOT_DIR"

status=0

ok() {
  echo "[ok]   $1"
}

warn() {
  echo "[warn] $1"
}

fail() {
  echo "[fail] $1"
  status=1
}

check_required_bin() {
  local name="$1"
  if command -v "$name" >/dev/null 2>&1; then
    ok "$name found at $(command -v "$name")"
  else
    fail "$name is missing"
  fi
}

probe() {
  local label="$1"
  shift
  local exit_code=0

  set +e
  "$@" >/tmp/ai_workflow_probe.out 2>&1
  exit_code=$?
  set -e

  if [[ $exit_code -eq 0 || $exit_code -eq 124 ]]; then
    ok "$label"
  else
    warn "$label"
    sed -n '1,6p' /tmp/ai_workflow_probe.out || true
  fi
}

echo "== KazUTB Smart Library AI workflow readiness =="
echo "repo: $ROOT_DIR"
echo

echo "-- core toolchain --"
check_required_bin php
check_required_bin composer
check_required_bin node
check_required_bin npm
check_required_bin npx

if command -v docker >/dev/null 2>&1; then
  ok "docker found at $(command -v docker)"
else
  warn "docker is missing; fetch MCP and runtime container checks will be unavailable"
fi

echo
echo "-- MCP probes --"
probe "filesystem MCP package is runnable" timeout 15s npx -y @modelcontextprotocol/server-filesystem "$ROOT_DIR"
probe "memory MCP package is runnable" timeout 15s npx -y @modelcontextprotocol/server-memory
probe "playwright MCP package is runnable" timeout 15s npx -y @playwright/mcp --help
probe "context7 MCP package is runnable" timeout 20s npx -y @upstash/context7-mcp --help

if command -v docker >/dev/null 2>&1; then
  probe "fetch MCP docker image is runnable" timeout 40s docker run -i --rm mcp/fetch --help
fi

echo
echo "-- optional credentials / environment --"
if [[ -n "${GITHUB_PERSONAL_ACCESS_TOKEN:-}" ]]; then
  ok "GITHUB_PERSONAL_ACCESS_TOKEN is set for optional GitHub MCP access"
else
  warn "GITHUB_PERSONAL_ACCESS_TOKEN is not set; GitHub MCP will prompt or remain unavailable"
fi

if bash "$ROOT_DIR/scripts/dev/start-postgres-mcp.sh" --print-url >/tmp/postgres_mcp_url.out 2>/tmp/postgres_mcp_url.err; then
  ok "Postgres MCP bootstrap can derive a valid connection URL from the repo environment"
else
  warn "Postgres MCP bootstrap is not ready yet; check .env DB_* / POSTGRES_* values"
  sed -n '1,6p' /tmp/postgres_mcp_url.err || true
fi

echo
echo "Suggested next steps:"
echo "- Open .vscode/mcp.json in VS Code and confirm the servers appear under 'MCP: List Servers'."
 echo "- Put a one-line task in docs/sdlc/current/draft.md and run /autopilot for the default full loop (use /remember for context-only updates)."
