Start by reading AGENT_START_HERE.md and all current project-context files.

Task:
Perform a strict repository normalization audit for the KazUTB Smart Library project. The goal is to clean up the repository structure so Copilot and developers can understand exactly what is source of truth, what is execution tooling, what is reference/history, and what should be removed or archived.

This is not a coding task.

Important:
- Be strict and repository-grounded.
- Do not assume all markdown/docs files are useful.
- Do not treat Obsidian as source of truth.
- Do not treat every phase doc as current.
- Separate active operational files from historical artifacts.
- Distinguish between repo truth, agent instructions, developer workflows, docs, prompts, scripts, archive, and future extensibility.
- Be practical and specific.

What to do:

1. Map the repository layers
Explain what each of these layers is for and how it should be used:
- .github
- AGENT_START_HERE.md
- project-context/
- prompts/
- scripts/dev/
- docs/
- docs/archive/
- app/
- routes/
- database/
- tests/
- resources/
- config/
- Obsidian vault / knowledge graph

2. Classify every important file or file group
For each important file or group, label it as:
- source of truth
- active working doc
- operational script
- prompt template
- historical archive
- redundant duplicate
- obsolete
- should be deleted after confirmation
- should be ignored for default agent startup

3. Find duplication and conflict
Look for:
- duplicate or legacy master context files
- overlapping workflow docs
- prompt duplication between repo prompt layers
- phase docs that are no longer current
- root-level artifacts that should live in archive or scripts
- any file naming that misleads agents

4. Decide what should be canonical
For each major area, identify the single canonical file or location:
- master project context
- current stage / focus
- workflow instructions
- prompt templates
- developer scripts
- archived historical docs
- knowledge vault boundary

5. Explain what Copilot should always read vs only read on demand
Create two lists:
- default startup context
- task-specific context

6. Explain what the Obsidian vault should and should not contain
Be explicit about:
- what should remain in repo
- what can be mirrored to vault
- what must never be used as execution truth

7. Recommend cleanup actions
Provide a practical plan grouped by:
- keep
- merge
- archive
- delete after confirmation
- ignore for default startup

8. Recommend future extensibility
Explain how the project can later expand Copilot capability with:
- MCP servers
- custom agents
- frontend tooling
- test automation
- repo-aware context tools

9. End with a concrete next step
Give the single best next action after the cleanup audit.

Output format:
1. Executive summary
2. Repository layer map
3. Canonical file map
4. Duplication/conflict analysis
5. Default vs task-specific agent context
6. Obsidian boundary rules
7. Cleanup plan
8. Extensibility plan for MCP/tools/agents
9. Next best step

Be strict, practical, and specific. Avoid generic advice.