# DATA_MODEL
> Derived from [[PROJECT_CONTEXT]] §6

## Current state
Data already lives in PostgreSQL, but quality is uneven because of inherited migration issues from MARC-SQL. Cleanup is a continuing operational requirement.

## Critical preservation rules
- preserve bibliographic meaning
- preserve entity relationships between record, edition, and copy
- preserve reporting compatibility and auditability
- avoid data loss during cleanup

## Bibliographic record fields
| Field | Required | Notes |
|---|---|---|
| Control number | ✅ | Unique identifier |
| Correction date | ✅ | Last modified timestamp |
| Country code | ✅ | Standard metadata |
| Language | ✅ | ru, kk, en, and others |
| UDC index | ✅ | Primary discovery axis |
| Catalog index | ✅ | Cataloging support |
| Author sign | ✅ | Display on cover UI |
| Main author | ✅ | Core author field |
| Title | ✅ | Main title |
| Physical medium | ✅ | Format description |
| Place / Publisher / Year | ✅ | Publication block |
| Annotation / Keywords | Recommended | Search and discovery |

## Copy / inventory fields
| Field | Required |
|---|---|
| Inventory number | ✅ |
| Barcode | ✅ |
| KSU record number | ✅ |
| Sigla / storage code | ✅ |
| Fund ownership | ✅ |
| Branch / library point | ✅ |
| Precise shelf location | ✅ |
| Acquisition metadata | ✅ |

## Entity hierarchy
```
Bibliographic Record
  └── Document / Edition
        └── Copy / Item
              ├── Inventory Number
              ├── Barcode
              ├── Branch / Shelf Location
              └── Availability Status
```

## Links
- [[PROJECT_CONTEXT]]
- [[STATUS_DICTIONARY]]
- [[LOCATION_AND_FUND_MODEL]]
