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
- `README.md`
- `docs/developer/REPO_NORMALIZATION_PLAN.md`
- `docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md`
- `docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md`
- `docs/developer/AGENT_AUTOMATION_WORKFLOW.md`
- `docs/developer/PUBLIC_CATALOG_CONVERGENCE_AUDIT.md`
- `prompts/next-step.md`
- all relevant files in `prompts/`
- all relevant files in `.github/prompts/`
- all relevant files in `.github/instructions/`
- all relevant files in `scripts/dev/`
- all relevant files in `docs/`
- all relevant files in `docs/archive/`
- current Composer scripts in `composer.json`

Context:

The repository has already completed:
- truth-layer normalization,
- README cleanup,
- startup/closeout helper flows,
- context drift and vault sync helpers,
- public catalog convergence classification,
- a first narrow convergence refinement,
- bounded Obsidian mirror architecture.

The next task is to perform one larger full cleanup and automation wave so the project becomes significantly cleaner and more self-operating for future AI-agent sessions.

Target outcome:

When entering the development environment:
- there is one obvious startup path,
- there is one obvious next-step path,
- the repo truth layer is clear,
- prompts and scripts are disciplined,
- duplicated docs are reduced,
- Obsidian mirror/index workflow is usable,
- agent session flow is operational,
- transitional and legacy repo noise is reduced where safely possible,
- the system becomes easier for the owner to delegate to agents.

Critical guardrails:

- Do not make Obsidian the execution source of truth.
- Repo truth remains canonical for execution.
- Obsidian is the primary human knowledge workspace and graph, but not authority.
- Do not remove canonical repo truth.
- Do not delete aggressively without clear justification.
- If deletion risk is non-trivial, prefer archive/freeze/annotation over deletion.
- Do not break runtime product behavior.
- Do not expand CRM-facing API scope.
- Do not introduce speculative features unrelated to cleanup/automation.
- Prefer a large but safe wave over reckless "perfect cleanup."

Main task:

Execute a full cleanup and automation wave across the repository and workflow system, implementing as much as is safely justified in one pass.

What to do:

## 1. Normalize the startup and next-step flow completely
Ensure there is one clear entrypoint for each of these:

- startup context
- current task / next step
- session start
- session closeout
- vault sync / mirror update
- context drift check

If multiple overlapping files or scripts exist for the same purpose, consolidate or subordinate them.

Make the flow easy enough that a future session can begin with minimal ambiguity.

## 2. Resolve prompt-layer confusion
Fully normalize the relationship between:
- `prompts/`
- `.github/prompts/`
- `.github/instructions/`

Goal:
- `prompts/` = canonical operational prompts
- `.github/prompts/` = thin IDE/Copilot adapters or shortcuts
- `.github/instructions/` = scoped file/directory behavior guidance

Actions:
- remove duplication where safely possible,
- rewrite or annotate misleading prompt files,
- add explicit boundary docs if needed,
- archive or freeze obsolete prompt artifacts,
- update references so the system is coherent.

## 3. Merge or rewrite overlapping workflow docs
Find overlapping workflow/process docs and reduce them.

Examples may include:
- `docs/DEVELOPER_COPILOT_WORKFLOW.md`
- `docs/developer/AI_WORKFLOW.md`
- other workflow/process guidance that duplicates repo truth or operational instructions

Goal:
- one canonical developer/agent workflow doc,
- any superseded docs either archived or clearly marked legacy,
- no duplicate authority.

If safe, perform the merge now and update references.
If not safe, produce the minimum changes needed to make authority unambiguous.

## 4. Clean root-level and misplaced artifacts
Audit root-level docs/scripts/artifacts and normalize placement.

For each root-level non-runtime artifact, decide:
- keep in root,
- move to docs/developer,
- move to docs/archive,
- move to scripts/dev,
- archive,
- delete after confirmation

Perform safe moves now where clearly justified.
Do not move runtime-critical files incorrectly.

## 5. Harden the Obsidian mirror workflow
Using the already established bounded-mirror model:

- improve the vault export/index scripts if needed,
- ensure the vault gets a usable project landing/index structure,
- ensure mirrored knowledge is clearly marked as mirrored from repo truth,
- ensure there is a current-session note pattern,
- ensure there is a next-step note pattern,
- ensure there is a decision/worklog structure,
- ensure graph-friendly notes exist for core project understanding.

If justified, create/update a minimal starter vault content set in the external vault path:
`/home/admlibrary/knowledge/kazutb-library-vault`

But do not copy everything blindly.
Mirror only the highest-value canonical context and useful session/navigation structures.

## 6. Generate the core knowledge graph skeleton
Create or update a bounded set of Obsidian-facing notes that represent:

- what the project is,
- why it exists,
- target end-state of the project,
- current stage,
- active workstreams,
- domain boundaries,
- architecture map,
- API boundary map,
- current next step,
- session workflow,
- decision log,
- work log index,
- archive boundary.

These notes must:
- link back to canonical repo truth,
- clearly distinguish mirrored truth from human synthesis,
- support graph navigation,
- not replace repo truth.

## 7. Reduce convergence and doc/tooling debt where safe
Perform additional safe cleanup where clearly justified, including:
- annotations on legacy/transitional files,
- freezing obsolete usage,
- removing duplicate references,
- fixing startup docs that still point to outdated locations,
- tightening helper scripts or Composer commands,
- improving the clarity of canonical-vs-legacy boundaries.

If a delete-after-confirmation candidate is extremely obvious and low-risk, you may either:
- leave it as a documented candidate, or
- remove it only if you can justify safety clearly.

Bias toward safety.

## 8. Make the session workflow real
By the end of this task, the project should support a realistic session flow like:

1. show startup truth/context
2. run session start
3. read current next-step prompt
4. execute task
5. sync vault mirrors / logs
6. run closeout

If this requires improving or adding scripts/notes/docs, do so.

## 9. Update references everywhere
After changes:
- update docs that point to old workflow locations,
- update script references,
- update README if startup workflow changed meaningfully,
- update prompts if canonical flow changed,
- ensure no major file still points users toward superseded workflow paths.

## 10. Produce a complete status report
At the end, report clearly:

- what was normalized,
- what was merged,
- what was moved,
- what was archived,
- what was mirrored to the vault,
- what was intentionally left transitional,
- what still needs manual review,
- what should be deleted later only after confirmation,
- what the next future agent should do.

Preferred concrete deliverables to create/update if justified:

- `docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md`
- `docs/developer/AGENT_AUTOMATION_WORKFLOW.md`
- `docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md`
- `docs/developer/STARTUP_AND_SESSION_FLOW.md`
- `prompts/next-step.md`
- `scripts/dev/show-startup-context.sh`
- `scripts/dev/session-start-checklist.sh`
- `scripts/dev/session-closeout.sh`
- `scripts/dev/check-context-drift.sh`
- `scripts/dev/export-context-to-vault.sh`
- `scripts/dev/sync-vault-index.sh`
- selected starter notes in `/home/admlibrary/knowledge/kazutb-library-vault` if safe and justified

Do not create duplicates if equivalent canonical files already exist.
Prefer updating and consolidating existing files.

Output format:

1. Executive summary
2. What was changed
3. Canonical operating model after cleanup
4. Repo truth vs Obsidian boundary after cleanup
5. Files created/updated/moved/archived
6. Safe deletions performed or still pending confirmation
7. Verification performed
8. Remaining transitional debt
9. Recommended next best step

Definition of done:

- Startup and next-step flow are clearly normalized.
- Prompt layers are no longer confusing.
- Workflow docs have a clear canonical authority.
- Obsidian mirror workflow is usable and bounded.
- The repo is cleaner and more automatable.
- The system is easier for future agents and the owner to operate.
- No reckless destructive cleanup was done.