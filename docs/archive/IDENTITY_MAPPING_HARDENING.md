# Identity Mapping Hardening Roadmap

## Overview

This document describes the staged approach to hardening the CRM user → Library reader identity mapping, moving from heuristic-based matching to explicit, auditable, and maintainable identity bindings.

## Current State (Before Phase 1)

**Architecture**: Session-based auth from CRM (10.0.1.47) → Laravel session → `/api/v1/account/summary`

**Matching Logic**: Email-based (primary) + ad_login (fallback) using `app.reader_contacts` table

**Risks**:

- No explicit mapping table → implicit and invisible linkages
- No audit trail → impossible to debug mismatches
- Email duplication possible (1 existing; more possible with data entry errors)
- No stale detection → email changes in CRM not detected
- No ambiguity detection → multiple readers per email silently resolved by `ORDER BY registration_at DESC`

**Data Facts**:

- Total readers: 2319
- Email contacts: 2319/2319 (100% coverage)
- AD_LOGIN contacts: 0/2319 (zero in DB; not used for matching)
- Email duplicates: 1 known (handled by sorting)
- core_reader_id field: Populated (2319/2319) but not linked to CRM

---

## Phase 1: Validation & Logging Layer ✅ DONE

**Goal**: Add visibility and audit trail without changing behavior

**What was added**:

### 1. `IdentityMatchAudit` Service

- Location: `app/Services/Library/IdentityMatchAudit.php`
- Validates every matching decision
- Detects ambiguity (multiple readers per email)
- Detects stale matches (email changed in CRM vs DB)
- Logs to application logs (Laravel's default)
- Optional: logs to `identity_match_logs` table if exists

### 2. `AccountSummaryReadService` Updated

- Injects `IdentityMatchAudit` via DI
- Calls audit service on every match
- Exposes matching metadata in response:
    ```json
    "matching": {
      "status": "matched|no_match",
      "matched_by": "email|ad_login_fallback|no_match",
      "has_ambiguity": false,
      "ambiguity_details": "...",
      "is_stale": false,
      "stale_reason": "..."
    }
    ```

### 3. Optional: `identity_match_logs` Table

- Location: `database/migrations/2026_03_31_080810_create_identity_match_logs_table.php`
- Schema: Captures every match decision with metadata
- Indexes: `matched_by`, `created_at` for performance
- Status: Optional; can be run now or deferred to Phase 2
- Automatic fallback if table doesn't exist

### 4. Tests

- Location: `tests/Feature/Api/IdentityMatchAuditTest.php`
- Unit tests for audit logic
- No match detection
- Stale detection (email change)
- Candidate validation

**Benefits**:
✅ Visibility: See which reader was matched and why
✅ Early warnings: Detect ambiguity, stale matches, failures
✅ Audit trail: Know what happened and when (in logs or DB)
✅ Backward compatible: No behavior change, pure logging layer
✅ Server-ready: Works on 10.0.1.8 without modifications

**How to verify**:

```bash
# 1. Check logs for matching decisions
tail -f storage/logs/laravel.log | grep "Identity mapping"

# 2. Run tests
php artisan test tests/Feature/Api/IdentityMatchAuditTest.php

# 3. Manual test: Login and check response
curl http://localhost:8000/api/v1/account/summary \
  -H "Accept: application/json" | jq .matching
```

**Git Commits**:

- `fb6fa54`: Add identity matching audit & validation layer (main service)
- `c27461c`: Add identity match logging table & model (Phase 1.5, optional)

---

## Phase 2: Explicit Mapping Table ⏸️ PLANNED

**Timeline**: After 2-3 weeks of Phase 1 data collection

**Goal**: Replace heuristic matching with explicit, immutable CRM↔Reader bindings

**What to do**:

1. **Create `app.user_crm_identity` table**

    ```sql
    CREATE TABLE app.user_crm_identity (
      id UUID PRIMARY KEY,
      crm_user_id TEXT UNIQUE NOT NULL,
      reader_id UUID NOT NULL REFERENCES app.readers(id),
      email_at_link TEXT,
      linked_at TIMESTAMP,
      notes TEXT,
      is_stale BOOLEAN DEFAULT false,
      created_at TIMESTAMP,
      updated_at TIMESTAMP
    );
    ```

2. **Backfill existing matches**
    - Use Phase 1 logs to audit current mappings
    - Create seed command: `php artisan seed:identity-mappings`
    - Validate no collisions detected before backfill

3. **Update `AccountSummaryReadService`**
    - Check explicit table first
    - Fall back to heuristic (email match) only for debugging/transition
    - Detect and warn if heuristic match differs from explicit

4. **Create `IdentitySyncService`**
    - Maintains mapping consistency over time
    - Detects email changes in CRM
    - Flags stale mappings
    - Provides admin interface to relink

5. **Add audit commands**
    ```bash
    php artisan identity:audit          # Find mismatches
    php artisan identity:resolve        # Prompt for resolution
    php artisan identity:relink-reader  # Manual relink by admin
    ```

**Data required from Phase 1**:

- Distribution of match types (email vs ad_login)
- Frequency of ambiguity detections
- Patterns in stale matches
- Any unmapped users or unlinked readers

**Risk mitigation**:

- No breaking changes; heuristic stays as fallback
- Gradual enablement: explicit lookup first, fallback on miss
- Admin override: always possible to relink manually
- Audit trail: all changes logged

---

## Phase 3: Production Stabilization 🔮 FUTURE

**Timeline**: After Phase 2 is operational in production (4-6 weeks)

**Goal**: Solidify system, harden against edge cases, prepare for write flows

**What to do**:

1. **Monitor & alert**
    - Alert on ambiguity detections
    - Alert on stale matches
    - Daily summary: unmapped users vs unlinked readers

2. **Hardening**
    - Add RBAC to prevent role injection from CRM
    - TLS/mTLS between 10.0.1.8 (library) and 10.0.1.47 (CRM)
    - Implement token refresh/expiration renewal
    - Add comprehensive audit logging table

3. **Write flow preparation**
    - Design explicit mapping for loans, returns, reservations
    - Implement optimistic locking (prevent race conditions)
    - Add transaction consistency
    - Plan rollback procedures

4. **Legacy cleanup**
    - Deprecate heuristic matching after explicit table is stable
    - Archive old identity_match_logs (after 90 days)
    - Remove fallback code paths

---

## Implementation Status

| Phase | Component                        | Status     | Commit  | Notes                           |
| ----- | -------------------------------- | ---------- | ------- | ------------------------------- |
| 1     | IdentityMatchAudit service       | ✅ Done    | fb6fa54 | Validation + logging            |
| 1     | AccountSummaryReadService update | ✅ Done    | fb6fa54 | Audit integration               |
| 1.5   | identity_match_logs table        | ✅ Done    | c27461c | Optional; auto-skips if missing |
| 1.5   | IdentityMatchLog model           | ✅ Done    | c27461c | DB access layer                 |
| 1.5   | IdentityMatchAuditTest           | ✅ Done    | fb6fa54 | Unit tests                      |
| 2     | Explicit mapping table           | ⏸️ Blocked | —       | Awaiting Phase 1 data           |
| 2     | Backfill seeder                  | ⏸️ Blocked | —       | Awaiting Phase 1 validation     |
| 2     | IdentitySyncService              | ⏸️ Blocked | —       | Design pending                  |
| 3     | Monitoring & alerts              | 🔮 Future  | —       | Post-Phase 2                    |
| 3     | TLS hardening                    | 🔮 Future  | —       | Post-production validation      |

---

## Deployment Notes

### Pre-Deployment (Now)

- ✅ Phase 1 code is safe to deploy immediately
- ✅ No schema changes required
- ✅ Works alongside existing heuristic matching
- ✅ Backward compatible with existing `/api/v1/account/summary` response

### Optional: Enable logging table

```bash
php artisan migrate --step  # Run only latest migration
```

### Post-Deployment Monitoring

```bash
# Check for ambiguity detections
tail -f storage/logs/laravel.log | grep "has_ambiguity.*true"

# Check for stale matches
tail -f storage/logs/laravel.log | grep "is_stale.*true"

# Query logs (if migration was run)
php artisan tinker
>>> IdentityMatchLog::where('has_ambiguity', true)->count()
>>> IdentityMatchLog::where('is_stale', true)->count()
```

### Rollback (if needed)

Phase 1 is additive only:

- ✅ Safe to revert commits fb6fa54, c27461c
- ✅ No data loss
- ✅ Heuristic matching still works as before
- ✅ Tests can be disabled without impact

```bash
git revert fb6fa54 c27461c
php artisan migrate:rollback --step=1  # Only if migration was run
```

---

## Next Steps

1. **THIS WEEK**: Deploy Phase 1 to 10.0.1.8
2. **MONITOR**: Collect Phase 1 audit data for 1-2 weeks
3. **ANALYZE**: Review logs for patterns, ambiguities, stale matches
4. **PLAN**: Design Phase 2 explicit mapping based on real data
5. **VALIDATE**: Smoke test Phase 1 on production before Phase 2

---

## Contacts & Questions

- **Security**: Phase 1 has no security impacts; Phase 2/3 require TLS hardening review
- **Performance**: Phase 1 adds minimal overhead (~1-2ms per request for audit checks)
- **Data retention**: identity_match_logs grows ~200-300 rows/day; recommend archival policy
