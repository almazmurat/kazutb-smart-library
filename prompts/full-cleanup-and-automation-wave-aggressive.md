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
- `composer.json`

Context:

The repository has already completed substantial normalization work:
- truth-layer normalization,
- startup/session script additions,
- context drift and vault automation helpers,
- public catalog convergence classification and first implementation refinement,
- bounded Obsidian mirror architecture.

This task is a deliberately aggressive cleanup and automation execution wave.

The goal is not just to analyze or propose.
The goal is to actively implement a large, meaningful repository cleanup and workflow automation pass now.

High-level target outcome:

By the end of this task:
- the repository should be significantly cleaner,
- the startup and next-step flow should be explicit and low-friction,
- duplicated workflow/prompt/doc authority should be reduced,
- prompt/tooling layers should be clearly separated,
- Obsidian mirror workflow should be operational and useful,
- unnecessary repo noise should be reduced,
- future AI-agent execution should be much easier,
- but canonical repo truth and runtime behavior must remain intact.

Execution bias:

- Prefer action over commentary.
- Prefer updating and consolidating existing files over adding more documents.
- Prefer moving/merging/annotating/archive-normalizing over endless planning.
- If a file is clearly redundant, misleading, or superseded, do not leave it untouched just to be conservative.
- Reduce ambiguity aggressively where the intent is clear.
- Update references immediately when moving or merging files.
- Make the repository easier to operate in one substantial pass.

Critical safety boundaries:

- Do not break runtime product behavior.
- Do not remove canonical execution truth from the repository.
- Do not make Obsidian execution authority.
- Do not change CRM-facing API scope.
- Do not perform reckless deletions when usage is unclear.
- If something may still be used, freeze/annotate/archive instead of deleting.
- If a delete is clearly safe and well-justified, you may perform it.
- If a move/merge is clearly safe and improves coherence, perform it now.
- If documentation is duplicated, consolidate it now.
- If prompts are duplicated or confusing, normalize them now.
- If references point to superseded locations, update them now.

Main task:

Perform one large aggressive-but-safe cleanup and automation wave across the repo and knowledge workflow system.

What to do:

## 1. Fully normalize startup and session flow
Actively make the startup/session workflow explicit and operational.

Ensure there is one coherent flow for:
- startup context
- next-step task entry
- session start
- session closeout
- context drift checking
- vault export/index sync

If multiple files/scripts/docs overlap in purpose, consolidate them.
If scripts exist but are not well surfaced, wire them into the visible workflow.
If prompts are unclear, rewrite them.

Make the future operator experience straightforward:
enter repo → see startup context → run session start → read next step → execute → close out → sync knowledge.

## 2. Aggressively normalize prompt layers
Resolve confusion between:
- `prompts/`
- `.github/prompts/`
- `.github/instructions/`

Desired outcome:
- `prompts/` = canonical operational prompts
- `.github/prompts/` = thin shortcut/adaptor layer only
- `.github/instructions/` = scoped behavior rules only

Actions allowed:
- rewrite prompt files,
- merge duplicate prompts,
- move misplaced prompts,
- archive clearly obsolete prompts,
- annotate thin adapter prompts,
- remove duplicated prompt authority where clearly safe,
- update references everywhere.

Do not preserve duplication just because it already exists.

## 3. Aggressively normalize workflow docs
Find overlapping workflow docs and reduce them now.

If two or more docs try to explain the same workflow:
- merge them,
- choose one canonical location,
- update references,
- archive or remove the redundant copy if clearly safe.

Examples include:
- `docs/DEVELOPER_COPILOT_WORKFLOW.md`
- `docs/developer/AI_WORKFLOW.md`
- startup/session automation docs
- any duplicated "how to use agents" instructions

The result should be a clear authority structure, not a museum of prior drafts.

## 4. Clean root-level repo noise
Audit root-level docs/scripts/artifacts and act on them now.

For root-level non-runtime artifacts:
- move them to the correct location if obviously misplaced,
- archive clearly historical items,
- remove clearly superseded low-value artifacts if safe,
- update references immediately.

Do not leave root clutter in place if the correct destination is clear.

## 5. Make Obsidian mirror workflow truly usable
Using the bounded mirror model, actively implement a practical vault workflow.

Improve and use:
- `scripts/dev/export-context-to-vault.sh`
- `scripts/dev/sync-vault-index.sh`
- related session scripts if needed

Create or update a usable vault note skeleton in:
`/home/admlibrary/knowledge/kazutb-library-vault`

Only create high-value notes, not bulk noise.

At minimum, ensure the vault has a coherent graph/navigation starter set for:
- project home
- current state
- target state
- current workstreams
- next step
- decisions
- work logs
- repo truth map
- archive boundary

All mirrored notes must clearly indicate they mirror or summarize repo truth.

## 6. Generate a useful knowledge skeleton, not note spam
Actively create/update the smallest high-value Obsidian note system that helps the owner think and navigate.

Good notes:
- are graph-friendly,
- link to each other,
- point back to canonical repo truth,
- separate mirror vs synthesis,
- support future sessions.

Bad notes:
- duplicate everything blindly,
- restate large files with no value,
- create alternate truth,
- flood the vault with low-signal pages.

Bias toward a small sharp system, not a huge noisy one.

## 7. Reduce repo ambiguity aggressively
Where ambiguity exists in docs/prompts/scripts:
- add explicit canonical/frozen/legacy annotations,
- fix misleading names if clearly justified,
- update helpers and docs to point to canonical flow,
- remove stale references,
- freeze obsolete usage where deletion is not yet safe.

If some docs or prompts are clearly not needed anymore after consolidation, you may archive them or remove them if safety is high and references are fully updated.

## 8. Make the automation layer operational
Ensure the automation is not theoretical.

By the end:
- startup helpers should surface the truth layer,
- next-step prompt should be canonical and usable,
- session start/closeout should actually support real work,
- vault export/index sync should be wired into the routine,
- Composer helpers should expose the useful operations.

If commands are missing but obviously useful, add them.
If command names are confusing, normalize them.
If scripts overlap, simplify them.

## 9. Update references globally
After making changes:
- update README if startup/workflow meaning changed,
- update prompt references,
- update doc references,
- update script references,
- update Composer scripts if needed,
- ensure no major path still directs users to superseded workflow locations.

Do not leave the repo half-normalized.

## 10. Perform safe cleanup, not endless postponement
If something is clearly safe to remove after consolidation and reference updates, remove it now.
If it is not clearly safe, do not remove it; instead mark it for follow-up.

Use this decision order:
1. canonicalize
2. merge
3. move
4. archive
5. delete if clearly safe
6. otherwise leave annotated with follow-up status

## 11. End with a strict completion report
At the end, report:

- what was actually changed,
- what was merged,
- what was moved,
- what was archived,
- what was deleted,
- what was mirrored to vault,
- what remains transitional,
- what still needs manual review,
- what the next agent should do next.

Preferred concrete deliverables to create/update if justified:

- `docs/developer/FULL_SYSTEM_NORMALIZATION_PLAN.md`
- `docs/developer/OBSIDIAN_VAULT_ARCHITECTURE.md`
- `docs/developer/AGENT_AUTOMATION_WORKFLOW.md`
- `docs/developer/STARTUP_AND_SESSION_FLOW.md`
- `prompts/next-step.md`
- `scripts/dev/show-startup-context.sh`
- `scripts/dev/session-start-checklist.sh`
- `scripts/dev/session-closeout.sh`
- `scripts/dev/check-context-drift.sh`
- `scripts/dev/export-context-to-vault.sh`
- `scripts/dev/sync-vault-index.sh`
- selected starter notes under `/home/admlibrary/knowledge/kazutb-library-vault`

Do not create duplicates if equivalent canonical files already exist.
Prefer consolidation over expansion.

Output format:

1. Executive summary
2. What was implemented
3. Files merged/moved/archived/deleted
4. Canonical operating model after changes
5. Repo truth vs Obsidian boundary after changes
6. Vault notes created/updated
7. Verification performed
8. Remaining transitional debt
9. Next best step

Definition of done:

- A large real cleanup and automation wave has been executed.
- Startup/session/next-step flow is clearer and lower-friction.
- Prompt/doc authority is less duplicated.
- Obsidian mirror workflow is operational and bounded.
- The repo is meaningfully cleaner.
- References are updated.
- No canonical truth or runtime behavior was broken.