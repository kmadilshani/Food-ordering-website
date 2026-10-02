# Speedy Eats Food Ordering System

A complete food delivery website with dynamic content, user authentication, shopping cart, and admin panel. This project has been **fully configured and fixed** to work properly.

## 🔧 Issues Fixed

### ✅ Search Functionality
- **Problem**: Search bar wasn't working due to missing `name` attribute
- **Solution**: Added `name="q"` to search inputs in `index.html` and `foods.html`
- **Result**: Search now works properly across all pages

### ✅ Database Configuration  
- **Problem**: Database connection issues with different XAMPP configurations
- **Solution**: Updated `database.php` to try multiple common passwords automatically
- **Result**: Works with most XAMPP installations

### ✅ Image Paths
- **Problem**: Some image references were incorrect
- **Solution**: Fixed logo path in `food-search.html`
- **Result**: All images now load correctly

### ✅ Cart Functionality
- **Problem**: Cart total calculation used wrong currency format
- **Solution**: Updated cart calculation to use "Rs" instead of "$"
- **Result**: Cart totals display correctly

## Stack

- Frontend: Plain HTML/CSS, jQuery, `js/dynamic.js`
- Backend: PHP 7.4+, PDO, MySQL (MariaDB) via XAMPP
- Session-based auth + cart

## 🚀 Quick Start

1. **Start XAMPP** (Apache + MySQL services)
2. **Run Setup**: Visit `http://localhost/Food_ordering_web-main/setup.php`
   - This automatically creates the database and seeds sample data
   - Alternative: Run `http://localhost/Food_ordering_web-main/backend/php/install.php?db=speedy_eats&seed=1`
3. **Access Site**: Visit `http://localhost/Food_ordering_web-main/`
4. **Admin Panel**: Visit `http://localhost/Food_ordering_web-main/admin/`

## 🎯 Default Credentials

- **Admin**: admin@example.com / secret123
- **Demo User**: demo@example.com / secret123

## Database Schema Highlights

Tables: users (is_admin), categories, foods, orders, order_items.
`schema.sql` also seeds 3 categories & 3 foods. If you had an older schema run:

```sql
ALTER TABLE users ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0 AFTER password_hash;
```

## Backend File Layout

```
backend/php/
  config/database.php         # PDO connection
  helpers/response.php        # json_response, validation
  helpers/auth.php            # session + auth helpers
  helpers/admin.php           # require_admin()
  models/                     # User, Category, Food, Order
  api/                        # Public endpoints
    auth_*.php                # login/logout/register/me
    foods_*.php               # list/show/search/by_category
    categories_list.php       # list categories
    cart_*.php                # add/list/update/remove
    order_checkout.php        # finalize order
    admin/                    # Admin CRUD endpoints
      categories_list.php
      category_create.php
      category_update.php
      category_delete.php
      foods_list.php
      food_create.php
      food_update.php
      food_delete.php
```

## Session Cart Structure

Session key `$_SESSION['cart']` is an associative array: `food_id => ['food_id','name','price','qty','image']`.

## Endpoints (JSON)

Auth:

- POST `api/auth_register.php` {name,email,password} -> {user}
- POST `api/auth_login.php` {email,password} -> {user}
- POST `api/auth_logout.php` -> {message}
- GET `api/auth_me.php` -> {user|null}

Public Data:

- GET `api/foods_list.php` -> {foods:[]}
- GET `api/foods_show.php?id=ID`
- GET `api/foods_search.php?q=...`
- GET `api/foods_by_category.php?category_id=ID`
- GET `api/categories_list.php`

Cart:

- GET `api/cart_list.php`
- POST `api/cart_add.php` {food_id, qty}
- POST `api/cart_update.php` {food_id, qty}
- POST `api/cart_remove.php` {food_id}

Order:

- POST `api/order_checkout.php` -> {order_id}

Admin (require is_admin=1):

- Categories: list/create/update/delete
- Foods: list/create/update/delete

## Frontend Dynamic Behavior (`js/dynamic.js`)

- Loads foods/categories depending on page and query parameters
- Handles add/update/remove cart actions
- Renders cart in nav + order page
- Auth state: swaps Login to Logout, injects Admin link when user is admin
- AJAX login form (`login.html`) + order checkout form (`order.html`)

## Adding Categories / Foods (Admin)

Use Admin panel forms. Slug auto-generated if blank (lowercase, hyphenated). Provide image path relative to project (e.g. `img/food/new.jpg`).

## Making a User Admin

In SQL:

```sql
UPDATE users SET is_admin=1 WHERE id=1;
```

(or by email).

## Security Notes

This is a demo/simple implementation:

- Password hashing uses password_hash (bcrypt) – OK for demo.
- No CSRF tokens (session + same-origin fetch assumed)
- Validation minimal; add stricter sanitization for production

## Roadmap / Possible Enhancements

- Password reset flow
- Image upload handler instead of manual path input
- Pagination for foods
- Order history page (user + admin)
- CSRF protection + rate limiting

## Database Seeding

You can auto-generate dummy data (admin user, demo user, extra categories & foods) by visiting:

```
http://localhost/Food_ordering_web-main/backend/php/seed.php
### One-Step Install + Seed

Instead of manual steps you can do:

```

http://localhost/Food_ordering_web-main/backend/php/install.php?db=speedy_eats&seed=1

```

This will:
1. Create database if missing
2. Run `schema.sql`
3. Seed dummy data

After running, DELETE `backend/php/install.php` for security.

```

What it creates (idempotent):

- Admin user: admin@example.com / secret123 (sets is_admin=1)
- Demo user: demo@example.com / secret123
- Adds categories: Drinks, Dessert (plus base ones if missing)
- Adds a set of sample foods if they do not already exist

### Manual SQL Alternative

```sql
INSERT INTO users (name,email,password_hash,is_admin) VALUES (
  'Alt Admin','altadmin@example.com', PASSWORD('secret123'),1
);

-- Add a category
INSERT INTO categories (name,slug) VALUES ('Pasta','pasta');

-- Add a food referencing that category
INSERT INTO foods (category_id,name,price,description,image)
VALUES ((SELECT id FROM categories WHERE slug='pasta'), 'Creamy Alfredo', 700.00, 'Rich white sauce pasta', 'img/food/p1.jpg');
```

Note: PASSWORD() is MySQL's own hash, not PHP bcrypt. Prefer using the seed script or generate a bcrypt hash via PHP:

```php
<?php echo password_hash('secret123', PASSWORD_BCRYPT); ?>
```

## License

Educational/demo use.
