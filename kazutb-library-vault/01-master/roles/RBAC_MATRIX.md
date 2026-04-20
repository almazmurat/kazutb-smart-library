# RBAC_MATRIX
> Derived from [[PROJECT_CONTEXT]] §5

This is the full permission reference for the live product. Use it alongside [[ROLES_AND_ACCESS]] and [[PAGE_MAP]].

## 1. Core Catalog & Search Actions
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| Search catalog | ✅ | ✅ | ✅ | ✅ |
| View full book metadata | ✅ | ✅ | ✅ | ✅ |
| View UDC / author sign | ✅ | ✅ | ✅ | ✅ |
| View availability status | ✅ | ✅ | ✅ | ✅ |
| View branch/library point | ✅ | ✅ | ✅ | ✅ |
| View sigla / shelf location | ✅ | ✅ | ✅ | ✅ |
| View inventory number | ✅ | ✅ | ✅ | ✅ |
| View barcode (display only) | ✅ | ✅ | ✅ | ✅ |
| View digital cover/preview | ✅ | ✅ | ✅ | ✅ |

## 2. Reservation & Circulation Actions
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| Create reservation | ❌ | ✅ | ✅ | ✅ |
| Cancel own reservation | ❌ | ✅ | ✅ | ✅ |
| Cancel any reservation | ❌ | ❌ | ✅ | ✅ |
| Confirm/process reservation | ❌ | ❌ | ✅ | ✅ |
| Issue copy to user | ❌ | ❌ | ✅ | ✅ |
| Return copy | ❌ | ❌ | ✅ | ✅ |
| View own borrowing history | ❌ | ✅ | ✅ | ✅ |
| View any user's history | ❌ | ❌ | ✅ | ✅ |
| Renew loan | ❌ | ✅ (if policy allows) | ✅ | ✅ |
| Override circulation limits | ❌ | ❌ | ✅ | ✅ |

## 3. Personal Shortlist Actions
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| Add item to shortlist | ❌ | ✅ | ❌ | ❌ |
| Remove item from shortlist | ❌ | ✅ | ❌ | ❌ |
| View own shortlist | ❌ | ✅ | ❌ | ❌ |
| View any user's shortlist | ❌ | ❌ | ❌ | ✅ |

## 4. Digital Materials Actions
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| View cover image | ✅ | ✅ | ✅ | ✅ |
| View limited preview | ✅ | ✅ | ✅ | ✅ |
| Read full digital material | ❌ | ✅ (if access flag set) | ✅ | ✅ |
| Download digital file | ❌ | ❌ | ❌ | ❌ |
| Upload cover image | ❌ | ❌ | ✅ | ✅ |
| Upload digital file | ❌ | ❌ | ✅ | ✅ |
| Set access flags | ❌ | ❌ | ✅ | ✅ |
| Delete digital file | ❌ | ❌ | ❌ | ✅ |

## 5. Catalog & Metadata Management
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| Add new bibliographic record | ❌ | ❌ | ✅ | ✅ |
| Edit bibliographic record | ❌ | ❌ | ✅ | ✅ |
| Delete bibliographic record | ❌ | ❌ | ❌ | ✅ |
| Add/edit copies/items | ❌ | ❌ | ✅ | ✅ |
| Delete copies/items | ❌ | ❌ | ❌ | ✅ |
| Merge duplicate records | ❌ | ❌ | ✅ | ✅ |
| Import data (MARC, CSV) | ❌ | ❌ | ✅ | ✅ |
| Data cleanup panel access | ❌ | ❌ | ✅ | ✅ |
| Edit UDC classification | ❌ | ❌ | ✅ | ✅ |

## 6. News & Announcements
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| Read news | ✅ | ✅ | ✅ | ✅ |
| Create news post | ❌ | ❌ | ✅ | ✅ |
| Edit own news post | ❌ | ❌ | ✅ | ✅ |
| Edit any news post | ❌ | ❌ | ❌ | ✅ |
| Delete news post | ❌ | ❌ | ❌ | ✅ |
| Publish/unpublish news | ❌ | ❌ | ✅ | ✅ |

## 7. Scientific Repository Actions
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| Browse repository metadata | ✅ | ✅ | ✅ | ✅ |
| Read full scientific work | ❌ | ✅ | ✅ | ✅ |
| Upload scientific work | ❌ | ❌ | ✅ | ✅ |
| Submit work for approval | ❌ | ❌ | ✅ | ✅ |
| Approve/reject submission | ❌ | ❌ | ✅ (moderate) | ✅ (final) |
| Publish approved work | ❌ | ❌ | ❌ | ✅ |
| Remove published work | ❌ | ❌ | ❌ | ✅ |

## 8. External Resources
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| View resource cards/descriptions | ✅ | ✅ | ✅ | ✅ |
| Use external resource link | ✅ (public) / conditional (licensed) | ✅ | ✅ | ✅ |
| Add/edit resource card | ❌ | ❌ | ❌ | ✅ |
| Delete resource card | ❌ | ❌ | ❌ | ✅ |

## 9. User & System Management
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| Manage users | ❌ | ❌ | ❌ | ✅ |
| Manage roles | ❌ | ❌ | ❌ | ✅ |
| View system logs | ❌ | ❌ | ❌ | ✅ |
| View integration status | ❌ | ❌ | ❌ | ✅ |
| System settings | ❌ | ❌ | ❌ | ✅ |

## 10. Reports & Analytics
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| View analytics dashboard | ❌ | ❌ | ✅ (library ops) | ✅ (full) |
| Export reports | ❌ | ❌ | ✅ | ✅ |
| View acquisition reports | ❌ | ❌ | ✅ | ✅ |
| View user activity reports | ❌ | ❌ | ✅ (aggregated) | ✅ (full detail) |

## 11. Contact / Messages
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| Submit contact message | ❌ | ✅ | ✅ | ✅ |
| View all received messages | ❌ | ❌ | ✅ | ✅ |
| Mark message as resolved | ❌ | ❌ | ✅ | ✅ |
| Delete message | ❌ | ❌ | ❌ | ✅ |

## 12. Fund / Branch / Location Management
| Action | Guest | Member | Librarian | Admin |
|---|---|---|---|---|
| View branch info | ✅ | ✅ | ✅ | ✅ |
| Manage branch/fund structure | ❌ | ❌ | ❌ | ✅ |
| Assign copy to fund/branch | ❌ | ❌ | ✅ | ✅ |
| Manage sigla/storage codes | ❌ | ❌ | ✅ | ✅ |

## Links
- [[PROJECT_CONTEXT]]
- [[ROLES_AND_ACCESS]]
- [[PAGE_MAP]]
