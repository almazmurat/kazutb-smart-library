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

echo "== Core tools =="
check_cmd git
check_cmd docker
check_cmd php
check_cmd composer
check_cmd node
check_cmd npm
check_cmd copilot

echo
echo "== Versions (if available) =="
command -v git >/dev/null 2>&1 && git --version || true
command -v docker >/dev/null 2>&1 && docker --version || true
command -v docker >/dev/null 2>&1 && docker compose version || true
command -v php >/dev/null 2>&1 && php -v | head -n 1 || true
command -v composer >/dev/null 2>&1 && composer --version || true
command -v node >/dev/null 2>&1 && node -v || true
command -v npm >/dev/null 2>&1 && npm -v || true

echo
echo "== Optional MCP check (Context7) =="
if command -v npx >/dev/null 2>&1; then
  if npx ctx7 --help >/dev/null 2>&1; then
    echo "[ok] ctx7"
  else
    echo "[warn] ctx7 is not ready. If Node is below 20, upgrade Node and retry."
  fi
else
  echo "[warn] npx not available; cannot verify ctx7"
fi
