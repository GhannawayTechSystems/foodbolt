# FoodHub — Multi-Kitchen Food Ordering App

A simple, self-contained PHP food ordering web app with **multi-kitchen support**.
Customers browse multiple kitchens, add dishes from different kitchens into one
cart, and place a single order. Each kitchen operator sees only their own
items in the order queue.

No framework, no database server, no Composer — just plain PHP 8.1+.

---

## Features

**Customer side**
- Browse all active kitchens on the home page
- Open a kitchen to see its menu grouped by category
- Add items from **multiple kitchens** into one cart
- Cart shows per-kitchen grouping and a combined delivery fee
- Checkout with name, phone, address, and notes
- Order confirmation page with a short order number

**Admin side** (`/index.php?r=admin/login`)
- Dashboard with stats (kitchens, orders, revenue, pending)
- Manage kitchens (create / edit / delete) — delete blocked while items exist
- Manage menu items (create / edit / delete), filter by kitchen
- Manage orders — change status (pending → preparing → ready → completed / cancelled)
- **Kitchen operator view** — see only the orders (and only the line items) for one kitchen

**Cross-cutting**
- Fully responsive (mobile → desktop)
- CSRF protection on every form
- Flash messages
- Seeded with 3 demo kitchens and 9 menu items on first run
- Data stored in JSON files under `storage/` — zero external dependencies

---

## Requirements

- PHP 8.1 or newer
- That's it. No database, no Composer, no web server required for local use.

---

## Run locally

From the project folder:

```bash
php -S localhost:8000 -t public
```

Then open <http://localhost:8000> in your browser.

The demo data (3 kitchens, 9 dishes) is created automatically on first load
and stored in `storage/`.

---

## Admin access

Go to <http://localhost:8000/index.php?r=admin/login>

- Username: `admin`
- Password: `admin123`

**Change these immediately** in `app/config.php` before any real use.

---

## Project structure

```
project/
├── public/              # Web root (point your server here)
│   ├── index.php        # Front controller / router
│   └── assets/
│       ├── style.css
│       └── app.js
├── app/                 # Application code
│   ├── config.php       # Edit me: name, currency, admin credentials
│   ├── helpers.php      # e(), csrf_*, flash(), money(), url()
│   ├── Storage.php      # JSON-file storage layer (swap for PDO if needed)
│   ├── Seeder.php        # Seeds demo data on first run
│   ├── Kitchen.php      # Kitchen model
│   ├── MenuItem.php     # Menu item model
│   ├── Order.php        # Order model (multi-kitchen grouping)
│   ├── Cart.php         # Session cart
│   ├── Router.php       # Simple route dispatcher
│   ├── View.php         # View + layout renderer
│   ├── PublicController.php
│   └── AdminController.php
├── views/               # PHP templates
│   ├── layouts/layout.php
│   ├── home.php
│   ├── kitchen.php
│   ├── cart.php
│   ├── checkout.php
│   ├── order.php
│   ├── errors/404.php
│   └── admin/
│       ├── login.php
│       ├── _sidebar.php
│       ├── dashboard.php
│       ├── kitchens.php
│       ├── menu.php
│       ├── orders.php
│       └── kitchen_orders.php
└── storage/            # Auto-created JSON data (kitchens, menu_items, orders)
```

---

## Configuration

All settings live in `app/config.php`:

```php
'name'     => 'FoodHub',
'tagline'  => 'Order from multiple kitchens, one cart',
'currency' => '$',
'admin'    => ['username' => 'admin', 'password' => 'admin123'],
'statuses' => ['pending', 'preparing', 'ready', 'completed', 'cancelled'],
```

---

## How multi-kitchen works

A customer can add dishes from any number of kitchens into one cart. At
checkout, the order stores every line item along with its `kitchen_id`. The
delivery fee is charged **per kitchen** in the order (default $2.50 each,
adjust in `Cart::deliveryFee()`).

Each kitchen operator opens **Kitchen Orders** in the admin panel and sees only
their own line items for each order — so multiple kitchens can fulfill one
customer order independently.

---

## Deploying to a real server

1. Copy the whole `project/` folder to your host.
2. Point the web server document root at `public/`.
3. Make sure `storage/` is writable by the web server.
4. Change the admin credentials in `app/config.php`.

### Apache

If you want clean URLs, add this `.htaccess` in `public/`:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?r=$1 [L,QSA]
```

### Nginx

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

## Reusing this project

This app was built to be a reusable local starter. To adapt it:

- **Rebrand:** edit `name`, `tagline`, `currency` in `app/config.php` and the
  logo emoji in `views/layouts/layout.php`.
- **Change the demo data:** edit `app/Seeder.php`.
- **Use a real database:** replace `Storage.php` with a PDO-backed
  implementation that exposes the same methods (`all`, `find`, `insert`,
  `update`, `delete`). No other file needs to change.
- **Add auth:** the admin login is a simple session flag. Swap `AdminController::login()`
  for a proper auth library and hashed password verification before going live.
