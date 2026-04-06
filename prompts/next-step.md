Start by reading:
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
- all relevant shared public layout files
- all relevant shared CSS/public shell files
- all relevant navbar/footer/shared partials
- all public Blade views
- recent prompts/docs related to frontend polish, academic discovery, public UI, and design-system consolidation

Context:
A substantial design-system consolidation wave has already been completed.
The public frontend is stronger now, but the next step is to harden overall quality, consistency, composition, and academic product clarity.

This is not a broad redesign from scratch.
This is a strict quality-hardening and consistency pass across the public reader-facing experience.

Primary goals:
1. Remove remaining visual inconsistencies across public pages.
2. Improve composition, centering, spacing rhythm, and section balance where pages still feel awkward or uneven.
3. Strengthen typography hierarchy and fine-detail polish.
4. Improve content clarity so pages feel less placeholder-like and more product-ready.
5. Make the academic discovery direction clearer and more useful without inventing unconfirmed institutional structure.
6. Preserve working auth/catalog/account/navigation behavior.

What to do:

## 1. Perform a strict public UI quality audit
Audit all public reader-facing pages as one product surface:
- home
- catalog
- book detail
- login
- services
- about
- contacts
- resources
- news
- shared navbar/footer/layout

Look for:
- weak composition
- awkward centering or left-heavy layouts
- inconsistent spacing rhythm
- typography mismatch
- weak empty states or weak supporting text
- sections that still feel temporary
- places where cards/blocks do not visually belong to the same system
- footer/detail inconsistencies
- poor content hierarchy or weak CTA framing

## 2. Improve layout and composition where still needed
Make targeted but meaningful improvements to:
- section width logic
- centering and balance
- content grouping
- card alignment
- vertical rhythm
- section transitions
- CTA placement
- readability and visual flow

## 3. Polish typography and micro-details
Refine:
- heading scale consistency
- paragraph width/readability
- muted/support text
- metadata text
- link styles
- button label consistency
- section eyebrow/label use
- icon/text spacing
- small visual details that still feel rough

## 4. Strengthen academic product clarity
Improve page content/copy/navigation so the site feels more clearly oriented toward:
- students
- teachers
- academic resource discovery
- syllabus/resource preparation
- library services and electronic resources

Important:
Do not hardcode unverified real university structure as final truth.
Keep this future-compatible and product-coherent.

## 5. Remove temporary-feeling UI/content where justified
If some public text blocks or UI structures still feel like placeholders, weak stubs, or transitional content, improve them now in a truthful way.

Do not overclaim.
Do not fabricate institutional facts.
But do improve weak wording, weak hierarchy, and weak presentation.

## 6. Keep shared-system discipline
Prefer shared improvements over scattered page hacks.
If the same refinement is needed in multiple places, strengthen the shared design layer.

## 7. Verify key flows
Verify:
- auth-sensitive nav behavior
- public routes
- footer/nav consistency
- no broken CTA paths
- responsive integrity
- no obvious regressions in catalog/book/account entry flows

Output format:
1. Executive summary
2. Quality issues found
3. Layout/composition improvements
4. Typography/detail improvements
5. Academic clarity/content improvements
6. Verification performed
7. Files updated
8. Remaining known limitations
9. Next best step

Definition of done:
- Public pages feel more polished, coherent, and product-ready.
- Remaining rough edges are reduced meaningfully.
- Academic discovery direction feels clearer.
- Shared system quality improves rather than isolated hacks multiplying.
- Existing working flows remain intact.