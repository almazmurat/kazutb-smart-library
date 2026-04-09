# Design System Specification: The Academic Curator

## 1. Overview & Creative North Star
The "Creative North Star" for this design system is **The Digital Curator**. Unlike standard academic portals that feel like dry repositories or generic SaaS platforms that feel ephemeral, this system is designed to feel like a modern, high-end physical library—permanent, authoritative, and meticulously organized.

To move beyond "template" UI, we employ **Editorial Asymmetry**. This means balancing heavy, traditional serif typography with expansive white space and ultra-functional UI elements. We break the rigid grid by allowing imagery and large display type to bleed or overlap subtly, creating a sense of layered physical media rather than a flat digital screen.

---

## 2. Color & Tonal Architecture
The palette is rooted in institutional trust, utilizing a core of deep, ink-like blues (`primary`) and soft, parchment-inspired neutrals. 

### Core Palette
- **Primary (`#001e40`):** The "Midnight Ink." Used for main brand moments and high-authority headers.
- **Secondary (`#14696d`):** The "Teal Archive." Used for secondary actions and institutional accents.
- **Tertiary (`#2a1c00`):** The "Golden Vellum." Used sparingly for high-value highlights or "Digital" status indicators.
- **Surface (`#f8f9fa`):** The "Soft Neutral." The foundation of the entire experience.

### The "No-Line" Rule
Designers are prohibited from using 1px solid borders to section off content. Traditional lines create visual "noise" that clutters academic density. Instead, define boundaries through:
1.  **Background Shifts:** Place a `surface-container-low` component against a `surface` background.
2.  **Tonal Transitions:** Use subtle shifts from `surface-container` to `surface-container-highest` to indicate nested priority.

### Glass & Gradient Signature
To provide "soul," apply a subtle linear gradient to main CTAs transitioning from `primary` (#001e40) to `primary_container` (#003366). For floating navigation or modal overlays, use **Glassmorphism**: `surface_container_lowest` with 80% opacity and a `20px` backdrop blur.

---

## 3. Typography: The Editorial Contrast
We pair the intellectual weight of a serif with the clinical precision of a sans-serif.

| Level | Token | Font | Weight | Character |
| :--- | :--- | :--- | :--- | :--- |
| **Display** | `display-lg` | Newsreader | 500 | Sophisticated, authoritative. |
| **Headline**| `headline-md` | Newsreader | 600 | Traditional hierarchy. |
| **Title**   | `title-lg` | Manrope | 600 | Functional, modern labeling. |
| **Body**    | `body-md` | Manrope | 400 | Highly legible, clean. |
| **Label**   | `label-sm` | Manrope | 700 | Technical, uppercase for metadata. |

**The Identity Rule:** All "Knowledge Content" (book titles, author names, quotes) must use **Newsreader**. All "System Content" (buttons, inputs, navigation, status) must use **Manrope**.

---

## 4. Elevation & Depth
In this system, depth is not "floating"; it is "stacked."

### The Layering Principle
Achieve hierarchy by stacking surface tokens. 
- **Layer 0 (Base):** `surface`
- **Layer 1 (Sections):** `surface-container-low`
- **Layer 2 (Cards/Items):** `surface-container-lowest` (pure #ffffff)

### Ambient Shadows
When a component must float (e.g., a dropdown or active modal), use a "Whisper Shadow":
- **Values:** `0px 12px 32px`
- **Color:** `on-surface` at 4% opacity. 
- **The Ghost Border:** If accessibility requires a stroke, use `outline-variant` at 15% opacity. Never use 100% opaque borders for containers.

---

## 5. Components

### Cards & Bibliographic Lists
Forbid the use of divider lines between list items. Use **Vertical White Space** (16px or 24px) to separate search results. 
- **Card Styling:** Use `radius-md` (0.375rem). The background should be `surface-container-lowest`. 
- **Interactive State:** On hover, transition the background to `surface-container-high`—do not lift the card with a shadow.

### Buttons
- **Primary:** `primary` background with `on-primary` text. Use `radius-sm` (0.125rem) for a sharper, more professional "architectural" look.
- **Secondary:** Transparent background with `secondary` text and a `Ghost Border` (outline-variant at 20%).
- **Status Chips:** High-contrast badges for availability:
    - **Available:** `secondary_container` background with `on_secondary_container` text.
    - **Reserved:** `tertiary_fixed` background with `on_tertiary_fixed` text.

### Input Fields
- **Aesthetic:** Minimalist. No background color. Only a bottom border of 1px using `outline-variant`. 
- **Active State:** Bottom border thickens to 2px using `primary` color. Label moves to `label-sm` above the line.

### Key Contextual Components
- **The "Citation Tooltip":** Use `inverse_surface` background with `inverse_on_surface` text for high-contrast referencing.
- **Search Bar:** A large, prominent element using `surface_container_low` and `radius-full` to break the geometric rigidity of the cards.

---

## 6. Do's and Don'ts

### Do:
- **Use Intentional Asymmetry:** Align a large `display-lg` heading to the left while keeping the body text in a narrower, centered column to mimic high-end journal layouts.
- **Maximize Density:** Academics prefer seeing more information at once. Use `body-sm` for metadata and `label-sm` for tags to keep the UI "tight."
- **Focus on Tonal Contrast:** Use the difference between `#f8f9fa` (Surface) and `#ffffff` (White) to define sections.

### Don't:
- **No Rounded Corners > 12px:** Except for the search bar, keep corners sharp (`sm` or `md`). Excessive rounding feels "playful" and degrades institutional authority.
- **No Standard Grey Shadows:** Never use `#000000` for shadows. Always tint them with the `primary` or `on-surface` hue to keep the palette cohesive.
- **No Divider Lines:** Avoid the "Excel Sheet" look. Trust the spacing and background shifts to organize the data.