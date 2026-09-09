# FREELANCEQUEST: Comprehensive Installation & Server Setup Guide

Welcome to **FREELANCEQUEST** — The Gamified Virtual Assistant & Freelancing Career Simulator built using native PHP 8.3, SQLite/MySQL, and Tailwind CSS.

---

## 1. System Requirements
- **PHP**: PHP 8.2 or PHP 8.3 (with `pdo_sqlite`, `json`, `mbstring`, `openssl` extensions enabled)
- **Database**: SQLite 3 or MySQL / MariaDB
- **Web Server**: Apache 2.4+ (with `mod_rewrite` enabled) or Nginx 1.20+
- **Localhost Environment**: XAMPP, WAMP, MAMP, or Herd

---

## 2. Localhost Installation (XAMPP / WAMP / MAMP)

### Step 1: Download & Extract Project Files
1. Copy the project folder into your web root:
   - XAMPP Windows: `C:\xampp\htdocs\freelancequest`
   - MAMP macOS: `/Applications/MAMP/htdocs/freelancequest`

### Step 2: Configure Environment & Database
1. Copy `.env.example` to `.env` (or default to `config/database.php`).
2. Run database initialization and seeding:
   ```bash
   cd C:\xampp\htdocs\freelancequest
   php database/seed.php
   ```

### Step 3: Serve the Application
Option A (PHP Built-in Server):
```bash
php -S localhost:8000 -t public
```
Visit `http://localhost:8000` in your web browser.

Option B (XAMPP Apache Server):
Visit `http://localhost/freelancequest/public` or configure a VirtualHost.

---

## 3. Production Deployment Guide (cPanel & Apache Shared Hosting)

### Step 1: Upload Files
Upload all repository files to `public_html` or a subdirectory `/public_html/freelancequest`.

### Step 2: Set Directory Permissions
Ensure write permissions (`755` or `775`) on `database/` so PHP can create and write to `database/database.sqlite`.

### Step 3: Initialize Database
Access cPanel Terminal or SSH:
```bash
php database/seed.php
```

---

## 4. Default Credentials
- **Admin Boss Account**: `admin@freelancequest.com` | Password: `password`
- **Student Account (Maria Santos)**: `maria@example.com` | Password: `password`
