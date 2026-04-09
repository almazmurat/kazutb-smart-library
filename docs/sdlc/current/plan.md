# Stitch design extraction and redesign planning pass

## Goal
Produce a **planning-only** redesign map using the live Stitch project as the primary visual source for the real Digital Library routes and screens.

> Constraint: **do not implement additional UI changes in this phase**. This document is the grounded mapping artifact for the next execution wave.

---

## 1. Full page inventory

### Public reader surfaces
| Route | Purpose | Primary file | Mapping status |
|---|---|---|---|
| `/` | Homepage / library entry | `resources/views/welcome.blade.php` | **Direct Stitch match** |
| `/catalog` | Blade catalog shell | `resources/views/catalog.blade.php` | **Direct Stitch match** |
| `/book/{isbn}` | Book detail | `resources/views/book.blade.php` | **Direct Stitch match** |
| `/digital-viewer/{materialId}` | Controlled digital access viewer | `resources/views/digital-viewer.blade.php` | **Extension design** |
| `/contacts` | Library identity / contact information | `resources/views/contacts.blade.php` | **Extension design** |
| `/resources` | Academic resources portal | `resources/views/resources.blade.php` | **Direct Stitch match** |
| `/discover` | Discovery-first academic navigation | `resources/views/discover.blade.php` | **Extension design** |
| `/shortlist` | Teacher shortlist / literature prep | `resources/views/shortlist.blade.php` | **Direct Stitch + extension** |
| `/login` | Secure access | `resources/views/auth.blade.php` | **Direct Stitch match** |
| `/account` | Member dashboard | `resources/views/account.blade.php` | **Direct Stitch match** |
| `/book/{isbn}/read` | Transitional reader route | `resources/views/reader.blade.php` | **Extension design** |

### SPA surfaces
| Route | Purpose | Primary file | Mapping status |
|---|---|---|---|
| `/app/*` | React shell | `resources/views/spa.blade.php` | **Shared shell alignment** |
| `/app/catalog` | Main SPA catalog workflow | `resources/js/spa/pages/CatalogPage.jsx` | **Direct Stitch match** |
| SPA fallback | Not found / missing route | `resources/js/spa/pages/NotFoundPage.jsx` | **Extension design** |

### Internal staff surfaces
| Route | Purpose | Primary file | Mapping status |
|---|---|---|---|
| `/internal/dashboard` | Staff overview | `resources/views/internal-dashboard.blade.php` | **Derived from Librarian Operations** |
| `/internal/review` | Review / moderation | `resources/views/internal-review.blade.php` | **Derived from Librarian Operations** |
| `/internal/stewardship` | Metadata stewardship | `resources/views/internal-stewardship.blade.php` | **Derived from Librarian Operations** |
| `/internal/circulation` | Circulation operations | `resources/views/internal-circulation.blade.php` | **Derived from Librarian Operations** |
| `/internal/ai-chat` | Staff AI assistance | `resources/views/internal-ai-chat.blade.php` | **Extension design** |

### Legacy / redirected routes
| Route | Behavior | Note |
|---|---|---|
| `/for-teachers` | `301` → `/resources` | legacy IA retained only for compatibility |
| `/services`, `/news` | `301` → `/` | deprecated public pages |
| `/about` | `301` → `/contacts` | consolidated into contact/about surface |

---

## 2. Stitch-to-project design mapping

## Verified Stitch design system
- **Body font:** `MANROPE`
- **Editorial headline font:** `NEWSREADER`
- **Mode:** `LIGHT`
- **Primary institutional color:** `#003366`
- **Roundness:** `ROUND_FOUR`
- **Visual language:** editorial asymmetry, calm paper surfaces, tonal separation, dense academic browsing, search-first discovery

## Verified Stitch screen mapping
| Stitch screen | Screen id | Real route / surface | Repo target | Adaptation note |
|---|---|---|---|---|
| `Homepage - Digital Library` | `d3368194512b4112a5dac3ad4c7b4081` | `/` | `resources/views/welcome.blade.php` | Search-first institutional landing page |
| `Catalog Search - Digital Library` | `3c1e3537278d4de7a7da0ee88980975d` | `/catalog`, `/app/catalog` | `resources/views/catalog.blade.php`, `resources/js/spa/pages/CatalogPage.jsx` | Core product working surface |
| `Academic Resources - Digital Library` | `5cc308be4a50495cadf9d94f00acb318` | `/resources` | `resources/views/resources.blade.php` | Utility-first access portal |
| `Book Details - Digital Library` | `ee26d2059f664feeb7d158a963b23633` | `/book/{isbn}` | `resources/views/book.blade.php` | Scholarly record hierarchy |
| `Secure Access - Digital Library` | `1568211d66fb4e30b8bb27715eb865d8` | `/login` | `resources/views/auth.blade.php` | Calm first-party auth surface |
| `Member Dashboard - Digital Library` | `e3f15ac499574de192f3d0efcaaf1a42` | `/account` | `resources/views/account.blade.php` | Operational member portal |
| `Teachers Portal - Digital Library` | `6d9c107a96fb45b4808d1cf7a3a989ef` | `/shortlist` | `resources/views/shortlist.blade.php` | Faculty support overview |
| `Literature Workbench - Teachers` | `6b4e75f96f9b49df830a8ba29de5d604` | shortlist flow | `resources/views/shortlist.blade.php` | Draft/export workbench detail |
| `Librarian Operations - Admin Portal` | `d180cd2098bb4aa5b5f6b13ff9748b4b` | `/internal/*` | `resources/views/internal-*.blade.php` | Denser operator UI variant |

## Secondary Stitch variants to reuse as references
| Variant screen | Use |
|---|---|
| `Catalog - Digital Library` (`627b6320ebb84c8d90eaf549c8cbdbc7`) | alternate catalog density and layout hierarchy |
| `Teacher Workbench - Digital Library` (`01298862a45d4aefba3a27ad3eb6a317`) | workbench structure for shortlist detail actions |
| alternate `Book Details`, `Member Dashboard`, `Academic Resources`, and `Secure Access` screens | section hierarchy and content rhythm, not separate routes |

---

## 3. Missing-page design list

These real product surfaces do **not** have a one-to-one Stitch page and need extension design that preserves the same system:

1. **`/contacts`**
   - Extend the homepage/resources typography and quiet section rhythm.
   - Use editorial info blocks for hours, librarians, contacts, and institutional notes.

2. **`/discover`**
   - Extend catalog discovery language into a guided academic exploration page.
   - Emphasize subjects, search jump points, and research starting paths.

3. **`/digital-viewer/{materialId}`**
   - Use book-detail and secure-access patterns for controlled reading surfaces.
   - Prioritize focus mode, access status, citation/help panel, and minimal chrome.

4. **`/book/{isbn}/read`**
   - Transitional reader should visually align with the digital viewer, not legacy branding.

5. **`/internal/ai-chat`**
   - Extend the librarian operations visual system into a utility-first assistant workspace.
   - Keep low-decoration, high-clarity chat panels and evidence/result blocks.

6. **Empty / loading / error / no-results states**
   - No explicit Stitch screens were found for these, but they must inherit the editorial tone.

---

## 4. Reusable component list

### Global shell components
- institutional navbar
- compact footer with route-group navigation
- paper-toned page header / intro band
- editorial section title block
- quiet CTA cluster
- breadcrumb / route context strip

### Discovery components
- large pill search bar
- left filter rail
- active filter summary row
- availability/status chips
- metadata list / bibliographic table
- result card with cover, holdings, and actions
- “related topics / adjacent works” modules

### Portal / dashboard components
- summary stat cards with subdued emphasis
- action queue / task list
- timeline or status rail
- reservation / renewal cards
- teacher shortlist preview block
- role-aware quick action strip

### Staff operation components
- compact metric grid
- tabular work queues
- status legend/chip set
- review/approval panel
- stewardship issue cards
- circulation action drawer or dense side panel

---

## 5. Motion opportunities list

Motion should remain subtle and optional, consistent with the calm academic tone.

1. **Search focus lift**
   - gentle shadow/outline emphasis on focus for the homepage and catalog search bar
2. **Filter interaction feedback**
   - small chip transitions when filters are added/removed
3. **Card hover refinement**
   - restrained elevation or tonal change on results and resource cards
4. **Section reveal on scroll**
   - very light fade/translate for editorial sections only
5. **Dashboard state refresh cues**
   - small live-state transitions for holds, renewals, and staff queue counts
6. **Drawer/panel transitions**
   - shortlist details, circulation actions, or metadata side panels can slide/fade minimally

> All motion must respect `prefers-reduced-motion` and avoid marketing-style animation.

---

## 6. Implementation order recommendation

### Wave 1 — system foundation
- `public/css/shell.css`
- `resources/css/spa.css`
- `resources/views/partials/navbar.blade.php`
- `resources/views/partials/footer.blade.php`

**Why first:** this establishes the institutional design language once and prevents page-by-page drift.

### Wave 2 — public entry and discovery
- `resources/views/welcome.blade.php`
- `resources/views/resources.blade.php`
- `resources/views/contacts.blade.php`
- `resources/views/discover.blade.php`

**Why second:** this sets the public-facing expectation and aligns the entry routes with Stitch.

### Wave 3 — core catalog journey
- `resources/views/catalog.blade.php`
- `resources/js/spa/pages/CatalogPage.jsx`
- `resources/views/book.blade.php`
- `resources/views/digital-viewer.blade.php`

**Why third:** the catalog and detail views are the real product center of gravity.

### Wave 4 — account and faculty workflows
- `resources/views/auth.blade.php`
- `resources/views/account.blade.php`
- `resources/views/shortlist.blade.php`
- `resources/views/reader.blade.php`

**Why fourth:** these routes depend on the discovery and shell language already being stable.

### Wave 5 — internal operations alignment
- `resources/views/internal-dashboard.blade.php`
- `resources/views/internal-review.blade.php`
- `resources/views/internal-stewardship.blade.php`
- `resources/views/internal-circulation.blade.php`
- `resources/views/internal-ai-chat.blade.php`

**Why fifth:** internal tools should inherit the system, then tighten into a denser operational variant.

---

## Guardrails for the next implementation phase
- Keep the **catalog** as the product center of gravity.
- Do **not** reintroduce heavy gradients, dark hero blocks, or generic SaaS marketing tropes.
- Reuse Stitch direction, but do **not** copy it literally or invent unsupported features.
- Preserve route truth, API contracts, session auth behavior, and test markers required by the repo.
- Extend the design system to missing pages so nothing looks visually orphaned.

## Ready for next step
- **Status:** mapping is now strong enough for implementation sequencing.
- **Recommended next command:** `/implement` against Wave 1 → Wave 3 in order, with verification after each wave.
