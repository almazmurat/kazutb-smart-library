Start by reading these files first, before any planning or changes:

- `AGENT_START_HERE.md`
- `.github/copilot-instructions.md`
- `project-context/00-project-truth.md`
- `project-context/01-current-stage.md`
- `project-context/02-active-roadmap.md`
- `project-context/03-api-contracts.md`
- `project-context/04-known-risks.md`
- `project-context/05-agent-working-rules.md`
- `project-context/06-current-focus.md`
- `project-context/98-product-master-context.md`
- `docs/developer/REPO_NORMALIZATION_PLAN.md`
- `README.md`
- `prompts/next-step.md` if it exists
- all relevant files under `scripts/dev/`
- all relevant files under `prompts/`
- all relevant files under `.github/prompts/`
- all relevant files under `.github/instructions/`
- all relevant files under `docs/`
- all relevant files under `docs/archive/`
- any current Obsidian-related folder, vault folder, notes folder, or knowledge-graph-related folder if present in or near the repository

Context and intent:

The goal is to fully normalize, clean up, and automate the working system around the KazUTB Smart Library repository so that:
- the repository becomes cleaner and easier for agents and developers to use,
- the execution flow becomes highly agent-friendly,
- the next-step workflow becomes explicit,
- the knowledge system becomes organized and explorable,
- Obsidian becomes the primary knowledge workspace and graph view for human understanding,
- but repository truth remains the execution source of truth unless an explicit safe boundary is defined and justified.

This is not a generic cleanup task.
This is a repository- and workflow-normalization task for a real evolving product codebase.

Critical guardrails:

- Do not treat Obsidian as execution truth by default.
- Do not move canonical execution truth out of the repository unless you explicitly justify why it is safe.
- Do not destroy the current truth structure unless you first map exactly what replaces it.
- Do not assume every markdown file is useful.
- Do not assume every doc should survive.
- Do not assume every prompt should survive.
- Do not assume every script should survive.
- Do not delete aggressively before building a canonical map.
- Preserve product truth, stage truth, current focus, API boundary truth, and agent working rules.
- Be strict, practical, and repository-grounded.
- If previous audits conflict with implemented reality, implemented reality must be identified and the mismatch must be documented.
- Avoid generic “knowledge management” advice. Work with the actual repository.

Main objective:

Design and execute a complete repository cleanup + agent workflow automation + Obsidian knowledge-graph integration plan so that the project ends up with:

1. a minimal clear execution truth layer inside the repository,
2. a clean operational tooling layer for prompts/scripts/automation,
3. a well-bounded reference/archive layer,
4. a disciplined Obsidian vault that mirrors and structures knowledge without becoming an unsafe competing truth source,
5. a clear “next action” entrypoint for each new work session,
6. a repeatable agent-driven workflow for future work,
7. reduced repository noise, duplicates, stale artifacts, and misleading docs.

What to do:

## 1. Perform a strict full-system audit
Map and evaluate all major layers:

- `AGENT_START_HERE.md`
- `project-context/`
- `.github/copilot-instructions.md`
- `.github/prompts/`
- `.github/instructions/`
- `prompts/`
- `scripts/dev/`
- `docs/`
- `docs/archive/`
- root-level markdown/docs/scripts/artifacts
- `README.md`
- Obsidian vault or vault-like notes structure
- runtime code directories for reference:
  - `app/`
  - `routes/`
  - `resources/`
  - `config/`
  - `database/`
  - `tests/`

For each layer explain:
- what it is for,
- whether it is canonical,
- whether it is active,
- whether it is redundant,
- whether it should stay in repo,
- whether it should be mirrored to Obsidian,
- whether it should be archived,
- whether it should be deleted after confirmation,
- whether it should be ignored during default agent startup.

## 2. Define the final target system
Describe the desired final normalized working model for this project.

This must include:

### A. Execution truth layer in repo
Identify the minimum required canonical files that must remain inside the repository as the source of execution truth.

### B. Operational tooling layer
Define the clean final structure for:
- prompts
- scripts/dev
- .github prompts/instructions
- next-step orchestration
- automation helpers
- session-start/session-closeout helpers
- verification helpers

### C. Reference/archive layer
Define what belongs in:
- docs/
- docs/archive/
- historical notes
- decisions
- old phase materials

### D. Obsidian knowledge layer
Define the exact intended role of the Obsidian vault.

Be explicit about:
- what it should contain,
- what it may mirror,
- what it should synthesize,
- what it must never become,
- how it should link back to canonical repo files,
- how the graph should be organized.

## 3. Create a canonical knowledge architecture for Obsidian
Design a practical Obsidian vault structure for this project.

Include recommended top-level folders such as examples like:
- `00 Inbox`
- `01 Current Session`
- `02 Project Map`
- `03 Product Context`
- `04 Architecture`
- `05 Domain`
- `06 Workstreams`
- `07 Decisions`
- `08 Work Logs`
- `09 Reference Mirrors`
- `10 Archive`

Adjust these names if a better structure fits the project.

For the Obsidian vault, define:
- the main landing note,
- the project index note,
- the current-next-step note,
- the product overview note,
- the target-state note,
- domain/functionality map notes,
- workstream notes,
- decision log note,
- automation map note,
- archive index note.

Also define linking conventions and tagging conventions if useful.

## 4. Map the full project knowledge model
Build the knowledge map the vault should ultimately contain, including:
- what the project is,
- why it exists,
- what the end-state product should become,
- current stage,
- current risks,
- functional contours,
- integrations,
- domain boundaries,
- workflow rules,
- active workstreams,
- next actions,
- cleanup decisions,
- automation decisions,
- runtime verification strategy,
- archive/history boundaries.

Do not invent product truth.
Derive it from repository truth and clearly separate:
- current truth
- inferred structure
- historical notes
- future direction

## 5. Decide what should be kept in repo vs mirrored to Obsidian
For every major context/doc/prompt/script group, classify into:
- canonical in repo
- mirrored to Obsidian
- Obsidian-only human note
- archive in repo
- delete after confirmation
- ignore by default

Be extremely explicit about boundaries.

## 6. Build a full cleanup plan
Provide a concrete practical cleanup plan grouped by:
- keep
- rewrite
- merge
- move
- archive
- mirror to Obsidian
- generate automatically
- delete after confirmation
- never read by default
- never let agents use as truth

Include path-specific recommendations.

## 7. Build a full automation plan
Design the agent workflow so that the user can enter the development environment and immediately know what happens next.

Target outcome:
- there is one obvious next-step entrypoint,
- the current task is visible,
- the startup context is visible,
- the agent can be pointed to a single prompt or control file,
- the system can generate/update knowledge notes,
- the system can maintain session logs,
- the system can keep repo truth and Obsidian mirrors aligned.

Recommend a practical automation system including things like:
- `prompts/next-step.md`
- `scripts/dev/show-startup-context.sh`
- `scripts/dev/session-start-checklist.sh`
- `scripts/dev/session-closeout.sh`
- `scripts/dev/export-context-to-vault.sh`
- `scripts/dev/sync-vault-index.sh`
- `scripts/dev/check-context-drift.sh`

Only recommend scripts that are actually useful and realistically maintainable.

## 8. Implement the safe first wave
Do not attempt infinite cleanup in one step.
Implement the first safe high-value wave only.

This may include:
- creating/updating a canonical next-step prompt,
- creating/updating an Obsidian vault structure guide,
- creating an Obsidian project index note,
- creating a repo-to-vault mirroring script,
- creating a context drift check script,
- creating a session automation script,
- rewriting or moving misleading root docs,
- creating a single workflow doc for human + agent use.

But do not perform destructive deletions unless they are clearly safe and justified.

## 9. Produce the deliverables
Create or update whichever of these are justified:

- `docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md`
- `docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md`
- `docs/developer/AGENT_AUTOMATION_WORKFLOW.md`
- `prompts/next-step.md`
- `prompts/full-system-normalization-and-automation.md`
- `scripts/dev/export-context-to-vault.sh`
- `scripts/dev/sync-vault-index.sh`
- `scripts/dev/check-context-drift.sh`
- `scripts/dev/session-start-checklist.sh`
- `scripts/dev/session-closeout.sh`
- `vault/` or another clearly named Obsidian-facing directory only if justified
- starter notes for the vault if justified

If an Obsidian vault location should live outside the repo, explain the recommended path and how the scripts should work with it.

## 10. End with a strict status report
At the end, report:
- what is now canonical,
- what remains transitional,
- what was created,
- what should still be manually reviewed,
- what should be deleted only after confirmation,
- what the next agent should do next.

Output format:

1. Executive summary
2. Final target operating model
3. Repo truth vs Obsidian boundary
4. Full repository classification
5. Obsidian vault architecture
6. Cleanup plan
7. Automation plan
8. Concrete files created/updated
9. Safe deletions requiring confirmation
10. Next best step

Definition of done:

- The project has a clear normalized operating model.
- The role of Obsidian is defined clearly and safely.
- The repo keeps only the minimum canonical execution truth necessary.
- The next-step workflow is explicit.
- Cleanup is structured and not reckless.
- Automation is practical, not speculative.
- The system is easier for future AI agents and for the human owner to operate.