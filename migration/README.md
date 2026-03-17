# Migration Pipeline — KazUTB Smart Library

## Directory Structure

```
migration/
├── raw/            ← Raw MARC SQL export files (archived, never modified)
├── clean/          ← Cleaned and deduplicated data
├── normalized/     ← Normalized data mapped to target schema
├── scripts/
│   ├── export/     ← Instructions/scripts for MARC SQL export
│   ├── clean/      ← Data cleaning pipeline scripts
│   ├── transform/  ← Data transformation/normalization scripts
│   └── import/     ← Database import scripts
├── logs/           ← Migration run logs
└── checksums/      ← SHA-256 integrity files
```

## Getting Started

1. Read [docs/migration/concept.md](../docs/migration/concept.md) for the full pipeline design.
2. Export data from MARC SQL — see `scripts/export/README.md`.
3. Run cleaning pipeline — see `scripts/clean/README.md`.
4. Run transformation — see `scripts/transform/README.md`.
5. Import to PostgreSQL — see `scripts/import/README.md`.

## Important Rules

- **Never modify files in `raw/`** — these are archive snapshots.
- Every migration run should be **idempotent** — safe to re-run.
- All steps must produce **log files** in `logs/`.
- Compute and store **SHA-256 checksums** in `checksums/` before and after each stage.
- The `raw/`, `clean/`, `normalized/`, `logs/`, and `checksums/` directories are in `.gitignore` — actual data files are not committed to the repository.
