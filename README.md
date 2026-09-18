<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<h1 align="center">Inventory Management System</h1>

<p align="center">
  A full-stack web application for managing products, orders, invoices, and customers — built with Laravel and Bootstrap.
</p>

<p align="center">
  <a href="#features">Features</a> &bull;
  <a href="#tech-stack">Tech Stack</a> &bull;
  <a href="#installation">Installation</a> &bull;
  <a href="#usage">Usage</a> &bull;
  <a href="#project-structure">Structure</a> &bull;
  <a href="#contributors">Contributors</a>
</p>

---

## Features

### Product Management
- Add, edit, and track products with unique product codes
- Monitor stock levels with real-time availability
- Restock products via the purchase products module
- Automatic stock deduction upon invoice creation

### Order Management
- Create orders from available product inventory
- AJAX-powered customer auto-fill for faster order creation
- Track order status: Pending &rarr; Delivered
- Separate views for pending and delivered orders

### Invoice & Sales
- Generate invoices from existing orders or create standalone invoices
- Automatic due amount calculation (`total - payment`)
- Auto-creation of customer records for new customers
- Sold products report with aggregated quantities

### Customer Management
- Full customer CRUD operations
- REST API endpoint for AJAX-based customer lookup
- Automatic customer registration during order/invoice creation

### Dashboard
- Summary cards: Stock, Sold Products, Available Products, Pending Orders
- Interactive area and bar charts via Chart.js
- Data tables with export options (Copy, Excel, PDF)

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 8.0+ / Laravel 8.12 |
| **Frontend** | Blade Templates, Bootstrap 4.5, jQuery 3.5 |
| **Database** | MySQL (IMS) |
| **Auth** | Laravel Breeze (session-based) |
| **Admin Theme** | SB Admin 2 |
| **Charts** | Chart.js 2.8 |
| **Data Tables** | DataTables 1.10 (with Excel, PDF, Copy export) |
| **Icons** | Font Awesome 5.15 |
| **Build Tool** | Laravel Mix 6 (Webpack) |
| **Testing** | PHPUnit 9.3 |
| **Code Style** | StyleCI (Laravel preset) |
| **Dev Environment** | Laravel Sail (Docker) |

---

## Installation

### Prerequisites
- PHP 8.0+
- Composer
- MySQL
- Node.js & NPM

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/ekramasif/Inventory_Management_System.git
   cd Inventory_Management_System
   ```

2. **Install PHP dependencies**
   ```bash
   composer update
   ```

3. **Install frontend dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Create a MySQL database named `IMS`**, then run migrations
   ```bash
   php artisan migrate
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. Visit [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Usage

| Route | Description |
|-------|-------------|
| `/register` | Create a new account |
| `/login` | Sign in to the dashboard |
| `/` | Dashboard with summary and charts |
| `/products` | View all products (stock report) |
| `/products/available` | View in-stock products |
| `/products/add` | Add a new product |
| `/orders` | View all orders |
| `/orders/pending` | View pending orders |
| `/orders/delivered` | View delivered orders |
| `/invoices` | View all invoices |
| `/customers` | View all customers |

---

## Project Structure

```
app/
├── Http/Controllers/    # Business logic (Product, Order, Invoice, Customer)
├── Models/              # Eloquent models (Product, Order, Invoice, Customer, User)
resources/
├── views/Admin/         # Blade views for all admin features
├── views/auth/          # Authentication views (login, register, reset)
routes/
├── web.php              # Web routes (30+ routes)
├── api.php              # REST API (customer lookup)
database/
├── migrations/          # Database schema (users, products, customers, orders, invoices)
```

---

## Contributors

<p align="center">
  <img src="https://contributors-img.web.app/image?repo=ekramasif/Inventory_Management_System" />
</p>
