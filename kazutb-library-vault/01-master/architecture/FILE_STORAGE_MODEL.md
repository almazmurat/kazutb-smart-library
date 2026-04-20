# FILE_STORAGE_MODEL
> Derived from [[PROJECT_CONTEXT]] §27

## File categories
| Category | Description | Access |
|---|---|---|
| Book cover images | Cover photos or scans | Public |
| Protected digital book files | Full digital versions | Protected |
| Scientific work files | Theses, dissertations, papers | Protected |
| Report exports | PDF and Excel outputs | Staff only |
| Import files | CSV or MARC loads | Staff only |

## Storage rules
- public covers can use a public path
- protected files must stay in a private bucket or equivalent store
- file delivery must happen via a signed URL or streaming proxy, never a raw public link
- deletion is soft by default, with hard delete reserved for admins
- duplicate uploads should be versioned

## Naming convention
`{entity_type}/{entity_id}/{timestamp}_{filename}`

## Controlled viewer requirements
The viewer should avoid direct URL exposure, disable download affordances, and keep the architecture ready for watermarking and future hardening.

## Links
- [[PROJECT_CONTEXT]]
- [[DIGITAL_MATERIALS_POLICY]]
- [[SCIENTIFIC_REPOSITORY]]
