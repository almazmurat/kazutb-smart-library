# Current State — KazUTB Library Platform
> Last updated: 2026-04-20

## Last changed
- Time: 2026-04-20 13:38:43 UTC
- Commit: 61b9285
- Branch: main
- Change type: UI/Blade view change — ADMIN PANEL
- Files: resources/views/admin/overview.blade.php, resources/views/admin/placeholder.blade.php, resources/views/layouts/admin.blade.php, routes/web.php
- Commit message: feat: implement admin overview governance shell

## Latest Git Automation
- Time: 2026-04-20 13:38:43 UTC
- Event: post-commit
- Branch: main
- Commit: 61b9285
- Update: Git post-commit on main: feat: implement admin overview governance shell
- Detail: Changed files: app/Http/Middleware/EnsureAdminStaff.php, bootstrap/app.php, resources/views/admin/overview.blade.php, resources/views/admin/placeholder.blade.php, resources/views/layouts/admin.blade.php, routes/web.php, tests/Feature/AdminOverviewPageTest.php
- Semantic: UI/Blade view change — ADMIN PANEL
- Links: [[TASK_LOG]], [[GRAPH_INDEX]]

## Project Phase
The project is in the institutional build-out phase: the canonical product model is defined, the public and member anchor surfaces are already implemented, and the next major delivery focus is the real admin overview and admin shell.

## What Is Done
- Canonical product truth is consolidated in [[PROJECT_CONTEXT]]
- Library-centered Laravel and PostgreSQL architecture is established
- Public homepage, secure access login, and member dashboard are already implemented as anchor surfaces
- CRM-backed authentication remains library-hosted and conservative
- Public catalog, availability, shortlist direction, and internal stewardship foundations are in place
- The vault itself has been rebuilt into a clean canonical structure

## What Is In Progress
- Admin overview and admin shell definition and implementation sequencing
- Staff-facing governance modules: news, feedback intake, reporting, and oversight
- Post-migration data cleanup and reconciliation depth
- Controlled digital materials foundation and repository governance

## Active Blockers
- Legacy data quality remains uneven after migration from MARC-SQL
- Some deeper admin and reporting surfaces are defined canonically but not yet fully implemented in the app
- CRM mirroring boundaries must stay conservative so the library remains the operational owner

## Immediate Next Actions
- [ ] Implement the admin overview and admin shell as the next high-value internal surface
- [ ] Follow with managed news, feedback inbox, and reporting views tied to the admin shell
- [ ] Deepen catalog and book-detail governance around data quality, UDC completeness, and inventory integrity
- [ ] Continue architecture work for controlled digital access and scientific repository moderation

## Known Technical Debt
- Post-migration metadata correction and duplicate cleanup remain ongoing
- Controlled viewer protections are architected but still need deeper product implementation
- Analytics and export depth need richer operational dashboards
- Contract-aware management of licensed external resources is future-ready but not yet complete


## Links
- [[PROJECT_CONTEXT]]
- [[DECISIONS]]
- [[OPEN_QUESTIONS]]
- [[TASK_LOG]]
- [[GRAPH_INDEX]]
