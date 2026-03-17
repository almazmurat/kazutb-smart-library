# Multilingual and Segmentation Baseline

## Multilingual Product Requirement

The platform must support three interface languages:

- Kazakh (`kk`)
- Russian (`ru`)
- English (`en`)

This requirement applies to both public and authenticated UI areas.

## UX and Content Style Requirement

All UI text and content patterns must remain:

- formal
- academic
- suitable for university and library administration context

Avoid casual phrasing in labels, policies, reports, and user-facing guidance.

## Library Segmentation Requirement

The real-world library structure includes:

- Economic Library
- Technological Library
- College Library

To support safe responsibility boundaries, domain design is hierarchical:

1. Institution scope (tenant-like grouping)
   - `UNIVERSITY`
   - `COLLEGE`
2. Branch ownership unit
   - `ECONOMIC_LIBRARY`
   - `TECHNOLOGICAL_LIBRARY`
   - `COLLEGE_LIBRARY`

## Access Boundary Rule

Operational baseline:

- College librarians cannot manage university branch records.
- University librarians cannot manage college branch records.
- Branch ownership checks are a mandatory layer on top of RBAC.

## Data Ownership Targets

The following entities must support ownership assignment by branch in the domain:

- book records
- book copies
- acquisition records
- librarian assignment context

## Implementation Approach in Current Phase

Current phase prepares architecture and data model for multilingual and ownership segmentation.

This phase does not require full translation rollout nor complete cross-module isolation enforcement yet.

Next phases enforce the boundary rules in endpoint-level business logic across catalog, circulation, reservations, and migration modules.
