# Las Delicias Horneadas

A full-stack e-commerce web application built with **PHP** and **MySQL** for an artisanal bakery. The system includes a complete customer-facing storefront for browsing and purchasing pastries, alongside a secure administrative panel for managing inventory, suppliers, and customer orders.

## Features

### Customer Storefront

- **Product Catalog:** Browse items, view details, and filter by categories (e.g., Pan Salado, Repostería Fina).
- **Shopping Cart:** Add products, update quantities, and remove items dynamically.
- **User Authentication:** Secure user registration and login system (`password_hash`).
- **User Account Management:** Manage personal info, multiple shipping addresses, and a securely masked payment method.
- **Checkout System:** Select shipping addresses, review items, and confirm orders with automatic stock reduction.
- **Order History:** Customers can view a comprehensive list of their past orders.

### Admin Dashboard (Role-Based Access)

- **Analytics Dashboard:** View quick statistics like total revenue, active products, and total orders.
- **Inventory Management (CRUD):** Add, edit, or delete products with image uploading support (stored securely as BLOBs).
- **Category & Supplier Management:** Maintain categories and supplier directories. Prevent deletion of entities linked to active products.
- **Order Tracking:** View all customer orders including detailed transaction breakdowns.

## Tech Stack

- **Backend:** PHP 8+
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Styling Framework:** Bootstrap 5.3.2 (with custom CSS tokens)
- **Icons:** Bootstrap Icons

## Project Structure

```text
├── /admin/            # Secure admin panel (CRUD interfaces for products, categories, suppliers)
├── /css/              # Custom stylesheets (style.css, admin.css)
├── /img/              # Static image assets
├── /js/               # Client-side scripts (e.g., login.js form toggling)
├── /layout/           # Reusable UI components (header, footer, admin_header)
├── /php/              # Backend processing scripts (auth, cart logic, checkout, db config)
├── store.sql          # Database schema and initial seed data
├── index.php          # Homepage with latest products
├── menu.php           # Catalog and category filtering
├── cart.php           # Shopping cart interface
├── checkout_page.php  # Order placement page
├── account.php        # User dashboard
└── login.php          # Authentication views
└── about.php          # Simple about us page
```
