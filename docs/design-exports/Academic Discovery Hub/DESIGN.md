# Design System: The Academic Curator

## 1. Overview & Creative North Star
The Creative North Star for this design system is **"The Digital Curator."** 

Moving away from the cluttered, utility-first look of traditional database libraries, this system treats digital assets as high-end editorial content. We reject the "template" aesthetic in favor of a bespoke, prestigious atmosphere that feels like a quiet, sunlit physical library. 

The design breaks the rigid, boxed-in grid by utilizing **intentional asymmetry**—offsetting serif headlines against sans-serif metadata—and leveraging **tonal layering** to define space. We prioritize a "High-Trust" atmosphere where the interface recedes, allowing the scholarly content to take center stage through generous white space and a sophisticated, ink-based color palette.

---

## 2. Colors & Surface Philosophy
The palette is rooted in the depth of "Midnight Ink" and "Deep Navy," balanced by the airy "Soft Light Grey."

### Color Roles
- **Primary (`#000613` to `#001F3F`):** Used for high-authority elements—headers, primary navigation, and deep background anchors.
- **Secondary (`#006A6A`):** A restrained teal used sparingly for subtle calls-to-action, active states, and scholarly highlights.
- **Surface (`#F8F9FA`):** The foundation. It provides the "paper" feel for the platform.

### The "No-Line" Rule
**Explicit Instruction:** 1px solid borders are strictly prohibited for sectioning or card definition. 
Boundaries must be defined solely through background color shifts. For example, a `surface-container-low` section sitting on a `surface` background provides all the definition needed. Use the Spacing Scale to let white space act as the invisible divider.

### Surface Hierarchy & Nesting
Treat the UI as physical layers of fine paper. 
- Use `surface-container-lowest` (#FFFFFF) for the most prominent content cards to make them "pop" off the `surface` (#F8F9FA) background. 
- When nesting information (e.g., a search bar inside a hero), move one step higher or lower in the tier (e.g., `surface-container-high`) rather than adding a stroke.

### Signature Textures
To avoid a "flat" digital feel, use subtle gradients for primary CTAs or hero backgrounds. A transition from `primary` (#000613) to `primary_container` (#001F3F) adds a "Midnight Ink" depth that feels premium and intentional.

---

## 3. Typography
The system employs a high-contrast pairing: **Newsreader** (Serif) for narrative authority and **Manrope** (Sans-Serif) for modern utility.

| Level | Token | Font Family | Size | Weight / Usage |
| :--- | :--- | :--- | :--- | :--- |
| **Display** | `display-lg` | Newsreader | 3.5rem | Editorial hero headers; minimal tracking. |
| **Headline**| `headline-md` | Newsreader | 1.75rem | Book titles and section headers. |
| **Title** | `title-lg` | Manrope | 1.375rem | Component headers; bold for hierarchy. |
| **Body** | `body-lg` | Manrope | 1rem | Long-form reading; optimized line height (1.6). |
| **Label** | `label-md` | Manrope | 0.75rem | Metadata, tags, and small utility text. |

**Editorial Intent:** Use `display-lg` for short, punchy phrases. The serif choice signals academic prestige, while the sans-serif body ensures legibility during prolonged research sessions.

---

## 4. Elevation & Depth
In "The Digital Curator," we move away from drop-shadow-heavy components toward **Tonal Layering.**

- **The Layering Principle:** Depth is achieved by "stacking." Place a `surface-container-lowest` card on a `surface-container-low` section. This creates a soft, natural lift that mimics heavy-stock paper.
- **Ambient Shadows:** When an element must float (e.g., a dropdown), use an extra-diffused shadow.
    - *Blur:* 24px–48px. 
    - *Opacity:* 4% of `on-surface`. 
    - *Color:* Tinted with the primary navy to maintain tonal harmony.
- **The "Ghost Border" Fallback:** If a border is required for accessibility (e.g., input fields), use `outline-variant` at **20% opacity**. Never use a 100% opaque border.
- **Glassmorphism:** For the fixed global navbar, use a semi-transparent `surface` color with a `backdrop-blur` (10px–20px). This allows content to bleed through softly as the user scrolls, maintaining a sense of place.

---

## 5. Components

### Buttons
- **Primary:** Gradient fill (`primary` to `primary_container`), `md` (0.375rem) roundedness. No border. Text in `on_primary`.
- **Secondary:** Transparent background with a "Ghost Border" (20% opacity `outline`). Text in `secondary`.
- **Tertiary:** Text only (`primary`) with a slight background shift to `surface-variant` on hover.

### Cards & Lists
- **Rule:** Forbid divider lines. Use vertical spacing (e.g., 32px or 48px) to separate items.
- **Interaction:** On hover, a card should shift from `surface-container-lowest` to `surface-container-high` rather than growing or casting a heavy shadow.

### Input Fields
- **Style:** Subtle `surface-container-highest` fill. Bottom-only "Ghost Border" for a minimalist, academic look. 
- **States:** Focus state uses a `secondary` (teal) 1px bottom-border—the only time a 1px line is encouraged.

### Signature Component: The "Scholar’s Drawer"
A sliding side-panel for citations and bookmarks. Use a backdrop-blur effect and `surface-container-lowest` background to distinguish it as an overlay without disconnecting it from the library context.

---

## 6. Do’s and Don’ts

### Do:
- **Do** use generous white space (margin-bottom: 80px+) between major sections to let the academic content breathe.
- **Do** use `secondary` (teal) only for "moments of action"—a citation link, a download button, or a search filter.
- **Do** align serif headlines asymmetrically (e.g., slightly offset to the left) to create a custom editorial feel.

### Don’t:
- **Don't** use standard #000000 black. Always use the Primary `on-background` (#191C1D) for text to maintain the "Ink" aesthetic.
- **Don't** use sharp corners. Always apply at least the `DEFAULT` (0.25rem) or `md` (0.375rem) roundedness to soften the interface.
- **Don't** use high-speed animations. Transitions should be slow and graceful (300ms–500ms) to reflect the quiet atmosphere of a library.