# MVP Demo Preparation — KazUTB Smart Library

## Purpose

This document defines a presentation-ready walkthrough for the current MVP increment.
It is intended for university leadership, library administration, and implementation stakeholders who need a concise view of what is already working, what business processes are covered, and what remains intentionally out of scope for this stage.

---

## MVP Narrative

The current MVP demonstrates a coherent institutional library platform built around five visible service areas:

1. Public catalog discovery
2. Reader cabinet visibility
3. Librarian reservation processing
4. Circulation operations
5. Analytics and reporting

The product is positioned as a single multilingual interface for university and college library operations, while preserving branch-aware and scope-aware ownership boundaries.

---

## What Is Ready To Demonstrate

### 1. Institutional overview experience

- The application now opens to a dedicated overview page rather than dropping users directly into a raw module screen.
- The overview explains system scope, covered processes, supported roles, and next-phase topics.
- This page is suitable as the opening frame for a leadership demonstration.

### 2. Navigation clarity

- Main navigation is grouped into public access, reader services, library operations, and administration.
- This reduces ambiguity during live demonstration and makes role-based areas easier to explain.
- Secure/internal areas are clearly labeled as protected operational surfaces.

### 3. Public catalog flow

- Guests can browse the catalog, apply filters, and open a bibliographic record.
- The catalog and book details pages now use consistent visual headers and institutional wording.
- The presentation emphasizes discovery, metadata visibility, and branch-aware availability.

### 4. Reader self-service flow

- The reader cabinet shows reservation and loan history in a clearer and more presentation-ready layout.
- Reservation statuses and branch context are visible.
- This is sufficient to explain what a student or teacher sees after authentication.

### 5. Librarian service flow

- The reservation queue page presents the operational handling of reader requests in a clearer service-oriented format.
- The circulation page presents issue and return operations with a more coherent operational heading structure.
- These pages are suitable for demonstrating branch-level service responsibility.

### 6. Operational visibility

- Analytics and reports pages now share the same institutional page structure and terminology.
- This improves credibility when showing the system to non-technical leadership audiences.
- The current MVP supports operational summaries rather than final executive BI.

### 7. Multilingual consistency

- Core visible presentation surfaces are aligned across Kazakh, Russian, and English.
- The interface tone remains formal and institution-appropriate.

---

## Recommended Demo Sequence

Use the following sequence during the live presentation.

1. Open the overview page.
2. Explain the platform scope: catalog, reservations, circulation, analytics, reports.
3. Show the grouped navigation and explain public versus protected areas.
4. Enter the public catalog and open a representative book record.
5. Explain reservation intent from the reader perspective.
6. Open the reader cabinet and show reservation and loan visibility.
7. Open the librarian reservation queue and explain request servicing.
8. Open circulation and explain issue/return operations.
9. Open analytics and show current operational indicators.
10. Open reports and show monthly, yearly, and branch-level reporting.
11. Switch interface language to show multilingual readiness.

---

## Demo Showcase Dataset

The current seed is prepared for a believable institutional demonstration and includes:

- 3 branches across university and college scopes
- 8 books distributed across branches
- multiple authors and categories with realistic combinations
- reservations in mixed statuses: pending, ready, fulfilled, cancelled, expired
- loans in mixed states: active, overdue, returned, lost
- activity distribution sufficient for dashboard and report visibility

This dataset is intentionally moderate in size to stay realistic for a university pilot without creating noisy, synthetic overpopulation.

---

## Suggested Demo Accounts and Roles

Use the following seeded identities as narrative anchors during presentation:

- Administrator: admin
- Analyst: analyst1
- Librarian (Economic branch): librarian_econ
- Librarian (Technological branch): librarian_tech
- Librarian (College branch): librarian_college
- Student (University scope): student1
- Student (College scope): student2
- Teacher: teacher1

If secure login is presented as scaffolded in the current stage, these identities should still be referenced to explain branch ownership, role boundaries, and operational responsibilities.

---

## Role-Oriented Walkthrough Story

Use this framing to keep the demonstration understandable for leadership audiences:

1. Guest story
   Show public catalog discovery and explain open institutional visibility.

2. Reader story
   Show reservation and loan history surfaces as student/teacher self-service.

3. Librarian story
   Show reservation queue decisions and circulation handling as operational branch work.

4. Administrator and analyst story
   Show analytics and reports for institutional control, planning, and governance.

---

## Suggested Talking Points For Leadership

- The system is being built as a unified institutional library platform rather than a collection of separate screens.
- Public discovery and internal operations now share a coherent visual and navigation model.
- The current MVP already reflects real library workflows: discovery, reservation handling, circulation, and reporting.
- The product is prepared for multilingual institutional use across Kazakh, Russian, and English.
- Branch-aware and institution-aware operating boundaries remain part of the product model.

---

## Scope Intentionally Deferred Beyond This Demo

The following items are intentionally not presented as completed in this MVP demonstration:

- Full LDAP / Active Directory production authentication integration
- Legacy MARC SQL migration pipeline execution
- Protected digital file viewer / PDF subsystem
- Expanded user administration workflows
- Advanced export and executive reporting formats

These topics remain valid next-phase work and should be described as planned extensions, not current MVP commitments.

---

## Presenter Notes

- Avoid presenting the authentication scaffold as production-ready identity integration.
- Describe protected sections as role-based operational surfaces prepared for secured login.
- Use the role-based entry cards on the overview page to make the walkthrough path explicit for non-technical stakeholders.
- Reference seeded cross-branch operational records to show that analytics and reports represent meaningful activity, not placeholder zeros.
- Keep the emphasis on process coverage, institutional coherence, and readiness for phased expansion.

---

## Validation Status For This Polish Increment

- Frontend production build: passed
- Backend build: passed
- Backend automated tests: passed
