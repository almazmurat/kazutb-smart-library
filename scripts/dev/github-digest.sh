# scripts/github-digest.sh
#!/bin/bash
REPO="almazmurat/digital-library"
VAULT_DIR="$HOME/kazutb-library-vault"
DATE=$(date +%Y-%m-%d)
OUTPUT="$VAULT_DIR/09-daily-notes/WORK_LOG_$(date +%Y%m%d).md"

echo "" >> "$OUTPUT"
echo "## GitHub Activity – $DATE" >> "$OUTPUT"
echo "" >> "$OUTPUT"

# Requires: gh CLI (github.com/cli/cli)
gh api "repos/$REPO/commits?per_page=20&since=$(date -d '24 hours ago' -Iseconds 2>/dev/null || date -v-1d +%Y-%m-%dT%H:%M:%SZ)" \
  --jq '.[] | "- `\(.sha[0:7])` \(.commit.message | split("\n")[0])"' >> "$OUTPUT" 2>/dev/null

echo "GitHub digest appended"