# 🍕 FoodBolt — Multi-Kitchen Food Ordering System

A lightweight, self-contained PHP food ordering web application with **multi-kitchen support**. Customers browse multiple kitchens, add dishes from different vendors into one cart, and place a single order. Each kitchen operator sees only their own items in the order queue.

**Zero external dependencies** — no framework, no database server, no Composer. Just PHP 8.1+ and JSON file storage.

---

## ✨ Key Features

### 👥 **Customer Experience**
- 🏪 Browse all active kitchens from a single home page
- 🍽️ Explore menus organized by category per kitchen
- 🛒 Add items from **multiple kitchens** into one unified cart
- 💰 Cart displays per-kitchen grouping with combined delivery fees
- 📦 Checkout with name, phone, address, and special notes
- ✅ Order confirmation with a memorable order number

### 🔐 **Admin Dashboard** (`/index.php?r=admin/login`)
- 📊 Real-time statistics (kitchens, orders, revenue, pending items)
- 🏪 Full kitchen management (create, edit, delete)
- 🍱 Menu item management with per-kitchen filtering
- 📋 Order management with status workflow
- 👨‍🍳 Kitchen operator view — isolated view of only their own orders

### 🌐 **Cross-Cutting Features**
- 📱 Fully responsive design (mobile-first to desktop)
- 🛡️ CSRF protection on every form
- 📢 Flash messages for user feedback
- 🌱 Pre-seeded with 3 demo kitchens and 9 menu items
- 💾 JSON file-based storage (zero external dependencies)

---

## 📋 Requirements

- **PHP 8.1** or newer
- That's it! No database, no Composer, no special web server configuration needed for local development.

---

## 🚀 Getting Started

### Local Development

From the project root directory:

```bash
php -S localhost:8000 -t public
```

Then visit **<http://localhost:8000>** in your browser.

The demo data (3 kitchens, 9 sample dishes) is automatically created on first load and stored in `storage/`.

### Admin Access

Navigate to **<http://localhost:8000/index.php?r=admin/login>**

**Default credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **Important:** Change these immediately in `app/config.php` before any production use.

---

## 📁 Project Structure

```
foodbolt/
├── public/                      # Web server document root
│   ├── index.php               # Front controller & router
│   └── assets/
│       ├── style.css           # Responsive styling
│       └── app.js              # Client-side interactivity
│
├── app/                         # Application logic
│   ├── config.php              # Configuration (edit: name, currency, admin creds)
│   ├── helpers.php             # Utility functions (e(), csrf_*, flash(), money(), url())
│   ├── Storage.php             # JSON file storage abstraction
│   ├── Seeder.php              # Demo data initialization
│   ├── Router.php              # URL routing dispatcher
│   ├── View.php                # Template rendering engine
│   ├── PublicController.php    # Customer-facing routes
│   ├── AdminController.php     # Admin panel routes
│   │
│   ├── Models/                 # Data models
│   │   ├── Kitchen.php         # Kitchen entity
│   │   ├── MenuItem.php        # Menu item entity
│   │   ├── Order.php           # Order with multi-kitchen support
│   │   └── Cart.php            # Session-based shopping cart
│
├── views/                       # PHP template files
│   ├── layouts/
│   │   └── layout.php          # Master layout template
│   ├── home.php                # Kitchen listing page
│   ├── kitchen.php             # Kitchen menu page
│   ├── cart.php                # Shopping cart view
│   ├── checkout.php            # Checkout form
│   ├── order.php               # Order confirmation
│   ├── errors/
│   │   └── 404.php             # Not found page
│   └── admin/
│       ├── login.php           # Admin authentication
│       ├── _sidebar.php        # Navigation sidebar
│       ├── dashboard.php       # Admin overview
│       ├── kitchens.php        # Kitchen management
│       ├── menu.php            # Menu item management
│       ├── orders.php          # Order management
│       └── kitchen_orders.php  # Kitchen operator view
│
└── storage/                     # Auto-generated JSON data (gitignored)
    ├── kitchens.json
    ├── menu_items.json
    └── orders.json
```

---

## ⚙️ Configuration

All application settings are centralized in `app/config.php`:

```php
return [
    'name'     => 'FoodBolt',           // App name
    'tagline'  => 'Order from multiple kitchens, one cart',
    'currency' => '$',                  // Currency symbol
    'admin'    => [
        'username' => 'admin',
        'password' => 'admin123',
    ],
    'statuses' => ['pending', 'preparing', 'ready', 'completed', 'cancelled'],
];
```

**Customize:**
- App name and branding
- Currency symbol
- Admin credentials
- Order status workflow

---

## 🍽️ How Multi-Kitchen Ordering Works

1. **Customer adds items** from any number of kitchens into one cart
2. **At checkout**, each line item is tagged with its `kitchen_id`
3. **Delivery fees** are charged **per kitchen** (default: $2.50 each, configurable in `Cart::deliveryFee()`)
4. **Kitchen operators** log into the admin panel and open "Kitchen Orders"
5. **Each kitchen sees only** their own line items for each order
6. **Multiple kitchens can fulfill** one customer order independently

**Example:** Customer orders a pizza from Kitchen A and noodles from Kitchen B in one checkout. Kitchen A prepares the pizza, Kitchen B prepares the noodles, customer receives one combined order.

---

## 🚢 Deployment

### Shared Hosting or VPS

1. Copy the entire `foodbolt/` directory to your server
2. Point your web server's document root to `foodbolt/public/`
3. Ensure `foodbolt/storage/` is writable by the web server (`chmod 755`)
4. **Update credentials** in `app/config.php` immediately

### Apache Web Server

Add this `.htaccess` file in `public/` for clean URLs:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?r=$1 [L,QSA]
```

### Nginx Web Server

Add this location block to your Nginx configuration:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

## 🔧 Customization Guide

### Rebrand the Application

Edit `app/config.php`:
```php
'name'    => 'Your Food Service',
'tagline' => 'Your tagline here',
```

Update the logo emoji in `views/layouts/layout.php`.

### Customize Demo Data

Edit `app/Seeder.php` to define your own kitchens and menu items instead of the defaults.

### Migrate to a Real Database

Replace the `Storage.php` class with a PDO-backed implementation. The interface is simple:

```php
public function all($type);      // Get all records
public function find($type, $id); // Get one record
public function insert($type, $data); // Create
public function update($type, $id, $data); // Update
public function delete($type, $id); // Delete
```

No other files need modification.

### Implement Proper Authentication

The admin login in `AdminController::login()` uses a basic session flag. For production:
- Integrate a proper auth library (e.g., [Clearice](https://github.com/clearice/clearice), Firebase Auth, or Keycloak)
- Hash passwords using `password_hash()` and `password_verify()`
- Implement session timeout and CSRF token rotation

---

## 🛠️ Architecture Overview

### Router (`app/Router.php`)
Simple URL dispatcher that maps requests to controller actions.

### Controllers
- **PublicController** — handles customer routes (home, kitchen view, cart, checkout, confirmation)
- **AdminController** — handles admin routes (login, dashboard, CRUD operations)

### Models
- **Kitchen** — represents a food vendor
- **MenuItem** — represents a dish (belongs to a kitchen)
- **Order** — represents a complete order (contains items from multiple kitchens)
- **Cart** — session-based shopping cart (handles multi-kitchen grouping)

### Storage Layer (`app/Storage.php`)
JSON-file based persistence. Easily swappable with a database implementation.

### View Renderer (`app/View.php`)
Simple PHP template engine with layout support and context injection.

---

## 📚 Usage Examples

### Add Items to Cart

```php
// In a controller action
session_start();
$cart = new Cart();
$cart->addItem($kitchen_id, $menu_item_id, $quantity, $name, $price);
```

### Fetch Kitchen Orders

```php
$storage = new Storage();
$orders = $storage->all('orders');

// Filter for a specific kitchen
$kitchen_orders = array_filter($orders, function ($order) use ($kitchen_id) {
    return in_array($kitchen_id, array_column($order['items'], 'kitchen_id'));
});
```

### Update Order Status

```php
$storage->update('orders', $order_id, [
    'status' => 'preparing',
    'updated_at' => date('Y-m-d H:i:s'),
]);
```

---

## 🚨 Security Notes

⚠️ **Before going to production:**

1. **Change admin credentials** in `app/config.php`
2. **Implement password hashing** — use `password_hash()` and `password_verify()`
3. **Add session timeout** — expire inactive sessions after 30 minutes
4. **Enable HTTPS** — all forms and authentication must use SSL/TLS
5. **Validate & sanitize input** — all user input is currently HTML-escaped via the `e()` helper; consider additional validation
6. **Restrict storage folder** — ensure `storage/` is not web-accessible or contains an `.htaccess` deny rule
7. **Implement proper logging** — track failed logins, order changes, and admin actions
8. **Add rate limiting** — prevent brute-force attacks on login

---

## 📖 API Reference

### Helper Functions (`app/helpers.php`)

| Function | Purpose |
|----------|---------|
| `e($string)` | HTML escape output |
| `csrf_token()` | Generate CSRF token |
| `csrf_field()` | Render hidden CSRF field |
| `csrf_verify()` | Validate CSRF token |
| `flash($key, $value)` | Store flash message |
| `get_flash($key)` | Retrieve flash message |
| `money($amount)` | Format currency |
| `url($route, $params)` | Generate URL |

### Core Classes

**Storage.php**
```php
$storage->all($type)              // Get all records
$storage->find($type, $id)        // Get one by ID
$storage->insert($type, $data)    // Create & return with ID
$storage->update($type, $id, $data) // Update record
$storage->delete($type, $id)      // Delete record
```

**Cart.php**
```php
$cart->addItem($kitchen_id, $menu_item_id, $qty, $name, $price)
$cart->removeItem($item_id)
$cart->getItems()
$cart->getTotal()
$cart->deliveryFee()
$cart->clear()
```

---

## 🤝 Contributing

This is a starter template designed for educational purposes and small deployments. Contributions welcome!

### Before Submitting
- Follow PSR-12 coding standards
- Test on PHP 8.1+
- Ensure responsive design works on mobile/tablet/desktop

---

## 📄 License

Open source — free to use, modify, and distribute.

---

## 💡 Tips & Tricks

- **Want to change delivery fee?** Edit `Cart::deliveryFee()` method
- **Add new order statuses?** Update the `statuses` array in `config.php`
- **Dark mode?** Modify CSS in `public/assets/style.css`
- **Multi-language?** Add a language array to `config.php` and update views
- **Payment gateway?** Add a payment processor integration in `PublicController::checkout()`

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| "Permission denied" on `storage/` | Run `chmod 755 storage/` |
| Demo data not loading | Delete `storage/` folder and reload |
| Routes not working (404) | Ensure `.htaccess` is in `public/` or check Nginx config |
| Session data lost | Check PHP session settings and `storage/` permissions |
| Admin login fails | Reset to defaults in `config.php` (this is plaintext for demo only!) |

---

## 📞 Support

For issues, questions, or suggestions, please open an issue on GitHub or review the code comments for additional context.

---

**Built with ❤️ for food delivery innovation. Happy coding!**
