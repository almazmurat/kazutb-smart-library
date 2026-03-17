# Catalog Core Domain Decisions (Phase: Authors, Categories, Books, Copies)

## Scope of this phase

Implemented operational core for catalog management:

- Authors
- Categories
- Books
- Book copies (inventory units)

Excluded in this phase:

- Search implementation
- Reservation workflows
- Circulation workflows
- Advanced analytics

## Ownership model

Catalog ownership is mutation-critical for books and copies.

- `Book.libraryBranchId` is required.
- `BookCopy.libraryBranchId` is required.
- Institution scope is derived through `LibraryBranch.scopeId`.

Mutation policy:

- `ADMIN` can mutate catalog records across all branches/scopes.
- `LIBRARIAN` can mutate only within assigned branch and scope.
- Cross-branch or cross-scope mutation is denied.

Policy is centralized in `CatalogOwnershipPolicy` and reused in `BooksService`.

## Authors and categories scope decision

Authors and categories are treated as global shared dictionaries in this phase.

Rationale:

- Avoid duplicate authority records across branches.
- Keep normalized references for books and future search indexing.
- Branch ownership remains enforced on books/copies where operational responsibility exists.

Both entities support soft deactivation via `isActive`.

## Copy status model

`CopyStatus` includes:

- `AVAILABLE`
- `RESERVED`
- `LOANED`
- `LOST`
- `ARCHIVED`
- `DAMAGED`
- `WRITTEN_OFF`

The first five statuses align with MVP requirements; additional statuses preserve future operational detail.

## API readiness for future phases

Current API now supports branch-scoped CRUD and inventory updates.
This enables next-phase implementation of:

- Search indexes and query UX
- Reservation lifecycle
- Circulation transactions (loan/return)
- Branch-scoped reporting
