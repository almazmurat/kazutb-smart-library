#!/usr/bin/env bash
set -euo pipefail

echo "Session start checklist:"
echo "1. Run: composer dev:context"
echo "2. Read AGENT_START_HERE.md + project-context startup set (00,01,06,05)"
echo "3. If planning/architecture task, also read: 02,03,04,98"
echo "4. Run: composer dev:context-drift"
echo "5. Classify current task scope before changes"
echo "6. Prepare prompt in prompts/next-step.md and execute it"
echo "7. Use vault as mirror/log only; repo remains execution truth"
