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
- all relevant navbar/footer/shared partials
- all relevant public Blade views
- any shared CSS/public styling files
- any SPA/public UI files that affect the public experience
- recent docs or prompts related to public UI, frontend polish, public catalog, and academic discovery

Context:
The current public frontend has improved incrementally, but it still does not feel like a coherent modern product.
Some sections are visually left-heavy, spacing is inconsistent, footer quality is weak, typography is uneven, and the overall public design still feels patchy rather than system-driven.

The goal now is not another micro-polish pass.
The goal is to execute a large, design-system-driven public frontend consolidation and refinement wave.

Important:
- Do not treat this as “just tweak some CSS”.
- Do not do random visual patching.
- Do not invent speculative backend features.
- Do not break working routes, auth state, or canonical catalog behavior.
- Where possible, use the strongest available tool-assisted workflow and modern frontend implementation approach available in this repository/tooling environment.
- Prefer coherent system-level improvement over scattered local fixes.

Primary goals:

1. Bring all public-facing pages to a coherent modern, minimal, premium visual system.
2. Fix alignment/composition issues where cards and sections feel left-stuck or visually unbalanced.
3. Redesign the footer into a polished, modern, information-rich but clean footer.
4. Normalize typography, spacing, card layout, section rhythm, and CTA hierarchy across public pages.
5. Preserve working functionality and key flows:
   - public catalog
   - book detail
   - auth/login/account access logic
   - navigation
   - responsive behavior
6. Make the academic discovery direction feel intentional and product-quality, not placeholder-like.

What to do:

## 1. Audit the full public UI as a system
Inspect the shared public experience across:
- home page
- catalog
- book detail
- login/auth
- account entry/access-related surfaces
- services
- about
- contacts
- resources
- news
- shared navbar
- shared footer
- shared layout/shell styles

Identify the main system-level design issues such as:
- misaligned or left-heavy cards/sections
- weak section centering/composition
- inconsistent container widths
- inconsistent vertical rhythm
- weak typography hierarchy
- uneven card sizing and spacing
- poor footer design
- inconsistent button and CTA treatment
- pages that feel visually disconnected from the rest of the site

## 2. Build or normalize a public design system layer
Create or strengthen a shared public UI system so pages use common rules for:
- max-width containers
- section spacing
- hero layout
- heading scale
- body text scale
- card padding/radius/shadow
- grid behavior
- footer structure
- button hierarchy
- empty states / notice blocks
- responsive breakpoints

Prefer shared reusable styling over repeated local hacks.
If existing shared CSS is insufficient, improve the shared layer instead of endlessly patching each page separately.

## 3. Refactor page layouts to feel centered, balanced, and premium
Fix the visible layout/composition issues across the public pages.

Examples of the desired improvements:
- cards that visually belong to centered grid systems
- sections that align to the same container logic
- more balanced whitespace
- better relationship between hero blocks and content blocks
- less “content glued to the left edge”
- more polished section transitions
- stronger visual coherence between pages

## 4. Redesign the footer properly
The footer should no longer feel generic or weak.

Make it:
- visually stronger,
- cleaner,
- better spaced,
- more typographically polished,
- aligned with the rest of the product,
- helpful for key audiences,
- consistent with the academic-library direction.

It should support:
- students,
- teachers,
- academic resources,
- services,
- about/contact/help,
without becoming noisy.

## 5. Improve typography and detail quality
Normalize:
- heading weights/sizes
- paragraph readability
- muted/supporting text styles
- card titles and metadata
- button labels
- footer/link readability
- spacing around icons and labels
- overall polish of fine details

The result should feel modern and intentional, not like default CSS plus gradients.

## 6. Keep logic and flows correct
Preserve and verify:
- authenticated vs guest navbar behavior
- login redirect behavior
- catalog route behavior
- book detail route behavior
- responsive behavior on desktop/tablet/mobile
- footer/navigation links
- no contradictory CTAs

## 7. Use the strongest implementation approach available
If this repository/tool environment supports better tooling, more structured shared styling, or MCP-assisted frontend work, use that intelligently.
The objective is a better end result, not loyalty to manual CSS patching.
However:
- do not introduce uncontrolled framework churn,
- do not rewrite the app into a new frontend architecture unless clearly justified,
- do not create speculative dependencies without operational value.

## 8. Perform a substantial implementation wave now
Do not stop at analysis.
Make the actual frontend/system changes now.

Prefer:
- shared layout improvements,
- shared style consolidation,
- footer redesign,
- page composition refactors,
- typography cleanup,
- consistent card/grid behavior,
- responsive refinement.

## 9. Verify the result
Perform practical verification such as:
- key public routes render correctly,
- auth-sensitive navigation is correct,
- no major layout regressions,
- responsive structure is coherent,
- no broken links or obvious UI contradictions.

Be honest about environment limits if full runtime/device verification is not possible.

Output format:
1. Executive summary
2. System-level design issues found
3. Shared design-system improvements implemented
4. Pages/components substantially improved
5. Footer redesign summary
6. Logic/UX correctness checks
7. Verification performed
8. Files updated
9. Remaining known limitations
10. Next best step

Definition of done:
- The public site feels substantially more coherent, modern, minimal, and premium.
- Layouts are better balanced and not awkwardly left-heavy.
- Footer quality is significantly improved.
- Typography and spacing feel intentional across pages.
- Existing public functionality and key logic remain intact.
- The result is a real frontend consolidation wave, not another patch-only pass.