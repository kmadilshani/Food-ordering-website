# Copilot Instructions for Speedy Eats Food Ordering Website

## Project Overview

This is a static HTML/CSS/JS food ordering website ("Speedy Eats") with a responsive design pattern. The project uses jQuery for interactivity, FontAwesome for icons, and a custom CSS grid system.

## Key Architecture Patterns

### HTML Structure Convention

All HTML files follow this exact template structure:

- **Consistent navigation**: Every page includes identical `<nav>` with 6 menu items plus shopping cart
- **Shopping cart embed**: Every page embeds the same cart HTML structure in navigation (lines 40-80)
- **Container pattern**: All content wrapped in `<div class="container">` for consistent width (85%)
- **Section-based layout**: Content organized in semantic `<section>` blocks

### CSS Grid System & Layout

- **Grid classes**: Use `.grid-2` (2 columns) and `.grid-3` (3 columns) for layouts
- **Food cards**: All food items use `.food-menu-box` container with `.food-menu-img` (20% width) + `.food-menu-desc` (70% width) pattern
- **Responsive images**: Always use `.img-responsive` and `.img-curve` for food images
- **Color scheme**: Primary brand color `rgb(226, 74, 74)`, hover color `#6c5ce7`

### File Organization & Naming

```
Root level: All HTML pages (8 total)
css/: Contains style.css, hover-min.css, and font-awesome/
img/: Organized by type (category/, food/, root level for backgrounds)
js/: Contains custom.js for jQuery functionality
```

**Image naming**: Food images use pattern `b1.jpg` (burger), `p1.jpg` (pizza), `s1.jpg` (sandwich)

### JavaScript Patterns (custom.js)

- **jQuery dependency**: All functionality requires jQuery
- **Key features**: Sticky navigation on scroll, cart toggle, cart item deletion with total recalculation
- **Event delegation**: Uses `$(document).on("click", ".btn-delete")` pattern for dynamic content
- **Animation**: Uses jQuery UI effects like `toggle("blind", "", 500)`

## Development Guidelines

### Adding New Food Items

1. Use the food card template from `index.html` lines 140-160
2. Follow the image + description layout pattern
3. Include quantity input: `<input type="number" value="1" min="1">`
4. Price format: Always prefix with "Rs." (e.g., "Rs.550.00")

### Navigation & Cart

- **Cart is static**: Shopping cart HTML is hardcoded in every page (not dynamic)
- **Navigation consistency**: When adding new pages, copy the exact nav structure from any existing page
- **Logo path**: Use `img/food/logo.jpg` for all pages except `food-search.html` (uses `img/logo.png`)

### CSS Utilities

- **Text alignment**: `.text-center`, `.text-left`, `.text-right`, `.text-white`
- **Buttons**: `.btn-primary` (main actions), `.btn-delete` (cart removal)
- **Visual effects**: Use `.hvr-underline-from-center` for menu hover effects
- **Responsive**: `.img-responsive` makes images 100% width

### Form Patterns

- Food ordering forms wrap each item in `<form action="">` tags
- Cart forms include hidden fields for food details
- Use `<input type="submit" class="btn-primary" value="Add To Cart">` for add buttons

## Common Maintenance Tasks

- **Adding pages**: Copy navigation structure from existing page, update active menu state
- **Styling**: Follow the established class naming (`.food-*`, `.cart-*`, `.navbar-*`)
- **Cart updates**: Modify cart HTML in ALL pages simultaneously (it's duplicated across files)
- **Images**: Place in appropriate subdirectory (`img/food/`, `img/category/`) and follow naming conventions
