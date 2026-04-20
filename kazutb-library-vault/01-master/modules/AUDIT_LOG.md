# AUDIT_LOG
> Derived from [[PROJECT_CONTEXT]] §26

## What must be logged
Authentication events, reservations, circulation actions, metadata edits, copy edits, merges, digital uploads, repository moderation, news management, user management, settings changes, exports, and data cleanup operations.

## Log entry structure
Each entry must include actor, timestamp, action type, entity type, entity ID, previous and new values where relevant, and the originating IP or request context.

## Access by role
Admin gets full access, librarians see their own and library operations, members see their own activity history, and guests have no audit access.

## Links
- [[PROJECT_CONTEXT]]
- [[ANALYTICS_AND_REPORTING]]
- [[API_STRATEGY]]
