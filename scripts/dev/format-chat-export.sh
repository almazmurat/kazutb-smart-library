# scripts/format-chat-export.sh
#!/bin/bash
# Usage: paste raw chat text into a file, run this to format it for Obsidian
INPUT=$1
DATE=$(date +%Y-%m-%d)
SLUG=$(head -1 "$INPUT" | tr '[:upper:]' '[:lower:]' | sed 's/[^a-z0-9]/-/g' | cut -c1-40)
OUTPUT="$HOME/kazutb-library-vault/13-copilot-memory/${DATE}_${SLUG}.md"

cat > "$OUTPUT" << EOF
---
date: $DATE
tags: [chat-export, claude]
---
$(cat "$INPUT")
EOF
echo "Saved to $OUTPUT"