#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
VAULT_DIR="${OBSIDIAN_VAULT_DIR:-/home/admlibrary/knowledge/kazutb-library-vault}"

mkdir -p "$VAULT_DIR/00-index" "$VAULT_DIR/02-architecture" "$VAULT_DIR/04-crm-auth-integration"

python3 - "$ROOT_DIR" "$VAULT_DIR" <<'PY'
from __future__ import annotations

import datetime as dt
import json
import re
import sys
from pathlib import Path

repo = Path(sys.argv[1]).resolve()
vault = Path(sys.argv[2]).resolve()
stamp = dt.datetime.now().strftime("%Y-%m-%d %H:%M")
written: list[Path] = []


def read_text(path: Path) -> str:
    try:
        return path.read_text(encoding="utf-8")
    except UnicodeDecodeError:
        return path.read_text(encoding="utf-8", errors="ignore")


def rel(path: Path) -> str:
    try:
        return path.resolve().relative_to(repo).as_posix()
    except ValueError:
        return path.as_posix()


def list_files(rel_dir: str, suffixes: set[str] | None = None, limit: int = 80) -> list[str]:
    base = repo / rel_dir
    if not base.exists():
        return []

    items: list[str] = []
    for path in sorted(base.rglob("*")):
        if not path.is_file():
            continue
        if suffixes and path.suffix.lower() not in suffixes:
            continue
        items.append(rel(path))
        if len(items) >= limit:
            break
    return items


def parse_routes(route_file: str, limit: int = 40) -> list[tuple[str, str, int]]:
    path = repo / route_file
    if not path.exists():
        return []

    pattern = re.compile(r"Route::(get|post|put|patch|delete)\(\s*['\"]([^'\"]+)['\"]")
    prefix_pattern = re.compile(r"Route::prefix\(\s*['\"]([^'\"]+)['\"]\s*\)")
    routes: list[tuple[str, str, int]] = []
    prefix_stack: list[tuple[str, int]] = []
    brace_depth = 0
    pending_prefix: str | None = None

    for line_no, line in enumerate(read_text(path).splitlines(), start=1):
        stripped = line.strip()
        open_count = line.count("{")
        close_count = line.count("}")

        prefix_match = prefix_pattern.search(stripped)
        if prefix_match:
            pending_prefix = prefix_match.group(1).strip("/")

        if pending_prefix is not None and "->group" in stripped:
            prefix_stack.append((pending_prefix, brace_depth + open_count - close_count))
            pending_prefix = None

        match = pattern.search(line)
        if match:
            route_path = match.group(2).strip()
            current_prefix = "/".join(prefix for prefix, _depth in prefix_stack if prefix)
            route_suffix = "" if route_path == "/" else route_path.strip("/")
            full_path = "/" + "/".join(part for part in [current_prefix, route_suffix] if part)
            routes.append((match.group(1).upper(), full_path or "/", line_no))
            if len(routes) >= limit:
                break

        brace_depth += open_count - close_count
        while prefix_stack and brace_depth < prefix_stack[-1][1]:
            prefix_stack.pop()

    return routes


def parse_php_inventory(rel_dir: str, limit: int = 40) -> list[dict[str, object]]:
    base = repo / rel_dir
    if not base.exists():
        return []

    items: list[dict[str, object]] = []
    for path in sorted(base.rglob("*.php")):
        text = read_text(path)
        class_match = re.search(r"class\s+([A-Za-z0-9_]+)", text)
        class_name = class_match.group(1) if class_match else path.stem
        methods = re.findall(r"public function\s+([A-Za-z0-9_]+)\(", text)
        items.append({
            "path": rel(path),
            "class": class_name,
            "methods": methods[:8],
        })
        if len(items) >= limit:
            break
    return items


def parse_js_inventory(rel_dir: str, limit: int = 40) -> list[dict[str, object]]:
    base = repo / rel_dir
    if not base.exists():
        return []

    items: list[dict[str, object]] = []
    for path in sorted(base.rglob("*")):
        if not path.is_file() or path.suffix.lower() not in {".js", ".jsx", ".ts", ".tsx"}:
            continue
        text = read_text(path)
        exports = re.findall(r"export\s+(?:function|const|class)\s+([A-Za-z0-9_]+)", text)
        items.append({
            "path": rel(path),
            "exports": exports[:8],
        })
        if len(items) >= limit:
            break
    return items


def parse_spa_routes() -> list[str]:
    app_file = repo / "resources/js/spa/App.jsx"
    if not app_file.exists():
        return []

    pattern = re.compile(r'<Route(?:(?:\s+path="([^"]+)")|(?:\s+index))')
    routes: list[str] = []
    for match in pattern.finditer(read_text(app_file)):
        routes.append(match.group(1) if match.group(1) else "(index redirect)")
    return routes


def parse_vite_inputs() -> list[str]:
    vite_file = repo / "vite.config.js"
    if not vite_file.exists():
        return []

    text = read_text(vite_file)
    match = re.search(r"input:\s*\[(.*?)\]", text, re.S)
    if not match:
        return []
    return re.findall(r"'([^']+)'", match.group(1))


def parse_docker_services() -> list[str]:
    compose_file = repo / "docker-compose.yml"
    if not compose_file.exists():
        return []

    services: list[str] = []
    in_services = False
    for line in read_text(compose_file).splitlines():
        if re.match(r"^services:\s*$", line):
            in_services = True
            continue
        if in_services:
            if re.match(r"^[A-Za-z]", line):
                break
            match = re.match(r"^\s{2}([A-Za-z0-9_-]+):\s*$", line)
            if match:
                services.append(match.group(1))
    return services


def parse_json(path: Path) -> dict:
    if not path.exists():
        return {}
    return json.loads(read_text(path))


def item_block(items: list[dict[str, object]], label: str = "class") -> str:
    if not items:
        return "- none found"

    lines: list[str] = []
    for item in items:
        path = str(item["path"])
        name = str(item.get(label) or item.get("path") or "unknown")
        methods = item.get("methods") or item.get("exports") or []
        suffix = f" — {', '.join(methods)}" if methods else ""
        lines.append(f"- `{path}` — `{name}`{suffix}")
    return "\n".join(lines)


def file_block(paths: list[str]) -> str:
    if not paths:
        return "- none found"
    return "\n".join(f"- `{path}`" for path in paths)


def write_note(note_rel_path: str, body: str) -> None:
    path = vault / note_rel_path
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(body.rstrip() + "\n", encoding="utf-8")
    written.append(path)


package_data = parse_json(repo / "package.json")
composer_data = parse_json(repo / "composer.json")
services_php = read_text(repo / "config/services.php") if (repo / "config/services.php").exists() else ""
bootstrap_php = read_text(repo / "bootstrap/app.php") if (repo / "bootstrap/app.php").exists() else ""

external_auth_match = re.search(r"login_url'\s*=>\s*env\([^,]+,\s*'([^']+)'", services_php)
external_auth_url = external_auth_match.group(1) if external_auth_match else "not found"

web_routes = parse_routes("routes/web.php", limit=24)
api_routes = parse_routes("routes/api.php", limit=48)
crm_routes = [
    route for route in api_routes
    if route[1] == "/login"
    or route[1].startswith("/demo-auth/")
    or route[1].startswith("/v1/me")
    or route[1].startswith("/v1/logout")
    or route[1].startswith("/v1/account/reservations")
    or route[1].startswith("/integration/v1/")
]
spa_routes = parse_spa_routes()
vite_inputs = parse_vite_inputs()
docker_services = parse_docker_services()

backend_controllers = parse_php_inventory("app/Http/Controllers/Api", limit=28)
backend_services = parse_php_inventory("app/Services/Library", limit=36)
backend_models = parse_php_inventory("app/Models", limit=20)
backend_middleware = parse_php_inventory("app/Http/Middleware", limit=12)
frontend_entries = parse_js_inventory("resources/js", limit=24)
integration_controllers = parse_php_inventory("app/Http/Controllers/Api/Integration", limit=12)

blade_views = list_files("resources/views", suffixes={".php"}, limit=80)
spa_pages = list_files("resources/js/spa/pages", suffixes={".js", ".jsx", ".ts", ".tsx"}, limit=40)
spa_components = list_files("resources/js/spa/components", suffixes={".js", ".jsx", ".ts", ".tsx"}, limit=60)
migrations = list_files("database/migrations", suffixes={".php"}, limit=120)
seeders = list_files("database/seeders", suffixes={".php"}, limit=40)
factories = list_files("database/factories", suffixes={".php"}, limit=40)
dev_scripts = list_files("scripts/dev", limit=120)
agents = list_files("agents", limit=60)

migration_topics = [
    Path(path).stem.split("_", 4)[-1].replace("_", " ") for path in migrations
]
composer_scripts = sorted((composer_data.get("scripts") or {}).keys())
npm_scripts = sorted((package_data.get("scripts") or {}).keys())
middleware_aliases = re.findall(r"'([^']+)'\s*=>\s*([A-Za-z0-9_:\\]+)::class", bootstrap_php)

route_lines_web = "\n".join(
    f"- `{method} {route}` (`routes/web.php:{line_no}`)" for method, route, line_no in web_routes
) if web_routes else "- none found"
route_lines_api = "\n".join(
    f"- `{method} {route}` (`routes/api.php:{line_no}`)" for method, route, line_no in api_routes
) if api_routes else "- none found"
crm_route_lines = "\n".join(
    f"- `{method} {route}` (`routes/api.php:{line_no}`)" for method, route, line_no in crm_routes
) if crm_routes else "- none found"
spa_route_lines = "\n".join(f"- `{route}`" for route in spa_routes) if spa_routes else "- none found"
alias_lines = "\n".join(
    f"- `{alias}` -> `{target}`" for alias, target in middleware_aliases
) if middleware_aliases else "- none found"
migration_topic_lines = "\n".join(f"- {topic}" for topic in migration_topics) if migration_topics else "- none found"

backend_note = f"""# Backend code map - Laravel routes, controllers, services, and models

> Generated automatically from the live repository on **{stamp}**.  
> Refresh with `bash scripts/dev/generate-obsidian-code-maps.sh` or `composer dev:memory-refresh-maps`.

## What this map anchors
- route truth in `routes/web.php` and `routes/api.php`
- the API controller surface under `app/Http/Controllers/Api/`
- library domain services under `app/Services/Library/`
- data models and middleware boundaries used by those flows

## Web route entrypoints seen in code
{route_lines_web}

## API route entrypoints seen in code
{route_lines_api}

## API controllers currently present
{item_block(backend_controllers)}

## Library service layer currently present
{item_block(backend_services)}

## Models currently present
{item_block(backend_models)}

## Middleware and boundary guards
{item_block(backend_middleware)}

## Read this with
- [[how-the-runtime-works-in-docker-laravel-and-postgresql]]
- [[api-boundaries]]
- [[../12-reference/repository-map]]
"""

frontend_note = f"""# Frontend code map - Blade, React, and Vite entrypoints

> Generated automatically from the live repository on **{stamp}**.  
> Refresh with `bash scripts/dev/generate-obsidian-code-maps.sh` or `composer dev:memory-refresh-maps`.

## What this map anchors
- Vite entrypoints and frontend build wiring
- Blade-rendered public and internal pages
- React SPA shell under `/app`
- dedicated internal AI chat frontend mount

## Vite inputs from `vite.config.js`
{file_block(vite_inputs)}

## JavaScript and React entrypoints
{item_block(frontend_entries, label='path')}

## SPA routes currently declared in `resources/js/spa/App.jsx`
{spa_route_lines}

## SPA pages
{file_block(spa_pages)}

## SPA components
{file_block(spa_components)}

## Blade views
{file_block(blade_views)}

## Read this with
- [[how-the-frontend-react-blade-vite-layer-connects-to-laravel]]
- [[../12-reference/repository-map]]
- [[../08-workstreams/current-workstreams]]
"""

database_note = f"""# Database code map - migrations, models, and persistence

> Generated automatically from the live repository on **{stamp}**.  
> Refresh with `bash scripts/dev/generate-obsidian-code-maps.sh` or `composer dev:memory-refresh-maps`.

## What this map anchors
- migration history in `database/migrations/`
- Eloquent model entrypoints in `app/Models/`
- seed and factory support used for local and test setup

## Migrations currently present ({len(migrations)})
{file_block(migrations)}

## Migration topics inferred from filenames
{migration_topic_lines}

## Models currently present
{item_block(backend_models)}

## Seeders
{file_block(seeders)}

## Factories
{file_block(factories)}

## Read this with
- [[../03-domain/library-domain-model]]
- [[../05-data-quality-stewardship/data-stewardship-strategy]]
- [[../12-reference/repository-map]]
"""

crm_note = f"""# CRM integration code map - auth boundary and reservation shell

> Generated automatically from the live repository on **{stamp}**.  
> Refresh with `bash scripts/dev/generate-obsidian-code-maps.sh` or `composer dev:memory-refresh-maps`.

## What this map anchors
- reader login flow from the library to the external CRM auth API
- integration boundary middleware and request logging
- reservation and document integration shell under `integration/v1`

## External auth configuration seen in code
- login URL default: `{external_auth_url}`
- config file: `config/services.php`
- demo/dev bypass config: `config/demo_auth.php`

## Auth and reader session files
- `app/Http/Controllers/Api/AuthController.php`
- `app/Http/Controllers/Api/DemoAuthController.php`
- `app/Http/Middleware/EnsureAuthenticatedReader.php`
- `routes/api.php`

## Integration and auth routes currently declared
{crm_route_lines}

## Integration controllers currently present
{item_block(integration_controllers)}

## Boundary middleware aliases from `bootstrap/app.php`
{alias_lines}

## Read this with
- [[crm-library-boundary]]
- [[crm-auth-truth]]
- [[crm-api-notes]]
- [[../02-architecture/api-boundaries]]
"""

tooling_note = f"""# Tooling code map - Docker, scripts, AI bridge, and developer workflow

> Generated automatically from the live repository on **{stamp}**.  
> Refresh with `bash scripts/dev/generate-obsidian-code-maps.sh` or `composer dev:memory-refresh-maps`.

## Runtime and build surfaces
- Docker services from `docker-compose.yml`: {', '.join(f'`{service}`' for service in docker_services) if docker_services else 'none found'}
- Vite config: `vite.config.js`
- Node package scripts: {', '.join(f'`{script}`' for script in npm_scripts) if npm_scripts else 'none found'}
- Composer scripts: {', '.join(f'`{script}`' for script in composer_scripts) if composer_scripts else 'none found'}

## Dev automation scripts
{file_block(dev_scripts)}

## 21st and agent tooling paths
- `scripts/21st/bridge.mjs`
- `app/Services/Ai/TwentyFirstBridgeService.php`
{file_block(agents)}

## Read this with
- [[how-mcp-21st-and-obsidian-fit-into-the-tooling-layer]]
- [[../12-reference/important-paths]]
- [[../07-bugs-and-incidents/active problem register - runtime, data, and integration]]
"""

index_note = f"""# Code map index - autogenerated from repo paths

> This page is regenerated from the repository tree on **{stamp}**.  
> Use it when you want code-anchored navigation instead of narrative notes.

## Generated maps
- [[../02-architecture/backend code map - Laravel routes, controllers, services, and models]]
- [[../02-architecture/frontend code map - Blade, React, and Vite entrypoints]]
- [[../02-architecture/database code map - migrations, models, and persistence]]
- [[../04-crm-auth-integration/CRM integration code map - auth boundary and reservation shell]]
- [[../02-architecture/tooling code map - Docker, scripts, AI bridge, and developer workflow]]

## Refresh commands
- `bash scripts/dev/generate-obsidian-code-maps.sh`
- `composer dev:memory-refresh-maps`

## Good companion notes
- [[read this first before any new agent session]]
- [[root context graph - kazutb smart library]]
- [[repo-context-index]]
- [[../08-workstreams/current-workstreams]]
"""

write_note("02-architecture/backend code map - Laravel routes, controllers, services, and models.md", backend_note)
write_note("02-architecture/frontend code map - Blade, React, and Vite entrypoints.md", frontend_note)
write_note("02-architecture/database code map - migrations, models, and persistence.md", database_note)
write_note("04-crm-auth-integration/CRM integration code map - auth boundary and reservation shell.md", crm_note)
write_note("02-architecture/tooling code map - Docker, scripts, AI bridge, and developer workflow.md", tooling_note)
write_note("00-index/code map index - autogenerated from repo paths.md", index_note)

for path in written:
    print(path)
PY

echo "Generated Obsidian code maps under: $VAULT_DIR"
echo "- 00-index/code map index - autogenerated from repo paths.md"
echo "- 02-architecture/* code map notes"
echo "- 04-crm-auth-integration/CRM integration code map - auth boundary and reservation shell.md"
echo "Regenerate anytime with: bash scripts/dev/generate-obsidian-code-maps.sh"
