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
- relevant catalog/discover/book/account routes, controllers, services, views, models, and tests

Task:
Implement the first teacher-oriented shortlist / syllabus-draft feature end-to-end.

This is a backend + frontend development task.
Do real implementation now.
Do not stop at analysis.

Primary goal:
Allow a teacher or authenticated user to collect relevant books/resources from catalog and book pages into a simple shortlist that can serve as the first draft of literature for a course/syllabus.

Implement all of the following:

## 1. Backend: shortlist persistence
Add a real shortlist mechanism.

Use the safest implementation that fits the current system:
- authenticated user-based shortlist if user identity/session model already supports it safely,
- otherwise session-based shortlist with a clean upgrade path.

Support:
- add item to shortlist
- remove item from shortlist
- list shortlisted items
- prevent duplicate entries
- keep item metadata sufficient for display/export-like use

Do not overbuild a full syllabus management module.
Do not invent complex approval workflows.

## 2. Backend: routes/controllers/services
Implement the required endpoints/routes for:
- adding a book/resource to shortlist
- removing it
- viewing shortlist
- optionally clearing shortlist if useful

Keep route and auth behavior consistent with the current app architecture.

## 3. Frontend: catalog integration
On catalog cards, add a clear action that allows the user to add/remove a book from shortlist.

Requirements:
- visually coherent with current design system
- clear state feedback
- no broken layout
- no duplicate additions
- work with existing catalog/filter flows

## 4. Frontend: book detail integration
On book detail page:
- add shortlist action
- show whether the item is already shortlisted
- keep current page quality and layout intact

## 5. Frontend: shortlist page
Create a real shortlist page for the user.

The page should:
- list selected books/resources
- show useful bibliographic information
- allow removing items
- show meaningful empty state
- provide a simple “draft literature list” style presentation
- if useful, include a copy-friendly/export-friendly plain text block or formatted bibliography-style block

Keep this lightweight, usable, and truthful.

## 6. Navigation / teacher flow integration
Connect the feature into the teacher-facing flow where appropriate:
- /for-teachers
- maybe account or nav/footer entry if justified
- discover/catalog/book path should naturally lead into shortlist usage

## 7. Keep logic stable
Do not break:
- canonical catalog route
- canonical book detail route
- classification/subject filtering
- guest/reader flow
- auth-sensitive navigation
- existing tests and public UX

## 8. Tests and verification
Add or update focused tests for:
- add/remove shortlist behavior
- duplicate prevention
- shortlist page rendering
- integration from catalog/book flow
- no regression in existing public routes

Be honest about environment limits, but do real verification where possible.

Output format:
1. Executive summary
2. Backend changes made
3. Frontend changes made
4. Shortlist behaviors now supported
5. Verification performed
6. Files updated
7. Remaining limitations
8. Next best step

Definition of done:
- A real shortlist feature exists end-to-end.
- Users can collect relevant books/resources from catalog/book pages.
- The shortlist can act as a first draft for syllabus literature selection.
- Backend and frontend are both implemented.
- Existing core flows remain intact.