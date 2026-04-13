---
type: memory-fragment
project: [[Digital Library]]
source_chat: [[chat.md]]
date: 2026-04-13
tags:
  - chat-memory
  - project-truth
  - architecture
  - domain
  - crm-auth
  - data-quality
links:
  - "[[02-architecture]]"
  - "[[03-domain]]"
  - "[[12-reference]]"
  - "[[06-decisions]]"
  - "[[04-crm-auth-integration]]"
---

# Memory Fragment

## Session
- Date: 2026-04-13
- Source chat: [[chat.md]]
- Main topic: chat
- Status: Verified

## Durable Knowledge Extracted
1. Title: Extract 1
Type: Bug Fix
Problem: I’m realizing I need to be cautious about exposing any secrets or committing them. The user may not ask for a fix, but if I do commit, I should avoid including sensitive informatio
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[02-architecture]]

2. Title: Extract 2
Type: Bug Fix
Problem: I'm planning to run a targeted test that I expect to fail, which will help prove that a certain command isn't functioning as intended. I think using the `runTests` tool for a speci
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[03-domain]]

3. Title: Extract 3
Type: Bug Fix
Problem: I need to run the get_errors tool on the modified files to catch any syntax problems. I'll focus on the welcome file and the test file, as they might have issues that need fixing. 
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[12-reference]]

4. Title: Extract 4
Type: Reference
Problem: I’m considering using `git add -- ... && git commit -m ...` to sync. This way, I’ll only commit the files that are staged, which is a good approach. I definitely need to include `d
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[06-decisions]]

5. Title: Extract 5
Type: Reference
Problem: I’m considering specifying that the changes only affect the frontend, without delving into the backend or database aspects. It seems I should update the spec.md to address the curr
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[04-crm-auth-integration]]

## Naming Or Terminology To Remember
- Use КазТБУ as the default institutional naming in project context.

## Promotion Recommendations
- Promote only repeated patterns and decisions to permanent vault notes.
- Keep raw chat as evidence link, not as primary knowledge store.
