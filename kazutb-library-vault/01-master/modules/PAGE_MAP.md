# PAGE_MAP
> Derived from [[PROJECT_CONTEXT]] §30

## Public routes
- `/` → Homepage
- `/catalog` → Catalog and search
- `/catalog/{id}` → Book detail
- `/resources` → External resources
- `/news` → News listing
- `/news/{id}` → Single news post
- `/login` → Library login page
- `/repository` → Scientific repository metadata listing
- `/repository/{id}` → Scientific work metadata page

## Member routes
- `/dashboard` → Member dashboard
- `/dashboard/reservations` → My reservations
- `/dashboard/history` → Borrowing history
- `/dashboard/list` → Personal literature shortlist
- `/dashboard/notifications` → My notifications
- `/dashboard/contact` → Contact form
- `/dashboard/messages` → My submitted messages
- `/repository/{id}/read` → Controlled scientific-work reader
- `/catalog/{id}/read` → Controlled digital-material reader

## Librarian routes
- `/librarian` → Librarian dashboard
- `/librarian/catalog` → Bibliographic records
- `/librarian/catalog/create` → Add record
- `/librarian/catalog/{id}/edit` → Edit record
- `/librarian/copies` → Copy management
- `/librarian/copies/{id}` → Copy detail
- `/librarian/issue` → Issue panel
- `/librarian/return` → Return panel
- `/librarian/reservations` → Reservation queue
- `/librarian/import` → Data import
- `/librarian/data-cleanup` → Cleanup panel
- `/librarian/news` → News management
- `/librarian/news/create` → Create post
- `/librarian/news/{id}/edit` → Edit post
- `/librarian/repository` → Repository moderation
- `/librarian/repository/{id}/review` → Review submission
- `/librarian/reports` → Reports dashboard
- `/librarian/reports/{type}` → Specific report
- `/librarian/messages` → Contact inbox
- `/librarian/external-resources` → Read-only resource view

## Admin routes
- `/admin` → Admin dashboard
- `/admin/users` → User management
- `/admin/roles` → Role management
- `/admin/settings` → System settings
- `/admin/logs` → Full audit log viewer
- `/admin/integrations` → CRM integration monitoring
- `/admin/external-resources` → Resource cards management
- `/admin/external-resources/create` → Add resource card
- `/admin/external-resources/{id}/edit` → Edit resource card
- `/admin/news` → Full news management
- `/admin/reports` → System-wide analytics
- `/admin/reports/{type}` → Specific report view
- `/admin/messages` → All contact messages
- `/admin/repository` → Full repository control
- `/admin/data-cleanup` → Full cleanup access
- `/admin/branches` → Branch and fund management

## Links
- [[PROJECT_CONTEXT]]
- [[RBAC_MATRIX]]
- [[START_HERE]]
