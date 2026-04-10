# Canonical platform truth for Digital Library

## Core identity
Digital Library is the new primary university digital library platform. It is replacing legacy library software in stages, not acting as a temporary demo site, a frontend-only shell, or a CRM-owned wrapper.

## Institutional naming convention
- In Kazakh/Cyrillic-facing UI copy, default to **`КазТБУ`**.
- Use **`КазУТБ`** only when explicitly referring to the Russian-language institutional name.
- Existing technical identifiers and English transliterations such as `kazutb-smart-library` or `KazUTB` stay unchanged unless a separate rename task requests it.

## What the platform is growing into
The platform is expected to serve all major university-library roles directly over time:
- readers and students
- teachers selecting literature for syllabi and educational programs
- librarians handling stewardship, circulation, and metadata correction
- admins and reporting/compliance operators

## Architectural non-negotiables
- Runtime stack: **Laravel 13 + Blade/React/Vite + PostgreSQL + Docker Compose**.
- Repo files are execution truth; Obsidian is the long-term memory graph.
- CRM is a bounded auth/integration client and must not connect directly to the library database.
- The library platform owns the reader experience, library-side domain logic, discovery semantics, operational workflows, and API behavior.

## Strategic product truths
- UDC classification is a first-class concept for search, filtering, navigation, grouping, and future recommendation flows.
- Digital materials, licensed external resources, and access-control rules are real product layers and must stay explicit in planning.
- Weak metadata quality is an operational reality; it must be surfaced and improved, not hidden.
- Future AI/NLP assistance for metadata cleanup, semantic discovery, and librarian support is a likely product direction.

## Product-planning posture
- Even when a request is phrased as “make a page” or “add a section”, treat the work as part of one coherent КазТБУ product surface rather than as an isolated screen.
- Within this repository, that means public pages, authenticated reader/teacher flows, and internal/staff operations should feel like one system and reuse shared patterns.
- New content or structural work should be planned so it can be governed, extended, and managed through staff/admin workflows over time, without losing the repo’s library-centered boundary.
