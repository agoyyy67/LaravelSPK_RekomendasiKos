---
name: Monochrome Logic
colors:
  surface: '#f9f9f9'
  surface-dim: '#dadada'
  surface-bright: '#f9f9f9'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f3f4'
  surface-container: '#eeeeee'
  surface-container-high: '#e8e8e8'
  surface-container-highest: '#e2e2e2'
  on-surface: '#1a1c1c'
  on-surface-variant: '#4c4546'
  inverse-surface: '#2f3131'
  inverse-on-surface: '#f0f1f1'
  outline: '#7e7576'
  outline-variant: '#cfc4c5'
  surface-tint: '#5e5e5e'
  primary: '#000000'
  on-primary: '#ffffff'
  primary-container: '#1b1b1b'
  on-primary-container: '#848484'
  inverse-primary: '#c6c6c6'
  secondary: '#5d5f5f'
  on-secondary: '#ffffff'
  secondary-container: '#dcdddd'
  on-secondary-container: '#5f6161'
  tertiary: '#000000'
  on-tertiary: '#ffffff'
  tertiary-container: '#1a1c1c'
  on-tertiary-container: '#838484'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#e2e2e2'
  primary-fixed-dim: '#c6c6c6'
  on-primary-fixed: '#1b1b1b'
  on-primary-fixed-variant: '#474747'
  secondary-fixed: '#e2e2e2'
  secondary-fixed-dim: '#c6c6c7'
  on-secondary-fixed: '#1a1c1c'
  on-secondary-fixed-variant: '#454747'
  tertiary-fixed: '#e3e2e2'
  tertiary-fixed-dim: '#c7c6c6'
  on-tertiary-fixed: '#1a1c1c'
  on-tertiary-fixed-variant: '#464747'
  background: '#f9f9f9'
  on-background: '#1a1c1c'
  surface-variant: '#e2e2e2'
typography:
  headline-xl:
    fontFamily: Hanken Grotesk
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
  headline-lg:
    fontFamily: Hanken Grotesk
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
  headline-lg-mobile:
    fontFamily: Hanken Grotesk
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  headline-md:
    fontFamily: Hanken Grotesk
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Hanken Grotesk
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Hanken Grotesk
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Hanken Grotesk
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
  label-sm:
    fontFamily: Hanken Grotesk
    fontSize: 11px
    fontWeight: '500'
    lineHeight: 14px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  container-padding: 32px
  gutter: 24px
  card-gap: 16px
  sidebar-width: 100px
---

## Brand & Style

This design system is built for clarity, objectivity, and decisiveness. Designed for a Decision Support System (SPK SAW), it prioritizes data density without sacrificing legibility. The aesthetic follows a **Modern Minimalist** approach with a high-contrast monochromatic palette. It leverages heavy whitespace and soft elevation to separate complex data sets into digestible "cards" of information.

The brand personality is professional, logical, and trustworthy. It avoids unnecessary flourishes, focusing instead on structural hierarchy and purposeful alignment. The interface feels light and airy, but the deep black accents provide a "grounded" quality that conveys authority and precision.

## Colors

The palette is strictly monochromatic to ensure focus remains on the decision-making data.
- **Primary Black (#000000):** Used for primary buttons, sidebars, and critical headlines. It provides the highest level of visual weight.
- **Surface White (#FFFFFF):** The base background for the entire application and primary card surfaces.
- **Soft Gray (#F5F5F5):** Used for input fields, inactive states, and secondary card containers to provide subtle depth without adding visual noise.
- **Accent Gray (#9E9E9E):** Reserved for metadata, captions, and placeholder text where low emphasis is required.
- **System Status (Implicit):** Success, warning, and error states should use very thin semantic borders or small icons, keeping the primary interface monochromatic.

## Typography

**Hanken Grotesk** is used across all levels for its sharp, contemporary geometry and exceptional legibility in data-heavy environments. 

- **Headlines:** Use Bold (700) weights to create immediate focal points in the dashboard.
- **Body:** Use Regular (400) for all descriptive text. Maintain generous line heights to prevent visual fatigue during long analysis sessions.
- **Labels:** Use SemiBold (600) for navigation elements and table headers to distinguish them from data entries. All-caps is used sparingly for tertiary labels to improve scannability.

## Layout & Spacing

The system uses a **Fluid Grid** model with high-margin containers. A base 8px unit dictates all spacing.

- **Dashboard Layout:** A fixed-width sidebar (100px) sits on the left, containing icon-only navigation. The main content area utilizes a flexible grid that expands to fill the viewport.
- **Margins & Gutters:** Main page containers use 32px padding. Card elements are separated by 24px gutters to maintain a "breathable" feel even when the screen is full of data.
- **Mobile Adaptation:** On mobile devices, the sidebar transitions to a bottom navigation bar. Margins are reduced to 16px, and multi-column card layouts stack vertically.

## Elevation & Depth

This design system uses a combination of **Tonal Layers** and **Soft Ambient Shadows** to define hierarchy.

- **Level 0 (Background):** Pure #FFFFFF.
- **Level 1 (Secondary Containers):** #F5F5F5 flat surfaces with no shadows, used for search bars and secondary data groups.
- **Level 2 (Cards):** #FFFFFF surfaces with a very soft, diffused shadow (0px 4px 20px rgba(0,0,0,0.04)). This makes data "pop" against the background.
- **Level 3 (Active Elements):** Deep black elements (#000000) create the highest contrast, naturally drawing the eye to primary actions.

## Shapes

The shape language is consistently **Rounded**, creating a friendly counterpoint to the rigid monochromatic color scheme.

- **Main Cards:** 1rem (16px) corner radius.
- **Buttons & Inputs:** 0.5rem (8px) corner radius.
- **Sidebar:** A unique "pill" container for the sidebar itself or highly rounded inner containers (1.5rem) as seen in the reference image.
- **Interactive Elements:** Hover states should not change the shape, only the elevation or background tint.

## Components

- **Buttons:** Primary buttons are solid #000000 with #FFFFFF text. Secondary buttons are #F5F5F5 with #000000 text.
- **Cards:** White containers with a 16px border radius and soft ambient shadow. They should contain a clear title and structured data rows.
- **Inputs:** Background set to #F5F5F5 with no border. On focus, a 1px solid #000000 border is added.
- **Data Tables:** Clean, no vertical borders. Use #F5F5F5 for the header row and a simple thin divider between data entries.
- **Sidebar Icons:** Minimalist line icons. Active states should be signified by a solid black background or a subtle shift in icon weight.
- **Progress Indicators:** Use circular strokes with high-contrast black/white ratios to indicate percentage completions in SAW calculations.