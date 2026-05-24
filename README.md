# 🏋️‍♂️ FitZone Gym Management System

[![PHP Version](https://img.shields.io/badge/PHP-7.4%20%7C%208.0%20%7C%208.1%20%7C%208.2-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%20%7C%205.7-orange.svg)](https://www.mysql.com/)
[![AngularJS](https://img.shields.io/badge/AngularJS-1.8.2-red.svg)](https://angularjs.org/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

FitZone is a web-based **Gym Management System** designed for gym owners and administrators to manage members, memberships, subscription plans, and payment logs. The application features a dark-themed, responsive dashboard and uses AngularJS for real-time frontend validation and API interaction, combined with a PHP backend and MySQL database.

---

## ✨ Key Features

- **📊 Admin Dashboard**: Visual cards showing key metrics:
  - Total Active/Expired Members
  - Active Subscription Plans
  - Monthly/Total Revenue
  - Pending Payments
- **👥 Member Management**: Register new members, search/filter members, and track status (`Active`, `Expired`, `Expiring Soon`).
- **💳 Plan Configurator**: Create and manage gym plans (e.g., Basic, Standard, Premium) with custom pricing and duration.
- **📅 Membership Tracking**: Assign members to plans, automatically compute expiry dates, and trigger status updates.
- **💵 Payment Tracking**: Monitor payment transactions, record notes, and track statuses (`Paid`, `Pending`, `Partial`).
- **🛡️ Secure Admin Authentication**: Login session tracking with AngularJS client-side validation and PHP session management.

---

## 🛠️ Tech Stack & Dependencies

- **Frontend**: HTML5, Vanilla CSS3 (Custom Variables, Flexbox/Grid), AngularJS (1.8.2), FontAwesome (icons), Google Fonts (Inter).
- **Backend**: Vanilla PHP (Object-oriented PDO database interactions).
- **Database**: MySQL (relational schema with cascading deletes on member deletion).
- **Server**: Apache (via XAMPP / WAMP / LAMP).

---

## 📁 Project Structure

```text
WT-Mini-Project-Gym-Management-System/
│
├── api/                   # PHP Endpoint Files for AJAX requests
│   ├── login_process.php  # Handles admin login validation
│   ├── logout.php         # Destroys sessions
│   ├── members_api.php    # CRUD operations for members
│   ├── plans_api.php      # Retrieve and manage membership plans
│   └── register_process.p # Handles new admin registration
│
├── assets/                # CSS and JS resources
│   ├── css/
│   │   ├── dashboard.css  # Styles specific to the admin panel
│   │   └── style.css      # Core global stylesheet (auth pages, utility classes)
│   └── js/
│       ├── app.js         # AngularJS module & controllers (login, registration)
│       ├── dashboard.js   # AngularJS controller for dashboard CRUD
│       └── validation.js  # Additional client-side validation helpers
│
├── config/                # Configuration settings
│   ├── db.php             # Active database config (git-ignored)
│   └── db.example.php     # Template database config
│
├── includes/              # Reusable UI component blocks
│   ├── header.php         # Head metadata, CSS links, and navbar
│   ├── footer.php         # Footer info and script references
│   ├── sidebar.php        # Navigation sidebar for the dashboard pages
│   └── session_check.php  # Middle-ware check to redirect unauthorized access
│
├── modules/               # Sub-pages / views grouped by features
│   ├── members/           # Members listing and registration views
│   ├── memberships/       # Membership assignments
│   ├── payments/          # Payments ledger and bills
│   └── plans/             # Subscriptions & plans management
│
├── index.php              # Login landing page
├── register.php           # Admin registration page
├── dashboard.php          # Main admin panel view
├── schema.sql             # SQL database script to import tables and seed data
├── setup_admin.php        # Troubleshooting helper to reset/create admin login
├── .gitignore             # Config to prevent leaking environment files
└── README.md              # Technical documentation (this file)
```

---

## 🚀 Step-by-Step Setup Guide

Follow these steps to set up and run the project locally on your machine:

### 1. Prerequisites
Install a local server suite that supports PHP and MySQL. We recommend **[XAMPP](https://www.apachefriends.org/)** (v7.4 or newer).

### 2. Project Installation
Clone the repository or extract the project zip directly into your XAMPP's public folder:
* Path for Windows: `C:\xampp\htdocs\gym`
* Path for macOS: `/Applications/XAMPP/xamppfiles/htdocs/gym`

### 3. Database Configuration
1. Open XAMPP Control Panel and start the **Apache** and **MySQL** services.
2. Open your browser and navigate to **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Click on the **Import** tab.
4. Choose the `schema.sql` file located in the root of the project directory.
5. Click **Go** / **Import**. This will automatically create the `gym_management` database and populate the tables with sample seed data.

### 4. Connection Configuration (Credentials Protection)
We protect credentials by ignoring the main configuration file `config/db.php`.
1. Go to the `config/` directory.
2. Copy `db.example.php` and rename it to `db.php`.
3. Open `db.php` in your text editor and specify your local database password if you have one set:
   ```php
   $host = 'localhost';
   $user = 'root';
   $password = 'YOUR_MYSQL_PASSWORD'; // Replace with your MySQL password (empty by default in XAMPP)
   $dbname = 'gym_management';
   ```

### 5. Accessing the Application
Once Apache is running and the database is configured:
1. Open your web browser.
2. Access the project via: **[http://localhost/gym/index.php](http://localhost/gym/index.php)**.

---

## 🔐 Credentials & Troubleshooting

### Seed Admin User Details
For testing purposes, the database is pre-seeded with the following default admin credentials:
- **Email**: `admin@gym.com`
- **Password**: `Admin@123`

### Force Admin Reset (Troubleshooting)
If you run into issues logging in or need to reset the admin user:
1. Open your browser and visit: **[http://localhost/gym/setup_admin.php](http://localhost/gym/setup_admin.php)**.
2. This runs an automatic recovery script that resets the default admin credentials inside the database.
3. Once completed, return to `index.php` and log in.

> [!WARNING]
> For production environments, delete `setup_admin.php` or restrict its access to prevent unauthorized account resets.

---

## 🔒 Security & Privacy Best Practices

- **`.gitignore` Integration**: The file `config/db.php` is explicitly ignored by Git rules. This prevents database usernames, passwords, or production server configs from being pushed to public GitHub repositories.
- **Password Hashing**: Admin passwords are encrypted using PHP's `password_hash()` with `PASSWORD_BCRYPT` before saving to MySQL.
- **SQL Injection Prevention**: All queries are executed using PDO Prepared Statements with parameterized placeholders.
