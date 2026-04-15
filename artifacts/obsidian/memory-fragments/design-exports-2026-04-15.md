---
type: context-update
project: [[Digital Library]]
date: 2026-04-15
timestamp: 2026-04-15T07:45:00Z
tags:
  - design-exports
  - canonical-screens
  - implementation-mapping
  - stitch-project
links:
  - "[[02-architecture]]"
  - "[[03-domain]]"
  - "[[12-reference]]"
---

# Design Exports — Screen Mapping Reference (Apr 15)

## Overview
New canonical design documentation added: `docs/design-exports/` contains the **Stitch project export** with approved screen definitions and implementation mapping for all platform surfaces.

## Files
- `canonical-design-map.md` (81 lines) — Master mapping of all page types to canonical sources
- `export-checklist.md` (37 lines) — Implementation verification checklist
- `athenaeum_digital/DESIGN.md` (91 lines) — Detailed design specifications
- Subdirectories: admin_overview/, book_details/, catalog/, homepage/, resources/

## Canonical Screen Source Truth

### Two-Project Model
- **Project A**: Approved reference screens (existing public, member, internal pages)
- **Project B**: Clean missing screens project (newly generated pages)

### Page Type Mappings
| Surface | Route | Source | Status |
|---------|-------|--------|--------|
| Homepage | `/` | Project B | design-ready |
| About | `/about`, `/contacts` | Project A | implemented |
| Login | `/login` | Project A | implemented |
| Discover | `/discover` | Project A | pending |
| Catalog | `/catalog` | Project B | pending |
| Book Details | `/book/:id` | Project B | pending |
| Resources | `/resources` | Project B | pending |
| Shortlist | `/shortlist` | Project A | partial |
| Member Dashboard | `/app/cabinet` | — | pending |
| Librarian Panel | `/app/staff` | — | pending |
| Admin Panel | `/app/admin` | — | pending |

## Integration Notes
- Source project ID: `4601252383613536784` (matched to Stitch project)
- Maps to `docs/sdlc/current/stitch-mapping.md` (when performing frontend redesign)
- Use as verification baseline before committing frontend changes
- Preserve UDC-first discovery logic in `/discover`
- Keep auth behavior unchanged in `/login`

## Next Steps
- [ ] Cross-check with Stitch project exports for consistency
- [ ] Validate each page against canonical design map before implementation
- [ ] Use export-checklist.md as gate for design verification
