Start by reading AGENT_START_HERE.md and all current project-context files.

Task:
Perform a full repository and workflow audit to explain what each major part of this project is for, what is actively used, what is historical, what is redundant, and what should be cleaned up or preserved.

This is not a coding task.

Important:
- Be strict and repository-grounded.
- Do not assume that every markdown file or docs file is useful.
- Distinguish execution truth, documentation, operational scripts, historical archives, and memory/knowledge files.
- Treat Copilot, docs, prompts, scripts, and Obsidian/vault as different layers with different purposes.
- The goal is to understand the system and then clean it up.

What to do:

1. Explain the project layers
Describe the role of:
- .github
- project-context
- prompts
- scripts/dev
- docs
- docs/archive
- Obsidian vault / knowledge graph
- app/routes/database/tests/resources/public/storage/config
Explain what each layer is for and how it should be used.

2. Classify files by purpose
For the important files and file groups in the repository, classify them as:
- source of truth
- active working doc
- operational script
- historical archive
- prompt template
- likely redundant
- likely obsolete
- keep for reference only

3. Identify duplicated or conflicting files
Find:
- duplicate master contexts
- overlapping workflow docs
- phase documents that are obsolete
- prompt files that overlap in purpose
- docs that should be archived or merged
- files that should probably be deleted after confirmation

4. Explain what is реально in use
Tell me:
- which files Copilot should always read
- which files it should read only for specific tasks
- which files are historical only
- which files should not be in default startup context

5. Explain what is already implemented in the product
Give a strict summary of the current real platform state:
- backend
- database
- frontend
- auth
- CRM boundary
- stewardship
- reservations/circulation
- testing/verification
- runtime scripts

6. Explain Obsidian / vault usage
Tell me:
- whether the vault is actually useful
- what should live there
- what should stay in repo
- how to use it without duplicating repo truth
- what must be synced back into repo if it matters for execution

7. Recommend a cleanup plan
Give a practical cleanup plan with categories:
- keep
- merge
- archive
- delete only after confirmation
- ignore for default agent startup

8. Recommend future extensibility
Explain how Copilot could later be extended with:
- MCP servers
- frontend tooling
- repo-aware automation
- developer workflow tools
- custom agent roles

9. End with a clear “what should I do next”
Give the single best next step for the project after cleanup.

Output format:
1. Executive summary
2. Project layers and their purpose
3. File classification table
4. Duplicates and conflicts
5. What Copilot should read by default
6. What Obsidian should contain
7. Cleanup plan
8. Extensibility plan for MCP/tools
9. Next best step

Be strict, practical, and specific.