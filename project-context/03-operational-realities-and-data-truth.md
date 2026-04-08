# Operational realities and data truth

## Migration and data-cleanup reality
- the university is moving from legacy library software into the new Laravel/PostgreSQL platform in stages
- metadata, bibliographic records, UDC mappings, inventory data, and reader data are still being refined post-migration
- weak or inconsistent metadata quality is a real operational issue and must remain visible in planning, UX, and admin tooling

## Data rules that stay first-class
- UDC must remain a first-class architectural concept for search, filtering, navigation, and future semantic discovery
- inventory number uniqueness is global across the shared environment
- barcode uniqueness is global across the shared environment
- operator attribution for who changed data matters for stewardship and audit trails
- KSU / invoice-based acquisition and reporting compatibility remains a real compliance need

## Digital access truth
- digital materials require controlled access behavior, not careless open-file assumptions
- external licensed resources must be modeled explicitly and separately from the local library fund
- access rules may vary by material type, license, role, and authentication state

## Future product intelligence direction
AI/NLP support for metadata cleanup, semantic discovery, recommendation, and librarian assistance is a legitimate future platform layer and should not be treated as out-of-scope noise.
