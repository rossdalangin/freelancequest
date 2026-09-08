# FREELANCEQUEST — Installation & Deployment Guide

This comprehensive guide covers step-by-step instructions for installing and running **FREELANCEQUEST** in:
1. **Localhost Environment using XAMPP** (Windows, macOS, Linux)
2. **Production Web Server using cPanel**

---

## Technical Requirements
- **PHP Version**: PHP 8.2 or PHP 8.3+
- **PHP Extensions**: `pdo`, `pdo_sqlite` (or `pdo_mysql`), `mbstring`, `json`, `session`, `curl`
- **Web Server**: Apache / Nginx with `mod_rewrite` enabled
- **Database**: SQLite (built-in, zero external setup) or MySQL/MariaDB

---

# Part 1: Installation on Localhost using XAMPP

### Step 1: Download & Install XAMPP
1. Download XAMPP with **PHP 8.2+** from [apachefriends.org](https://www.apachefriends.org/).
2. Install XAMPP to your computer (default path: `C:\xampp` on Windows or `/Applications/XAMPP` on macOS).

### Step 2: Clone or Copy Project Files
1. Open your XAMPP installation directory and navigate to the `htdocs` folder:
   - **Windows**: `C:\xampp\htdocs\`
   - **macOS**: `/Applications/XAMPP/htdocs/`
2. Create a folder named `freelancequest` inside `htdocs`.
3. Copy or clone all FREELANCEQUEST files into `C:\xampp\htdocs\freelancequest\`.

### Step 3: Enable PDO SQLite Extension in PHP
1. Open XAMPP Control Panel.
2. Click **Config** next to Apache -> **PHP (php.ini)**.
3. Search for `extension=pdo_sqlite` and `extension=sqlite3`.
4. Ensure the semicolon `;` at the beginning of these lines is removed:
   ```ini
   extension=pdo_sqlite
   extension=sqlite3
   ```
5. Save `php.ini` and **Restart Apache** in XAMPP Control Panel.

### Step 4: Configure Virtual Host or Subdirectory URL
#### Option A: Direct Apache Localhost Access (Easiest)
Navigate in your web browser to either:
- `http://localhost/freelancequest/` (auto-redirects to `/public/`)
- `http://localhost/freelancequest/public/`

*Note: FREELANCEQUEST includes dynamic URL router parsing so all pages (`/learn`, `/pricing`, `/admin`) resolve seamlessly under XAMPP subfolders without throwing `404 Not Found`!*

#### Option B: Virtual Host Setup (Recommended - `http://freelancequest.local`)
1. Open `C:\xampp\apache\conf\extra\httpd-vhosts.conf` and append:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/freelancequest/public"
       ServerName freelancequest.local
       <Directory "C:/xampp/htdocs/freelancequest/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
2. Open your system `hosts` file (Windows: `C:\Windows\System32\drivers\etc\hosts`) as Administrator and add:
   ```text
   127.0.0.1 freelancequest.local
   ```
3. Restart Apache. Access in browser: `http://freelancequest.local`

### Step 5: Initialize & Seed Database
1. Open Terminal or Command Prompt (`cmd`).
2. Navigate to your project directory:
   ```bash
   cd C:\xampp\htdocs\freelancequest
   ```
3. Execute the native database seeder script:
   ```bash
   php database/seed.php
   ```
   *Output:*
   ```text
   Schema initialized successfully.
   Seeding native database with expanded levels, resources & SOPs...
   Expanded database seeding completed successfully.
   ```
4. Verification complete! You can now log in using test accounts:
   - **Admin Account**: `admin@freelancequest.com` / Password: `password`
   - **Student Account**: `maria@example.com` / Password: `password`

---

# Part 2: Installation on Server using cPanel

### Step 1: Prepare Project Zip File
1. On your local machine, compress the FREELANCEQUEST directory into a `.zip` archive (e.g., `freelancequest.zip`).
2. Ensure `database/database.sqlite` is writeable or let the installer generate it automatically.

### Step 2: Upload Files via cPanel File Manager
1. Log into your **cPanel Account** (`https://yourdomain.com:2083`).
2. Open **File Manager**.
3. Choose the target destination:
   - **Main Domain**: Upload to `/public_html/` directory.
   - **Subdomain or Subfolder** (e.g., `app.yourdomain.com`): Create directory `/public_html/app/` or `/app/`.
4. Upload `freelancequest.zip` and click **Extract**.

### Step 3: Configure Document Root to `/public` Folder
*FREELANCEQUEST uses a Front Controller architecture where public assets reside in the `/public` directory for security.*

#### Option A: Subdomain / Addon Domain (Recommended)
1. Go to cPanel -> **Domains** or **Subdomains**.
2. Set the **Document Root** for your domain to point to `/public_html/public` or `/public_html/app/public`.

#### Option B: Main `public_html` Root (Using Root `.htaccess` Redirect)
If you cannot change the cPanel document root, ensure a `.htaccess` file exists in your primary root (`public_html/.htaccess`) containing:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Step 4: Verify PHP Version & Extensions in cPanel
1. In cPanel, navigate to **Select PHP Version** or **MultiPHP Manager**.
2. Select **PHP 8.2** or **PHP 8.3**.
3. In **PHP Extensions**, verify the following modules are checked/enabled:
   - `pdo`
   - `pdo_sqlite`
   - `sqlite3`
   - `json`
   - `mbstring`
   - `curl`

### Step 5: Set Directory Permissions
In cPanel File Manager:
1. Ensure the `database/` folder has permission `775` or `755` so the web server can read/write the SQLite file.
2. Ensure `database/database.sqlite` (once generated) has file permission `664` or `644`.

### Step 6: Initialize Database on cPanel Server
You have two options to run the database seeder on cPanel:

#### Method 1: Via Terminal in cPanel (Recommended)
1. Open **Terminal** in cPanel.
2. Navigate to your app directory:
   ```bash
   cd public_html
   ```
3. Run the database seeder:
   ```bash
   php database/seed.php
   ```

#### Method 2: Via Browser Execution (Auto-Initialization)
FREELANCEQUEST includes auto-schema initialization inside `config/database.php`. Simply visit your site URL:
`https://yourdomain.com/login`
If the database file does not exist, SQLite will automatically create `database/database.sqlite` and initialize all tables.

### Step 7: Verify cPanel Installation
1. Visit `https://yourdomain.com/login` in your browser.
2. Login using the default administrator credentials:
   - **Email**: `admin@freelancequest.com`
   - **Password**: `password`
3. Access the Admin Dashboard at `https://yourdomain.com/admin` to manage users, content, and export system backups!

---

## Troubleshooting & Common Questions

#### Issue 1: "500 Internal Server Error" or White Screen
- **Cause**: PHP version lower than 8.2 or missing `pdo_sqlite` extension.
- **Fix**: Check cPanel **Error Logs** or Apache error log (`C:\xampp\apache\logs\error.log`). Upgrade PHP version to 8.2+ in cPanel **Select PHP Version**.

#### Issue 2: "Database file is not writable"
- **Cause**: Incorrect folder permissions on `database/`.
- **Fix**: Run `chmod 775 database` in terminal or adjust folder permissions in cPanel File Manager to `775`.

#### Issue 3: CSS / Tailwind styles not loading
- **Cause**: Incorrect document root path or base URL in header layout.
- **Fix**: Ensure your domain points directly to the `/public/` directory so `/css/style.css` resolves correctly.
