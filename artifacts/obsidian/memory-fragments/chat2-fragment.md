---
type: memory-fragment
project: [[Digital Library]]
source_chat: [[chat2.md]]
date: 2026-04-13
tags:
  - chat-memory
  - architecture
  - domain
  - crm-auth
  - data-quality
  - decisions
links:
  - "[[05-data-quality-stewardship]]"
  - "[[02-architecture]]"
  - "[[12-reference]]"
  - "[[04-crm-auth-integration]]"
  - "[[03-domain]]"
---

# Memory Fragment

## Session
- Date: 2026-04-13
- Source chat: [[chat2.md]]
- Main topic: chat2
- Status: Verified

## Durable Knowledge Extracted
1. Title: Extract 1
Type: Reference
Problem: > Avoid this on shared systems because it exposes the password in shell history.
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[05-data-quality-stewardship]]

2. Title: Extract 2
Type: Reference
Problem: I’ve verified the Docker Compose settings and need to find out how to connect to the database via shell and show tables for a research project. I might need to provide specific com
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[02-architecture]]

3. Title: Extract 3
Type: Reference
Problem: I need to check if the actual connection commands work. I'll run a command using `docker compose exec` to list the tables while keeping secrets secure. It’s important to show usefu
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[12-reference]]

4. Title: Extract 4
Type: Reference
Problem: I’m thinking about inspecting the schema of a few tables relevant to the project. It might not be strictly necessary, but it’ll help clarify research tasks. I could try running `\d
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[04-crm-auth-integration]]

5. Title: Extract 5
Type: Reference
Problem: For the quoted table `"Book"`, the `information_schema.table_name` indeed returns the name `Book` exactly as is, confirming that the database is case-sensitive. This means I need t
Decision Logic: Captured from conversation evidence.
Result: Promoted to durable memory fragment.
Suggested vault target: [[03-domain]]

## Naming Or Terminology To Remember
- No new terminology rule extracted in this session.

## Promotion Recommendations
- Promote only repeated patterns and decisions to permanent vault notes.
- Keep raw chat as evidence link, not as primary knowledge store.
