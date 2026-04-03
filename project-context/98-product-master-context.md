# Product Master Context – KazUTB Smart Library

This file is the high-level product and domain source of truth for the project.

Read this file together with:
- AGENT_START_HERE.md
- 00-project-truth.md
- 01-current-stage.md
- 02-active-roadmap.md
- 03-api-contracts.md
- 04-known-risks.md
- 05-agent-working-rules.md
- 06-current-focus.md

Purpose:
- preserve the real business and domain meaning of the project
- prevent scope drift
- preserve the distinction between the library core platform and CRM integration
- keep product, operational, data, and integration realities visible during implementation

---

## 1. What this project really is

The “KazUTB Smart Digital Library” project is the creation of a new full library system for the university.

It is not just:
- a new website
- a new catalog page
- a new interface on top of old data
- a decorative demo
- a temporary shell around a legacy platform

It is an attempt to build a new real digital library platform that will eventually replace the old library environment.

The future system is expected to unify:
- catalog
- search
- book detail pages
- fund and copy data
- user accounts and personal cabinet flows
- library workflows
- administrative capabilities
- librarian workspaces
- analytics and reporting
- digital materials
- data stewardship and data improvement workflows
- foundations for future AI-assisted functionality

The project should become a real operational digital base of the library.

---

## 2. Why this project exists

The old library system is not “useless”, but it is not a good foundation for the future.

The old environment still supports basic library work:
- records of the collection
- bibliographic records
- copy/fund records
- catalog exposure to users
- core library practice

The problem is not the absence of a system.
The problem is that the old system is too limited to serve as the base for:
- modern user experience
- modern digital library workflows
- clean extensible architecture
- transparent and governable data work
- flexible analytics and reporting
- future integrations with university systems
- a good internal administrative and librarian environment

So the new system is not just an interface refresh.
It is a transition to a new level of digital maturity for the library.

---

## 3. What the old reality looked like

Historically, the library worked through a legacy library system that supported:
- cataloging
- bibliographic records
- fund records
- copies
- internal library operations
- web exposure of the catalog

Data was indexed and published to users through a web catalog.
The environment was local to the university and under institutional control.

But that reality also had serious limitations:
- incomplete transparency of data structures
- unclear data quality
- duplicates and stale records
- heavy and outdated operational environment
- weak flexibility for future development

The new platform must respect the library reality, but must not be trapped by the old system as an eternal foundation.

---

## 4. Real essence of the new project

The goal is to build a new independent library system as a real digital product.

The system must:
- store library data in a modern structure
- support real library workflows
- be usable for students and teachers
- be usable for librarians
- be governable for administrators
- support analytics and reporting
- be suitable for demonstration
- be suitable for deployment and future real use
- become the digital foundation of the new KazUTB library

This is not just a showcase project.

---

## 5. What the system must include

### For ordinary users
The system should provide:
- catalog search
- filtering
- book pages
- metadata
- availability information
- reservation
- access to allowed digital materials
- personal cabinet
- modern understandable library UX

### For librarians
The system should provide:
- catalog management
- document/book record work
- copy management
- circulation (checkout / return)
- reservation processing
- data import
- data correction and stewardship
- reports
- real librarian workflows
- operational metrics and reporting relevant to librarians

### For administrators
The system should provide:
- user management
- role and permission management
- system configuration
- service/technical administration
- integration oversight
- logs and system state visibility

### About analytics
Analytics is a functional layer, not necessarily a separate permanent role.
Analytics is needed for:
- librarians
- responsible staff
- administration
for reporting, metrics, and decision-making.

---

## 6. What the final system should become

The result should be a unified digital library ecosystem.

It should include:
- user-facing interface
- catalog
- search
- book detail pages
- reader scenarios
- personal cabinet
- library workflows
- admin panel
- librarian panels
- analytics
- reporting
- role model
- digital materials handling
- tools for data stewardship and improvement

The system should be:
- understandable
- mature
- demonstrable
- usable in real operation
- extensible
- modern enough not to look like a temporary mockup

---

## 7. Why this project is hard

This is not one small project.
It is several projects at once:
- a product project
- a library project
- a digital transformation project
- a data cleanup and improvement project
- an operational environment redesign project
- a future-facing user experience project

The task is not just “to write code”.
The team must:
- understand the library’s real operational meaning
- preserve reporting and library semantics
- migrate and improve data
- build a new product
- ensure the result is usable, not decorative

---

## 8. Main users of the system

### Guest
Should have:
- search
- open catalog
- availability preview
- limited book details

Should not have:
- reservation
- personal cabinet
- access to protected materials
- library operations

### Student
Should have:
- login with university identity
- search
- filtering
- book pages
- availability
- reservation
- access to allowed materials
- personal cabinet
- user-specific statuses and interactions

### Teacher
Similar to student, with possible expanded privileges.

### Librarian
Key operational user.
Should have:
- catalog/document work
- copy work
- checkout/return
- reservation handling
- import
- correction/stewardship
- reports
- fund workflows
- operational metrics and dashboards needed for real work

### Administrator
Maximum system control.
Should have:
- user management
- role management
- settings
- technical/service operations
- logs
- integration oversight

---

## 9. Most important functional contours

### 9.1 Catalog
The catalog is the user-facing core.
It must support:
- search
- filters
- sorting
- book pages
- metadata
- relationships
- availability
- understandable result presentation

### 9.2 Book detail page
Should show:
- title
- author
- publisher
- year
- language
- category
- status
- availability of copies
- related materials
- related entities
- possible user actions

### 9.3 Search
Search is one of the most important functions of the whole system.
It should support:
- title search
- author search
- keyword search
- metadata search
- full-text search
- filters
- sorting
- good UX logic

Later desirable:
- suggestions
- autocomplete
- typo correction
- similar books
- smarter search layer

### 9.4 Documents and copies
The system must model real library reality, not just “book in general”.
It must support:
- editions/documents
- copies/items
- availability
- copy status
- fund movement
- acquisition relation
- belonging to a specific fund and physical location

### 9.5 Reservation
Should support:
- creating reservation
- availability control
- librarian approval
- reservation statuses
- limits
- expiration
- cabinet visibility

### 9.6 Checkout and return
Should support:
- checkout
- return
- movement history
- statuses
- librarian action tracking
- real operational library logic

QR-code support may be added later if it improves workflow.

### 9.7 Personal cabinet
Should show:
- reservations
- statuses
- history
- available materials
- notifications
- user’s relation to the library

### 9.8 Admin panel
The library must have its own admin panel.
Even if CRM has its own admin environment, the library system must preserve a library-specific administrative surface.

It should support:
- adding and editing books/documents
- managing library entities
- managing users in library context
- rights/permissions in library context
- controlling import and data correction
- controlling library operations
- working with logs, reports, and service tasks

### 9.9 Analytics and reporting
This is a practical and important contour.
The system should provide:
- popular books
- user activity
- collection usage statistics
- process dynamics
- monthly reports
- yearly reports
- acquisition reports
- invoice-related reporting
- data reconciliation
- possibly accounting reconciliation if needed

The new system must not break the current reporting reality.
It must make it more transparent and convenient.

---

## 10. Data work is a core foundation of the project

If data is bad, the whole system will be bad, even with a beautiful interface.

So this project includes not only UI and workflows, but deep work on library data:
- entities
- tables
- fields
- mandatory data
- duplicates
- incomplete records
- mistakes
- reporting-critical data
- operationally critical data
- archival/historical data

---

## 11. How migration and current data stage must be understood

Migration is not just a future theory anymore.

Current reality:
- data has already been moved into PostgreSQL
- the new system already has updated schemas, entities, tables, and attributes
- but this does not mean data work is finished
- substantial post-migration data quality work is still ahead

This includes:
- manual cleanup
- library-side verification
- metadata correction
- reconciliation with physical collection
- error correction
- AI-assisted correction
- gradual raising of record quality

Some issues cannot be solved fully automatically.
The project needs both:
- library staff who know the operational reality
- good internal tools/panels to improve data
- AI assistance where useful

---

## 12. What must not be lost in data work

Must not be lost:
- metadata correctness
- entity structure
- document-copy relationships
- reporting compatibility
- library meaning of records
- analytical usefulness
- reproducibility of operations
- understanding of what changed and why

Critical to preserve:
- quality control
- logging/auditability
- no silent loss
- library logic
- reporting logic

---

## 13. Digital materials

Digital materials are a sensitive contour.

Currently the library has relatively few digital versions of physical books.
There is scanning capability.
The system must support:
- digital materials
- electronic versions
- covers
- file artifacts
- controlled viewer

But the library must not turn into an unrestricted file repository.

For digital materials:
- no unrestricted download
- no direct links to protected files
- restrict copying/selection as much as realistically possible
- provide controlled viewer mode
- access must depend on role, authorization, and usage rules

Guests must not access protected materials.
Authorized users must only get the level of access allowed by library policy and rights holders.

---

## 14. Authentication truth

The library system remains the primary system for library logic:
- its own logic
- its own database
- its own panels
- its own library workflows
- its own administrative contour

CRM is needed mainly as:
- a shared university administrative environment for staff
- an external auth/API point
- a source of LDAP / AD authentication
- a system that may also build its own panels using library APIs

The library and CRM coexist.
But the library remains the main system in the library domain.

---

## 15. Login flow

Login happens inside the library UX, not through CRM UI.

Flow:
1. user opens library login page
2. user enters login/password
3. library sends request to CRM API
4. CRM performs LDAP / AD validation
5. if successful, CRM returns token and user data
6. library uses that token and user state in its own app
7. user continues inside the library system

There is:
- no redirect to CRM
- no CRM UI handoff during login
- library-owned login UX

---

## 16. Known CRM auth API

Known CRM host:
- `10.0.1.47`

Known base URLs:
- `http://10.0.1.47`
- `http://10.0.1.47/api`

Known endpoints:
- `POST /api/login`
- `POST /api/admin/login`
- `GET /api/me`
- `POST /api/logout`

For library login, primary endpoints are:
- `POST /api/login`
- `GET /api/me`
- `POST /api/logout`

Expected model:
- CRM handles AD / LDAP auth
- may have local fallback
- returns bearer token model

This means the library can:
- show its own login screen
- send credentials to CRM
- receive token
- use `Authorization: Bearer <token>`
- keep working as the library app

---

## 17. Expected CRM login response

Successful login is expected to return:
- token
- token_type = Bearer
- user

User may include:
- id
- name
- first_name
- last_name
- initials
- display_name
- description
- department
- department_number
- room
- email
- ad_login
- role

The library can use:
- user identity
- role
- display data
- department context if useful
- library-side session and role behavior around that

---

## 18. Integration security risk

Current integration is over HTTP, not HTTPS.

This means:
- credentials go over the network without TLS
- bearer token also goes over HTTP
- this may temporarily work in local/internal environment
- for serious operation, this is a risk

Future must include:
- HTTPS
- transport security hardening
- careful session/token handling

---

## 19. What the library must do on session side

Because CRM returns bearer token, the library system must correctly implement:
- token storage
- profile loading
- logout
- invalid token handling
- repeat login behavior
- behavior without refresh token if absent
- safe session state handling

---

## 20. CRM in university reality

CRM is a shared administrative environment for staff at the university.

But this does not mean the library disappears as a separate system.
Instead:
- the library remains its own system
- CRM is a neighboring university system
- the library integrates with CRM via auth/API
- the library also exposes APIs so CRM developers can build parallel panels/workflows on the CRM side

The systems coexist and integrate.

---

## 21. Real physical and organizational structure of the library

The library is not one completely homogeneous physical thing.

There are at least:
- economic library point / corpus
- technological library point / corpus

There is also college-related fund history:
- the college fund is historically linked to the university
- librarians are in the process of separating college books from university books

Known distinctions already matter:
- college
- general
- economic university
- technological university

The new system must explicitly support fund belonging, not treat everything as one flat pool.

---

## 22. What this means for the data model

The system must support and preserve concepts like:
- organization / unit
- fund type
- belonging of the copy
- physical location
- campus / branch / library point
- acquisition source
- reporting contour

It is incorrect to design the system as if all books are just one flat list.
The system must model:
- where the copy physically is
- who it belongs to
- whether it belongs to college or university
- whether it belongs to economic or technological context
- how this affects reporting and operations

---

## 23. External resources and contracts

The library does not work only with local physical fund and local digital materials.
It also operates with licensed external educational/library resources.

Example:
- IPR SMART access is known to be available to KazUTB students and teachers until **September 9, 2026**

The system must be able to represent:
- external licensed resources
- access periods
- usage conditions
- user categories allowed to access them
- distinction between internal fund and external licensed resources

External resources are not just decorative links.
They reflect real contractual and access realities.

---

## 24. Why contracts and licenses matter

The system must respect:
- license conditions
- access periods
- usage restrictions
- difference between own fund and external licensed content
- difference between local books, RMEB, IPR SMART, and similar platforms/resources

This means the external resources layer is a real operational and legal contour, not just a UI block.

---

## 25. CRM and future panel integration

CRM developers will receive library APIs to build their own panels and workflows.

This means:
- the library remains the primary system for library domain logic
- CRM can consume library APIs
- CRM can build analogous panels on its side
- but the library should still preserve the possibility of its own panels and workflows

So:
- the library should have its own UI and operational scenarios
- CRM may also have its own UI and operational scenarios
- both systems interact through API

The API layer of the library is therefore strategically important.

---

## 26. Main focus remains inside the library system

Despite CRM integration, the main product focus remains in the library itself.

The library system must continue to evolve:
- catalog
- search
- book pages
- covers
- digital versions
- controlled viewer
- personal cabinet
- library panels
- administrative contour
- AI functions
- recommendations
- AI assistant
- reader-facing UX
- data quality
- library APIs

CRM is important, but the heart of the library product stays in the library system.

---

## 27. Future AI layer

A future AI/assistant layer is an important direction.

It may support:
- smarter search
- recommendations
- contextual hints
- navigation through the library
- AI-assisted data correction
- user guidance
- improved interaction with the catalog

But this AI layer must live inside the library system and respect:
- its data
- its access rules
- its library logic

---

## 28. Current state of the codebase

The new codebase already exists and already provides:
- visual interface
- landing
- catalog
- book page
- reader page
- login page
- account page
- Laravel backend scaffold
- API layer
- basic auth wiring
- PostgreSQL as the working core data environment
- clear direction toward the new library

But it is still a transition stage:
- not a finished product
- not production-ready
- not an empty shell
- but a transition between a strong prototype and a real library system

---

## 29. What already exists vs what still needs serious work

Already exists:
- basic visual and architectural base
- pages
- user-facing layer
- part of the API layer
- auth shell
- new PostgreSQL environment
- direction for working with data
- understanding of key roles and contours

Still needs substantial work:
- real library domain logic
- real entities and relationships
- reservation maturity
- circulation maturity
- real user-book relations
- fuller role enforcement
- librarian panels
- admin panels
- metrics and logs
- production readiness
- protected digital materials flow
- configuration maturity
- integration security
- high-quality CRM-facing APIs

---

## 30. What must not be lost while moving fast

In the pursuit of speed, do not lose:
- library meaning
- operational library logic
- library admin contour
- librarian workspace
- data correctness
- reporting compatibility
- digital material restrictions
- real operational usefulness
- structured and extensible architecture

Do not degrade the project into “just a pretty frontend”.
UI is important, but this remains a full library system.

---

## 31. What the final result should be

The ideal end result is a product that:
- looks like a modern digital library
- actually works as a real library system
- supports catalog, search, book pages, cabinets, and real workflows
- has its own admin and librarian logic
- works with roles and access
- handles digital materials in controlled mode
- runs on cleaned and governable data
- respects real fund structure and belonging
- preserves reporting reality
- handles external resources and licenses
- is suitable for demonstration
- is suitable for deployment and future development

---

## 32. Short explanation of the project

The KazUTB Smart Digital Library project is the creation of a new main library system for the university.

It is intended to replace the old library environment and provide:
- modern catalog
- search
- book pages
- personal cabinets
- library workflows
- administrative contour
- librarian panels
- analytics and reporting

The system is built on a new PostgreSQL data environment, requires substantial continued data quality work, must respect digital material and copyright constraints, and uses CRM API integration for LDAP / Active Directory-backed authentication.

CRM does not replace the library system.
Library logic, library workflows, and administrative contour must remain inside the library system.

The system must also account for:
- real physical and organizational library structure
- university vs college fund distinctions
- external licensed resources
- future AI-assisted capabilities

---

## 33. Current project truths

At the current stage, the project must be understood like this:
- this is the new main library system, not just an interface
- the old system is historical context, not the future foundation
- data is already in PostgreSQL, but still needs substantial refinement
- manual cleanup and AI-assisted correction are real strategy
- library logic stays inside our system
- CRM does not take over the library system
- CRM is primarily used for LDAP / AD auth through API
- login UX remains inside the library
- the library must have its own admin and librarian capabilities
- the library must have a strong API layer for CRM integration
- the library must respect the real structure of the fund:
  - university
  - college
  - economic
  - technological
- the library must account for external electronic/library resources and license contracts
- the library must support covers, digital versions, and controlled viewer
- the library should gain an AI layer for search, recommendations, and data work
- the system must be a real future library platform, not a fake demo
- production-ready maturity is still ahead
- the project is in a transition phase from strong prototype to real library system

---

## Additional notes – system and CRM

### System notes
The system is expected to operate with university corporate identity / LDAP / AD reality.
Users are expected to authenticate with university-backed credentials.
Authentication is performed through CRM API integration.

### CRM notes
CRM should be able to use library APIs to support admin/library-like panels on its side.
But CRM should not connect directly to library database.
The library remains the source of library domain logic and data.

Known CRM-side endpoints shown for awareness Just for example, not an exhaustive list:
- `GET http://10.0.1.47/api/admin/library/reservations`
- `GET http://10.0.1.47/api/admin/library/reservations/{id}`
- `POST http://10.0.1.47/api/admin/library/reservations/{id}/approve`
- `POST http://10.0.1.47/api/admin/library/reservations/{id}/reject`

These are contextual references only.
They do not change the core project truth:
library APIs are strategic, CRM is an integrating client, and the library remains the core system in its own domain.