#!/usr/bin/env bash
set -euo pipefail

VAULT_DIR="/home/admlibrary/knowledge/kazutb-library-vault"

echo "Checking vault structure..."

test -d "$VAULT_DIR/00-index"
test -d "$VAULT_DIR/06-decisions"
test -d "$VAULT_DIR/07-bugs-and-incidents"
test -d "$VAULT_DIR/08-workstreams"
test -d "$VAULT_DIR/09-daily-notes"

echo "Vault core structure OK"