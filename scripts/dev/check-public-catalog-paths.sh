#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/../.."

PASS=true

echo "== Public catalog convergence guard =="

if command -v rg >/dev/null 2>&1; then
  search_contains() {
    rg --quiet --fixed-strings "$1" "$2"
  }
elif command -v grep >/dev/null 2>&1; then
  search_contains() {
    grep -Fq -- "$1" "$2"
  }
else
  echo "[error] Neither rg nor grep is available."
  exit 127
fi

must_have() {
  local pattern="$1"
  local path="$2"
  local label="$3"
  if search_contains "$pattern" "$path"; then
    echo "[ok] $label"
  else
    echo "[error] Missing: $label ($pattern in $path)"
    PASS=false
  fi
}

warn_if_present() {
  local pattern="$1"
  local path="$2"
  local label="$3"
  if search_contains "$pattern" "$path"; then
    echo "[warn] Transitional surface still active: $label"
  else
    echo "[ok] Not present: $label"
  fi
}

must_have "Route::get('/catalog'," "routes/web.php" "canonical public catalog route"
must_have "Route::get('/book/{isbn}'," "routes/web.php" "canonical public book route"
must_have "Route::get('/catalog-db'," "routes/api.php" "canonical catalog DB API route"
must_have "Route::get('/book-db/{isbn}'," "routes/api.php" "canonical book DB API route"
must_have "const API_ENDPOINT = '/api/v1/catalog-db';" "resources/views/catalog.blade.php" "catalog view canonical API wiring"
must_have "const BOOK_DB_API_ENDPOINT = '/api/v1/book-db/';" "resources/views/book.blade.php" "book view canonical API wiring"
must_have "CATALOG_API_ENDPOINT = '/api/v1/catalog-db';" "resources/views/welcome.blade.php" "landing catalog block canonical API wiring"

warn_if_present "Route::get('/catalog'," "routes/api.php" "legacy demo catalog API route"
warn_if_present "Route::get('/catalog/{isbn}'," "routes/api.php" "legacy duplicate book API route"
warn_if_present "Route::get('/catalog-external'," "routes/api.php" "external proxy catalog API route"
warn_if_present "Route::get('/book/{isbn}/read'," "routes/web.php" "reader route on external proxy flow"
must_have "[WS1-FROZEN][LEGACY-CATALOG]" "routes/api.php" "legacy catalog route freeze marker"
must_have "[WS1-FROZEN][LEGACY-DETAIL-ALIAS]" "routes/api.php" "legacy detail alias freeze marker"
must_have "[WS1-FROZEN][TRANSITIONAL-EXTERNAL]" "routes/api.php" "transitional external route freeze marker"
must_have "reader.transitional" "routes/web.php" "transitional reader route named marker"
must_have "curl -fs http://localhost/api/v1/catalog-db?limit=1 || exit 1" "docker-compose.yml" "healthcheck on canonical catalog DB API"
warn_if_present "/catalog/search?" "resources/js/spa/pages/CatalogPage.jsx" "SPA catalog wired to non-existent API path"

if [ "$PASS" = true ]; then
  echo "Result: PASS (canonical paths present)."
  exit 0
fi

echo "Result: FAIL (canonical path wiring incomplete)."
exit 1
