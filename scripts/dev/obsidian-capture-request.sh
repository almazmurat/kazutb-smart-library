#!/usr/bin/env bash
set -euo pipefail

# Capture every user request into Obsidian memory with professional naming.
# Optional entity tracking keeps stateful history (e.g. navbar resources -> books).

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$ROOT_DIR"

MEMORY_DIR="artifacts/obsidian/memory-fragments"
ENTITY_DIR="$MEMORY_DIR/entities"
mkdir -p "$MEMORY_DIR" "$ENTITY_DIR"

if [[ $# -lt 1 ]]; then
    echo "Usage: bash scripts/dev/obsidian-capture-request.sh \"request text\" [--entity NAME] [--from OLD] [--to NEW]" >&2
    exit 1
fi

REQUEST_TEXT="$1"
shift || true

ENTITY=""
FROM_VALUE=""
TO_VALUE=""

while [[ $# -gt 0 ]]; do
    case "$1" in
        --entity)
            ENTITY="${2:-}"
            shift 2
            ;;
        --from)
            FROM_VALUE="${2:-}"
            shift 2
            ;;
        --to)
            TO_VALUE="${2:-}"
            shift 2
            ;;
        *)
            shift
            ;;
    esac
done

NOW_ISO="$(date -u +'%Y-%m-%dT%H:%M:%SZ')"
DATE_DASH="$(date -u +'%Y-%m-%d')"
TIME_COMPACT="$(date -u +'%H%M%S')"
TODAY_COMPACT="$(date -u +'%Y%m%d')"

SLUG="$(python3 - <<'PY' "$REQUEST_TEXT"
import re, sys, unicodedata
text = sys.argv[1].strip().lower()
text = re.sub(r"\s+", " ", text)
# Transliterate Cyrillic/Kazakh chars to ASCII for professional portable filenames.
trans = {
    'а':'a','ә':'a','б':'b','в':'v','г':'g','ғ':'g','д':'d','е':'e','ё':'e','ж':'zh','з':'z','и':'i','й':'i','к':'k','қ':'k','л':'l','м':'m','н':'n','ң':'n',
    'о':'o','ө':'o','п':'p','р':'r','с':'s','т':'t','у':'u','ұ':'u','ү':'u','ф':'f','х':'h','һ':'h','ц':'ts','ч':'ch','ш':'sh','щ':'sh','ы':'y','і':'i','э':'e','ю':'yu','я':'ya','ь':'','ъ':''
}
text = ''.join(trans.get(ch, ch) for ch in text)
text = unicodedata.normalize("NFKD", text).encode("ascii", "ignore").decode("ascii")
text = re.sub(r"[^a-z0-9]+", "-", text)
text = re.sub(r"-+", "-", text).strip("-")
print(text[:90] or "request")
PY
)"

REQUEST_FILE="$MEMORY_DIR/${DATE_DASH}_${TIME_COMPACT}__request__${SLUG}.md"
REQUEST_NODE="$(basename "$REQUEST_FILE" .md)"
WORK_LOG="$MEMORY_DIR/WORK_LOG_${TODAY_COMPACT}.md"

ENTITY_LINK_LINE=""
ENTITY_HISTORY_LINE=""
ENTITY_FILE=""
if [[ -n "$ENTITY" ]]; then
    ENTITY_SLUG="$(echo "$ENTITY" | tr '[:upper:]' '[:lower:]' | tr -cs 'a-z0-9' '-')"
    ENTITY_SLUG="${ENTITY_SLUG#-}"
    ENTITY_SLUG="${ENTITY_SLUG%-}"
    ENTITY_FILE="$ENTITY_DIR/entity-${ENTITY_SLUG}.md"
    ENTITY_NODE="$(basename "$ENTITY_FILE" .md)"
    ENTITY_LINK_LINE="  - \"[[${ENTITY_NODE}]]\""
    ENTITY_HISTORY_LINE="- Entity tracked: [[${ENTITY_NODE}]]"
fi

cat > "$REQUEST_FILE" <<EOF
---
type: user-request
project: [[Digital Library]]
date: ${DATE_DASH}
timestamp: ${NOW_ISO}
tags: [request, micro-update, obsidian-first]
links:
  - "[[CENTRAL_HUB_MIN]]"
  - "[[WORK_LOG_${TODAY_COMPACT}]]"
${ENTITY_LINK_LINE}
---

# Request Capture: ${DATE_DASH} ${TIME_COMPACT}

## Raw Request
${REQUEST_TEXT}

## Parsed Intent
- Type: user-request
- Scope: session-memory + graph node
${ENTITY_HISTORY_LINE}

## Planned Handling
- Capture request as first-class memory node.
- Preserve context for next sessions and next chats.
- Link to work log and hub for graph discoverability.
EOF

if [[ -n "$ENTITY_FILE" ]]; then
    if [[ ! -f "$ENTITY_FILE" ]]; then
        cat > "$ENTITY_FILE" <<EOF
---
type: entity-memory
project: [[Digital Library]]
entity: ${ENTITY}
date: ${DATE_DASH}
tags: [entity, state-history, obsidian-first]
links:
  - "[[CENTRAL_HUB_MIN]]"
---

# Entity State History: ${ENTITY}

## Current State
- Latest value: ${TO_VALUE:-unknown}
- Last updated: ${NOW_ISO}

## Change Log
| Timestamp (UTC) | From | To | Request Node |
|---|---|---|---|
| ${NOW_ISO} | ${FROM_VALUE:-unknown} | ${TO_VALUE:-unknown} | [[${REQUEST_NODE}]] |
EOF
    else
        # Update latest value line if present.
        if grep -q "^- Latest value:" "$ENTITY_FILE"; then
            sed -i "s|^- Latest value:.*$|- Latest value: ${TO_VALUE:-unknown}|" "$ENTITY_FILE"
        fi
        if grep -q "^- Last updated:" "$ENTITY_FILE"; then
            sed -i "s|^- Last updated:.*$|- Last updated: ${NOW_ISO}|" "$ENTITY_FILE"
        fi

        cat >> "$ENTITY_FILE" <<EOF
| ${NOW_ISO} | ${FROM_VALUE:-unknown} | ${TO_VALUE:-unknown} | [[${REQUEST_NODE}]] |
EOF
    fi
fi

# Ensure work log exists, then append request feed entry.
if [[ ! -f "$WORK_LOG" ]]; then
    cat > "$WORK_LOG" <<EOF
---
type: work-log
project: [[Digital Library]]
date: ${DATE_DASH}
session_id: session-${TODAY_COMPACT}
tags: [auto-tracked, continuous-log]
links:
  - "[[CENTRAL_HUB_MIN]]"
---

# Work Log — ${DATE_DASH}

## Request Feed
EOF
fi

if ! grep -q "^## Request Feed" "$WORK_LOG"; then
    cat >> "$WORK_LOG" <<EOF

## Request Feed
EOF
fi

cat >> "$WORK_LOG" <<EOF
- ${NOW_ISO} — [[${REQUEST_NODE}]]
EOF

echo "Request captured: $REQUEST_FILE"
if [[ -n "$ENTITY_FILE" ]]; then
    echo "Entity updated: $ENTITY_FILE"
fi
