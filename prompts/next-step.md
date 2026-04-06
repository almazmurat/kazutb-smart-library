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
- relevant routes, controllers, services, views, config files, and tests related to resources, for-teachers, shortlist, and public discovery

Task:
Implement the first real external licensed resources feature slice end-to-end.

This is a backend + frontend implementation task.
Do real development now.
Do not stop at analysis.

Primary goal:
Make external licensed resources a real first-class part of the product experience, clearly distinguished from the local library fund and usable in teacher/resource discovery flows.

Implement all of the following:

## 1. Backend: external resources data source
Introduce a real app-level data source for external licensed resources.

Use the safest practical implementation for the current repo state:
- config-backed or database-backed if clearly justified and low-risk,
- but make it structured, not hardcoded ad hoc in Blade templates.

Each resource should support fields such as:
- id or slug
- title
- provider / platform
- description
- access type / eligibility
- status
- expiry date if known
- url or launch target if appropriate
- category/type
- optional notes for teachers/research use

Do not invent fake contracts.
Use only known/truthful resources and clearly marked placeholders if absolutely necessary.

## 2. Backend: resource listing flow
Add the backend/controller/service support needed so the app can render external licensed resources as structured data.

Support at least:
- listing active resources
- distinguishing local resources vs external licensed resources
- exposing known access/expiry information when available

If useful and low-risk, add filtering by resource type/category.

## 3. Frontend: real external resources surface
Implement a real public-facing external resources experience.

This may be:
- a dedicated section on /resources,
- and/or a dedicated page if justified.

Requirements:
- visually coherent with the current design system
- clearly distinguish external licensed resources from the local library catalog
- clearly communicate access expectations
- avoid misleading users into thinking external resources are downloadable local assets

## 4. Frontend: teacher-facing integration
Integrate external licensed resources into the teacher flow.

From /for-teachers and/or related discovery surfaces, make it clearer that a teacher selecting literature/resources for a course can also use:
- licensed external electronic resources
- provider/platform-based resources
- research databases where available

This should be a real navigation/use-case improvement, not just decorative copy.

## 5. Frontend: shortlist-awareness where justified
If it is safe and coherent, allow external resources to participate in shortlist-related UX in a lightweight way.

This does NOT require full parity with local books.
But if a simple safe representation is possible, support it.
If not, leave a clear truthful boundary.

## 6. Keep core logic stable
Do not break:
- catalog flow
- book detail flow
- shortlist flow
- teacher-facing flow
- auth-sensitive navigation
- existing public routes
- responsive behavior

## 7. Tests and verification
Add or update focused tests for:
- external resources rendering/data flow
- known resource visibility
- teacher-facing integration
- no regression in catalog/shortlist/public routes

Be honest about limitations, but do real verification where possible.

Output format:
1. Executive summary
2. Backend changes made
3. Frontend changes made
4. External resource behaviors now supported
5. Verification performed
6. Files updated
7. Remaining limitations
8. Next best step

Definition of done:
- External licensed resources are now represented as a real product feature, not just vague copy.
- Backend and frontend are both implemented.
- Teacher/resource discovery now includes external resources in a meaningful way.
- Existing core flows remain intact.