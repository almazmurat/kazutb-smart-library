---
type: memory-fragment
project: [[Digital Library]]
source_chat: [[chat1.md]]
date: 2026-04-13
tags:
  - chat-memory
  - project-truth
  - architecture
  - domain
  - crm-auth
  - decisions
links:
  - "[[12-reference]]"
  - "[[01-project-truth]]"
  - "[[06-decisions]]"
  - "[[03-domain]]"
  - "[[04-crm-auth-integration]]"
---

# Memory Fragment

## Session
- Date: 2026-04-13
- Source chat: [[chat1.md]]
- Main topic: chat1
- Status: Verified

## Durable Knowledge Extracted
1. Title: Extract 1
Type: Decision
Problem: I need to inspect the repository memory file and search for terms like "КазУТБ" or "КазТБУ" to check for any discrepancies. Since this is an update stage, I should modify the impac
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[12-reference]]

2. Title: Extract 2
Type: Decision
Problem: I need to use the list_dir command to examine relevant directories in my vault, specifically 00-index and 06-decisions. It’s important to confirm if the workspace path includes the
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[01-project-truth]]

3. Title: Extract 3
Type: Decision
Problem: I need to be precise to avoid confusion: the default name for Kazakh/Cyrillic-facing copy should be "КазТБУ," using "КазУТБ" only for Russian references. Existing technical identif
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[06-decisions]]

4. Title: Extract 4
Type: Decision
Problem: I’ll ensure future branding uses `КазТБУ`, treating `КазУТБ` as drift in Kazakh content unless there's a Russian context. I also want to update the decision log to reflect these up
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[03-domain]]

5. Title: Extract 5
Type: Decision
Problem: I need to consider using `multi_replace_string_in_file` for efficient edits because there are multiple independent changes across different files. It sounds like a good idea to cre
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[04-crm-auth-integration]]

## Naming Or Terminology To Remember
- Use КазТБУ as the default institutional naming in project context.

## Promotion Recommendations
- Promote only repeated patterns and decisions to permanent vault notes.
- Keep raw chat as evidence link, not as primary knowledge store.
