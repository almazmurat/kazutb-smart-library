#!/usr/bin/env bash
set -euo pipefail

# Minimal runtime smoke verification for Integration Document Management v1 Phase 1.
# Scope is intentionally narrow: document metadata endpoints only.

BASE_URL="${BASE_URL:-http://127.0.0.1}"
COMPOSE_CMD="${COMPOSE_CMD:-docker compose}"
EXERCISE_UNEXPECTED_FAILURE="${EXERCISE_UNEXPECTED_FAILURE:-1}"

REQUEST_ID_PREFIX="doc-smoke-$(date +%s)"
CORRELATION_ID_PREFIX="doc-corr-$(date +%s)"

if ! command -v curl >/dev/null 2>&1; then
  echo "[FAIL] curl is required"
  exit 1
fi

if ! command -v jq >/dev/null 2>&1; then
  echo "[FAIL] jq is required"
  exit 1
fi

postgres_was_stopped=0
cleanup() {
  if [[ "$postgres_was_stopped" == "1" ]]; then
    echo "[INFO] Restoring postgres service..."
    $COMPOSE_CMD start postgres >/dev/null
  fi
}
trap cleanup EXIT

wait_for_app() {
  local attempts=0
  local max_attempts=30

  while (( attempts < max_attempts )); do
    if curl -sS -o /dev/null -w "%{http_code}" "${BASE_URL}/api/v1/catalog-db" | grep -qE '^(200|401|404)$'; then
      echo "[INFO] App is reachable"
      return 0
    fi

    attempts=$((attempts + 1))
    sleep 2
  done

  echo "[FAIL] App did not become reachable in time"
  exit 1
}

run_json_request() {
  local method="$1"
  local path="$2"
  local body="${3:-}"
  local req_id="$4"
  local corr_id="$5"
  local auth_header="${6:-__USE_DEFAULT_BEARER__}"

  local url="${BASE_URL}${path}"
  local tmp
  tmp=$(mktemp)

  local auth_args=()
  if [[ "$auth_header" == "__USE_DEFAULT_BEARER__" ]]; then
    auth_args=(-H "Authorization: Bearer integration-smoke-token")
  elif [[ "$auth_header" != "__NO_AUTH__" ]]; then
    auth_args=(-H "Authorization: ${auth_header}")
  fi

  if [[ -n "$body" ]]; then
    http_code=$(curl -sS -o "$tmp" -w "%{http_code}" -X "$method" "$url" \
      "${auth_args[@]}" \
      -H "X-Request-Id: ${req_id}" \
      -H "X-Correlation-Id: ${corr_id}" \
      -H "X-Source-System: crm" \
      -H "X-Operator-Id: crm-smoke-operator" \
      -H "X-Operator-Roles: documents.read,documents.write" \
      -H 'X-Operator-Org-Context: {"branch_id":"f84341eb-1010-45be-b93e-6c94cd9cea8a"}' \
      -H "Content-Type: application/json" \
      --data "$body")
  else
    http_code=$(curl -sS -o "$tmp" -w "%{http_code}" -X "$method" "$url" \
      "${auth_args[@]}" \
      -H "X-Request-Id: ${req_id}" \
      -H "X-Correlation-Id: ${corr_id}" \
      -H "X-Source-System: crm" \
      -H "X-Operator-Id: crm-smoke-operator" \
      -H "X-Operator-Roles: documents.read,documents.write" \
      -H 'X-Operator-Org-Context: {"branch_id":"f84341eb-1010-45be-b93e-6c94cd9cea8a"}')
  fi

  response_body=$(cat "$tmp")
  rm -f "$tmp"
}

assert_status() {
  local expected="$1"
  local actual="$2"
  local label="$3"
  if [[ "$expected" != "$actual" ]]; then
    echo "[FAIL] ${label}: expected status ${expected}, got ${actual}"
    echo "$response_body" | jq . 2>/dev/null || echo "$response_body"
    exit 1
  fi
  echo "[PASS] ${label}"
}

assert_jq_true() {
  local expr="$1"
  local label="$2"
  if ! echo "$response_body" | jq -e "$expr" >/dev/null; then
    echo "[FAIL] ${label}: jq assertion failed: ${expr}"
    echo "$response_body" | jq . 2>/dev/null || echo "$response_body"
    exit 1
  fi
  echo "[PASS] ${label}"
}

echo "[INFO] Ensuring app stack is running..."
$COMPOSE_CMD up -d postgres app >/dev/null
wait_for_app

echo "[INFO] Scenario: boundary/header failure (missing bearer)"
run_json_request "GET" "/api/integration/v1/documents" "" "${REQUEST_ID_PREFIX}-b1" "${CORRELATION_ID_PREFIX}-b1" "__NO_AUTH__"
assert_status "401" "$http_code" "boundary missing bearer status"
assert_jq_true '.error.error_code == "auth_failed" and .error.reason_code == "missing_bearer_token"' "boundary missing bearer envelope"

echo "[INFO] Scenario: list success"
run_json_request "GET" "/api/integration/v1/documents" "" "${REQUEST_ID_PREFIX}-l1" "${CORRELATION_ID_PREFIX}-l1"
assert_status "200" "$http_code" "list status"
assert_jq_true 'has("data") and has("meta") and .request_id == "'"${REQUEST_ID_PREFIX}-l1"'"' "list envelope"

echo "[INFO] Scenario: create success"
TITLE="Smoke Doc ${REQUEST_ID_PREFIX}"
CREATE_BODY=$(jq -n --arg title "$TITLE" '{title:$title}')
run_json_request "POST" "/api/integration/v1/documents" "$CREATE_BODY" "${REQUEST_ID_PREFIX}-c1" "${CORRELATION_ID_PREFIX}-c1"
assert_status "201" "$http_code" "create status"
assert_jq_true '.data.id != null and .data.title == "'"$TITLE"'"' "create payload"
DOC_ID=$(echo "$response_body" | jq -r '.data.id')

echo "[INFO] Scenario: detail success"
run_json_request "GET" "/api/integration/v1/documents/${DOC_ID}" "" "${REQUEST_ID_PREFIX}-d1" "${CORRELATION_ID_PREFIX}-d1"
assert_status "200" "$http_code" "detail status"
assert_jq_true '.data.id == "'"$DOC_ID"'"' "detail payload"

echo "[INFO] Scenario: patch success"
PATCH_TITLE="${TITLE} patched"
PATCH_BODY=$(jq -n --arg title "$PATCH_TITLE" '{title:$title}')
run_json_request "PATCH" "/api/integration/v1/documents/${DOC_ID}" "$PATCH_BODY" "${REQUEST_ID_PREFIX}-p1" "${CORRELATION_ID_PREFIX}-p1"
assert_status "200" "$http_code" "patch status"
assert_jq_true '.data.id == "'"$DOC_ID"'" and .data.title == "'"$PATCH_TITLE"'"' "patch payload"

echo "[INFO] Scenario: archive success"
run_json_request "POST" "/api/integration/v1/documents/${DOC_ID}/archive" "" "${REQUEST_ID_PREFIX}-a1" "${CORRELATION_ID_PREFIX}-a1"
assert_status "200" "$http_code" "archive status"
assert_jq_true '.data.id == "'"$DOC_ID"'"' "archive payload"

echo "[INFO] Scenario: invalid body validation failure"
run_json_request "POST" "/api/integration/v1/documents" '{"isbn":"9780000000000"}' "${REQUEST_ID_PREFIX}-v1" "${CORRELATION_ID_PREFIX}-v1"
assert_status "400" "$http_code" "invalid body status"
assert_jq_true '.error.error_code == "invalid_request" and .error.reason_code == "invalid_request_body"' "invalid body envelope"

echo "[INFO] Scenario: invalid UUID"
run_json_request "GET" "/api/integration/v1/documents/not-a-uuid" "" "${REQUEST_ID_PREFIX}-u1" "${CORRELATION_ID_PREFIX}-u1"
assert_status "400" "$http_code" "invalid uuid status"
assert_jq_true '.error.error_code == "invalid_request" and .error.reason_code == "invalid_document_id"' "invalid uuid envelope"

echo "[INFO] Scenario: not found"
run_json_request "GET" "/api/integration/v1/documents/00000000-0000-0000-0000-000000000000" "" "${REQUEST_ID_PREFIX}-n1" "${CORRELATION_ID_PREFIX}-n1"
assert_status "404" "$http_code" "not found status"
assert_jq_true '.error.error_code == "not_found" and .error.reason_code == "document_not_found"' "not found envelope"

if [[ "$EXERCISE_UNEXPECTED_FAILURE" == "1" ]]; then
  echo "[INFO] Scenario: unexpected failure envelope (postgres stopped briefly)"
  $COMPOSE_CMD stop postgres >/dev/null
  postgres_was_stopped=1

  run_json_request "GET" "/api/integration/v1/documents" "" "${REQUEST_ID_PREFIX}-e1" "${CORRELATION_ID_PREFIX}-e1"
  assert_status "500" "$http_code" "unexpected failure status"
  assert_jq_true '.error.error_code == "server_error" and .error.reason_code == "internal_failure"' "unexpected failure envelope"

  $COMPOSE_CMD start postgres >/dev/null
  postgres_was_stopped=0
fi

echo "[PASS] Document Management API v1 Phase 1 smoke verification completed"
