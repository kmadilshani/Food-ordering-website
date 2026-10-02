# Speedy Eats PHP Backend

This backend adds dynamic functionality (auth, foods, categories, cart, checkout) to the static Speedy Eats frontend using PHP + MySQL (XAMPP).

## Requirements

- XAMPP (Apache + MySQL)
- PHP 7.4+ (bundled with XAMPP)

## Directory Structure

```
backend/php/
  config/database.php
  helpers/{response.php,auth.php}
  models/{User.php,Category.php,Food.php,Order.php}
  api/*.php (endpoint scripts)
  schema.sql
```

## Setup Steps

1. Start Apache & MySQL in XAMPP Control Panel
2. Create database:
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create DB named `speedy_eats` (utf8mb4)
3. Import schema & seed data:
   - Select `speedy_eats` DB -> Import -> choose `backend/php/schema.sql`
4. Place project in XAMPP htdocs (already here):
   - Access frontend at: http://localhost/Food_ordering_web-main/
5. API base path (examples):
   - List foods: http://localhost/Food_ordering_web-main/backend/php/api/foods_list.php
   - Search foods: http://localhost/Food_ordering_web-main/backend/php/api/foods_search.php?q=pizza
   - Categories: http://localhost/Food_ordering_web-main/backend/php/api/categories_list.php

## Auth Endpoints

| Action       | Method | Path                              |
| ------------ | ------ | --------------------------------- |
| Register     | POST   | backend/php/api/auth_register.php |
| Login        | POST   | backend/php/api/auth_login.php    |
| Logout       | POST   | backend/php/api/auth_logout.php   |
| Current User | GET    | backend/php/api/auth_me.php       |

POST body (JSON or form):

```
{"name":"User","email":"user@example.com","password":"Secret123"}
```

## Foods & Categories

- List all foods: `foods_list.php`
- Show one: `foods_show.php?id=1`
- Search: `foods_search.php?q=burger`
- List categories: `categories_list.php`
- Foods by category: `foods_by_category.php?category_id=2`

## Cart (Session-Based)

| Action      | Method | Path            | Body         |
| ----------- | ------ | --------------- | ------------ |
| Add item    | POST   | cart_add.php    | food_id, qty |
| List cart   | GET    | cart_list.php   | -            |
| Update qty  | POST   | cart_update.php | food_id, qty |
| Remove item | POST   | cart_remove.php | food_id      |

## Orders

| Action   | Method | Path               |
| -------- | ------ | ------------------ |
| Checkout | POST   | order_checkout.php |

Requires authenticated session and non-empty cart.

## Example Fetch (List Foods)

```js
fetch("backend/php/api/foods_list.php")
  .then((r) => r.json())
  .then((data) => console.log(data.foods));
```

## Notes

- Sessions handle auth + cart; ensure cookies are enabled.
- Extend models for CRUD admin features as needed.
- Sanitize/validate more strictly before production (e.g., email format, password strength).

## Frontend Dynamic Integration

The static HTML pages were adapted to pull live data via `js/dynamic.js`:

| Page / Element                                                                      | Placeholder ID                     | Data Source Endpoint                                                               |
| ----------------------------------------------------------------------------------- | ---------------------------------- | ---------------------------------------------------------------------------------- |
| Foods grids (`index.html`, `foods.html`, `food-search.html`, `category-foods.html`) | `foods-grid`                       | `foods_list.php`, `foods_search.php`, `foods_by_category.php`                      |
| Categories grid (`categories.html`)                                                 | `categories-grid`                  | `categories_list.php`                                                              |
| Cart table on all pages                                                             | `cart-items` / `cart-total-amount` | `cart_list.php` + mutations (`cart_add.php`, `cart_update.php`, `cart_remove.php`) |

Behavior summary:

1. Detects context from query params: `?q=` (search) or `?category_id=` (category foods)
2. Renders food cards with add-to-cart forms (session cart)
3. Intercepts existing search forms and redirects to `food-search.html?q=term`
4. Keeps cart in sync after add/update/remove without full page reload

To revert to static content, remove the `dynamic.js` script include and restore the commented static blocks.
