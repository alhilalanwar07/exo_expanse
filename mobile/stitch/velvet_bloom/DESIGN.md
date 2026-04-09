# Design System Strategy: The Digital Atelier

## 1. Overview & Creative North Star
**The Creative North Star: "The Digital Curator"**

This design system moves away from the rigid, boxy constraints of traditional SaaS platforms to embrace the soul of a high-end editorial magazine. For a digital invitation platform, the interface must never outshine the event; instead, it acts as a sophisticated gallery—a "Digital Atelier." 

We break the "template" look through **Intentional Asymmetry** and **Tonal Depth**. By utilizing overlapping elements, generous white space (the "breathing room" of luxury), and a high-contrast typography scale, we create an experience that feels bespoke rather than generated. The layout should feel like a series of curated spreads where the invitation itself is the masterpiece, and the UI is the elegant frame.

---

## 2. Colors: Tonal Atmosphere
Our palette transitions from deep, midnight purples to ethereal pastels, mimicking the shift from an evening gala to a soft morning wedding.

*   **Primary Roles:** Use `primary` (#630ed4) and `primary_container` (#7c3aed) for moments of celebration and brand authority.
*   **Secondary/Tertiary Accents:** `secondary` (#b51c0b) provides a passionate "Event Red" for urgency or love, while `tertiary` (#654a00) offers a golden, sun-drenched warmth.
*   **The "No-Line" Rule:** We do not use 1px solid borders to section content. Boundaries must be defined solely through background color shifts. For example, a `surface_container_low` section sitting on a `surface` background provides all the separation needed without the visual "noise" of a line.
*   **Surface Hierarchy & Nesting:** Treat the UI as stacked sheets of fine vellum. Use the `surface_container` tiers (Lowest to Highest) to create depth. An inner card (`surface_container_highest`) should feel like it is physically resting upon a broader section (`surface_container_low`).
*   **The "Glass & Gradient" Rule:** To achieve a premium feel, use Glassmorphism for floating navigation and modal overlays. Apply `surface` colors with a 70% opacity and a `24px` backdrop-blur. 
*   **Signature Textures:** Use subtle linear gradients—from `primary` to `primary_container`—at a 135-degree angle for primary CTAs to give them a gemstone-like dimension.

---

## 3. Typography: Editorial Authority
We pair the geometric clarity of **Plus Jakarta Sans** with the approachable modernism of **Manrope**.

*   **Display & Headline (Plus Jakarta Sans):** These are our "statement" styles. Use `display-lg` for hero invitations. The wide apertures and modern proportions convey a sense of "New Luxury." 
*   **Body & Title (Manrope):** Manrope provides high legibility for event details (dates, locations). Its slightly condensed nature allows for sophisticated data density without feeling cluttered.
*   **Hierarchy as Identity:** Use a dramatic scale difference between `headline-lg` and `body-md`. This contrast is what separates "standard UI" from "high-end editorial." Labels (`label-md`) should be tracked out (0.05rem) to evoke the feel of a printed invitation.

---

## 4. Elevation & Depth: Tonal Layering
We reject the heavy, "drop-shadow-everything" aesthetic of the early 2010s.

*   **The Layering Principle:** Depth is achieved by "stacking" surface tokens. 
    *   *Example:* Place a `surface_container_lowest` card on a `surface_container_low` background. The subtle shift in hex value creates a soft, natural lift.
*   **Ambient Shadows:** If a floating element (like a mobile FAB) requires a shadow, it must be "Ambient." Use a 32px blur with 6% opacity. The shadow color should be a tinted version of `on_surface` (#24162c), never pure black.
*   **The "Ghost Border" Fallback:** If a border is required for accessibility, use the `outline_variant` token at **15% opacity**. This creates a "suggestion" of a boundary that disappears into the background, maintaining the minimalist aesthetic.
*   **Glassmorphism:** Use semi-transparent `surface_variant` colors on overlays. This allows the vibrant event photography to bleed through the UI, making the interface feel integrated with the user's content.

---

## 5. Components: The Refined Primitives

### Buttons & Interaction
*   **Primary Button:** Uses the `primary` fill with a subtle gradient to `primary_container`. Corner radius: `full` (9999px) for a soft, pill-shaped invite feel.
*   **Secondary Button:** Ghost style. No fill, `outline` at 20% opacity, with `primary` colored text.
*   **Haptic Feedback:** Interactions should feel "weightless." On hover, buttons should shift slightly in tone (e.g., from `primary` to `primary_fixed_dim`) rather than using heavy shadows.

### Inputs & Fields
*   **Text Inputs:** No bottom lines or heavy boxes. Use `surface_container_highest` with a `md` (0.75rem) roundedness. Labels should sit above in `label-md` uppercase.
*   **Error States:** Use `error` (#ba1a1a) only for the text and a subtle `error_container` tint for the field background.

### Cards & Lists
*   **The "No-Divider" Rule:** Forbid the use of horizontal rules (`<hr>`). Use vertical white space from the spacing scale (e.g., 24px or 32px) to separate list items. 
*   **Event Cards:** Use a `surface_container_low` base. Images should have a `lg` (1rem) corner radius. Metadata should be styled in `label-sm` for a clean, "metadata" look.

### Platform-Specific Components
*   **The Invitation Tray:** A bottom-sheet component using Glassmorphism (`surface` @ 80% + blur) that "floats" over the invitation preview, holding RSVPs and Registry links.
*   **Status Chips:** Selection chips for "Attending" or "Declined" should use `secondary_container` for high-energy events or `tertiary_fixed` for elegant, neutral affairs.

---

## 6. Do’s and Don’ts

### Do
*   **Do** use asymmetrical margins (e.g., 10% left, 15% right) for hero sections to create an editorial feel.
*   **Do** prioritize whitespace. If a section feels crowded, double the padding instead of adding a border.
*   **Do** use `surface_bright` for the main background to keep the "Event" atmosphere light and welcoming.

### Don’t
*   **Don’t** use 100% opaque black for text. Always use `on_surface` (#24162c) to keep the contrast sophisticated, not jarring.
*   **Don’t** use "Standard" Material shadows. If it looks like a default component, it has failed the "Atelier" test.
*   **Don’t** use more than two font weights in a single view. Let the size and color do the work of hierarchy.
*   **Don’t** use sharp corners. Use the `md` (0.75rem) or `lg` (1rem) tokens to maintain a "soft" and welcoming event-centric persona.