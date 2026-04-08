# This project is the new primary KazUTB library platform

## Core identity
KazUTB Smart Library is the future main university library system, not just a demo website, not just a catalog UI, and not just a CRM shell.
It owns the reader-facing experience, teacher resource discovery, circulation logic, stewardship workflows, staff/admin operations, and library-side API semantics.

## What the system already includes
- public catalog and book pages
- role-aware account/login flow backed by server session state
- teacher shortlist / syllabus-draft support
- internal review and circulation surfaces for staff
- CRM-facing integration endpoints for reservation workflows
- external licensed resources and digital-material foundations

## What it is not
- not a CRM-owned product
- not a frontend-only shell
- not a disposable migration sandbox

## Business boundary
The library product remains the domain owner.
CRM is a bounded auth/integration/admin client.

## Related repo truth
- `routes/web.php`
- `routes/api.php`
- `config/services.php`
- `app/Services/Library/*`
- `app/Services/Ai/TwentyFirstBridgeService.php`
