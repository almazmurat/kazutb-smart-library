# Stitch → KazUTB full reset mapping

## Stitch access confirmed via MCP
- project: `KazUTB Digital Library Platform`
- project id: `4601252383613536784`
- verified on: `2026-04-08`
- active design direction: `The Academic Curator` → strengthened with a project-wide reset prompt toward **The Living Archive**

## Verified Stitch screens
| Stitch screen | Screen id | KazUTB route | Primary repo file |
|---|---|---|---|
| `Homepage - Digital Library` | `d3368194512b4112a5dac3ad4c7b4081` | `/` | `resources/views/welcome.blade.php` |
| `Catalog Search - Digital Library` | `3c1e3537278d4de7a7da0ee88980975d` | `/app/catalog`, `/catalog` | `resources/js/spa/pages/CatalogPage.jsx`, `resources/views/catalog.blade.php` |
| `Academic Resources - Digital Library` | `5cc308be4a50495cadf9d94f00acb318` | `/resources` | `resources/views/resources.blade.php` |
| `Book Details - Digital Library` | `ee26d2059f664feeb7d158a963b23633` | `/book/{isbn}` | `resources/views/book.blade.php` |
| `Secure Access - Digital Library` | `1568211d66fb4e30b8bb27715eb865d8` | `/login` | `resources/views/auth.blade.php` |
| `Member Dashboard - Digital Library` | `e3f15ac499574de192f3d0efcaaf1a42` | `/account` | `resources/views/account.blade.php` |
| `Teachers Portal - Digital Library` | `6d9c107a96fb45b4808d1cf7a3a989ef` | `/shortlist` + teacher modules | `resources/views/shortlist.blade.php` |
| `Literature Workbench - Teachers` | `6b4e75f96f9b49df830a8ba29de5d604` | shortlist workbench flow | `resources/views/shortlist.blade.php` |
| `Librarian Operations - Admin Portal` | `d180cd2098bb4aa5b5f6b13ff9748b4b` | `/internal/*` | `resources/views/internal-*.blade.php` |

---

## Core mismatch diagnosed
The problem is **not** that the implementation is ugly; the problem is that it still preserves too much of the old DNA.

### Stitch direction
- light editorial surfaces
- lots of air
- тонкая академическая типографика
- calm navy / paper palette
- quiet, premium institutional hierarchy
- discovery-first product feel
- minimal visual noise
- serious university system tone

### Current implementation drift to remove
- dark, massive hero blocks
- large gradients
- heavy CTA treatment
- landing-page / marketing feeling
- old card and shell patterns still visible
- redesign that reads as a polish pass instead of a replacement

**Conclusion:** this must be treated as a **full visual reset**, not a homepage polish.

---

## Non-negotiable reset rules
1. **Do not preserve the old style by default.**
2. **Do not build a generic SaaS shell.**
3. **Do not add brochure filler, fake metrics, or testimonials.**
4. **Do not optimize for “pretty only”; workflow clarity comes first.**
5. **Make the catalog the product center of gravity.**
6. **Keep the result original and repo-grounded; use Stitch as direction, not as a literal copy source.**

---

## Page-by-page adaptation map

### 1) Homepage → `resources/views/welcome.blade.php`
**Required shift:** from marketing hero to **search-first academic entry surface**.

Must emphasize:
- large global search
- clear pathways into `catalog`, `resources`, `account`, `shortlist`
- distinction between physical holdings, local digital materials, and licensed external resources
- lighter editorial composition and quieter header

Remove or reduce:
- dark blocks
- loud gradients
- oversized CTA feel
- brochure-style promotional rhythm

### 2) Catalog → `resources/js/spa/pages/CatalogPage.jsx`, `resources/views/catalog.blade.php`
**Required shift:** make this the **main working interface** of the platform.

Must emphasize:
- left filter rail
- strong active-filter summary
- subtle availability/status chips
- richer metadata hierarchy
- UDC/discovery growth path
- denser, calmer academic browsing patterns

### 3) Book detail → `resources/views/book.blade.php`
**Required shift:** from showcase card composition to **scholarly bibliographic record**.

Must emphasize:
- metadata hierarchy
- copy/storage availability
- reserve and shortlist actions
- digital access and external references
- related items in a quieter structure

### 4) Resources → `resources/views/resources.blade.php`
**Required shift:** from informative promo page to **utility-first access portal**.

Must emphasize:
- strong provider cards
- practical access-mode clarity
- licensed external resources vs local digital collections
- less filler text, more action clarity

### 5) Teachers / shortlist workbench → `resources/views/shortlist.blade.php`
**Required shift:** from brochure grouping to **task-oriented literature preparation**.

Must emphasize:
- shortlist/workbench layout
- selected literature flow
- external resource integration
- continue/export actions
- course-preparation usability

> Keep the consolidated IA. Do **not** restore the old `/for-teachers` landing page.

### 6) Secure access → `resources/views/auth.blade.php`
**Required shift:** to a first-party, premium, calm institutional login experience.

Must emphasize:
- secure university access messaging
- cleaner form hierarchy
- polished but minimal auth surface

### 7) Member dashboard → `resources/views/account.blade.php`
**Required shift:** from general cards to **role-aware operational portal**.

Must emphasize:
- loans / renewals
- reservations
- quick actions
- teacher modules when applicable
- meaningful empty states and status surfaces

### 8) Staff/admin direction → `resources/views/internal-*.blade.php`
**Required shift:** establish a **denser operational UI language** that is consistent with the public system but not styled like a marketing page.

Must emphasize:
- forms and tables
- scanability
- stewardship/circulation readiness
- clarity over decoration

---

## Recommended implementation waves
1. **Global design-token reset**
   - `public/css/shell.css`
   - `resources/css/app.css`
   - `resources/css/spa.css`
   - typography, spacing, surfaces, border/shadow discipline

2. **Public entry reset**
   - `welcome.blade.php`
   - `resources.blade.php`
   - `partials/navbar.blade.php`
   - `partials/footer.blade.php`
   - `auth.blade.php`

3. **Core product surface reset**
   - `CatalogPage.jsx`
   - `catalog.blade.php`
   - `book.blade.php`

4. **Teacher + account reset**
   - `account.blade.php`
   - `shortlist.blade.php`

5. **Internal operations alignment**
   - `internal-dashboard.blade.php`
   - `internal-review.blade.php`
   - `internal-stewardship.blade.php`
   - `internal-circulation.blade.php`

---

## What was done in Stitch this session
- Accessed the live Stitch project through MCP.
- Verified the full multi-page screen set and mapped each screen to the real KazUTB routes/files.
- Submitted a project-wide screen edit prompt to push the direction further away from dark/marketing-heavy UI and toward a lighter editorial institutional system.

## Next build focus
The next implementation wave should be a **true shell/token reset first**, then a **catalog-first product redesign**, not another surface-level homepage polish.

