<?php

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dbPath = __DIR__ . '/../database/database.sqlite';
            $dir = dirname($dbPath);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            self::$instance = new PDO('sqlite:' . $dbPath);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$instance->exec('PRAGMA foreign_keys = ON;');

            // Auto-initialize database schema and seed if database is uninitialized
            self::ensureSchemaExists(self::$instance);
        }

        return self::$instance;
    }

    private static function ensureSchemaExists(PDO $pdo): void
    {
        try {
            // Check if users or payments table exists
            $stmt = $pdo->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='users'");
            $usersExist = (int)$stmt->fetchColumn() > 0;

            if (!$usersExist) {
                require_once __DIR__ . '/../database/seed.php';
                seedDatabase();
            } else {
                // Ensure payments table exists on existing installations
                $pdo->exec("CREATE TABLE IF NOT EXISTS payments (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
                    payment_gateway TEXT NOT NULL,
                    transaction_id TEXT UNIQUE NOT NULL,
                    amount REAL NOT NULL,
                    currency TEXT DEFAULT 'USD',
                    status TEXT DEFAULT 'completed',
                    details TEXT,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    key_name TEXT UNIQUE NOT NULL,
                    value_text TEXT,
                    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
                    action TEXT NOT NULL,
                    details TEXT,
                    ip_address TEXT,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS jobs (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
                    title TEXT NOT NULL,
                    company TEXT NOT NULL,
                    category TEXT DEFAULT 'General VA',
                    budget TEXT DEFAULT '$15 - $25/hr',
                    job_type TEXT DEFAULT 'Part-Time',
                    description TEXT NOT NULL,
                    requirements TEXT,
                    status TEXT DEFAULT 'open',
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS job_applications (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    job_id INTEGER REFERENCES jobs(id) ON DELETE CASCADE,
                    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
                    cover_letter TEXT NOT NULL,
                    proposed_rate TEXT NOT NULL,
                    portfolio_url TEXT,
                    status TEXT DEFAULT 'applied',
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS applications_tracker (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
                    company TEXT NOT NULL,
                    position TEXT NOT NULL,
                    applied_date TEXT NOT NULL,
                    status TEXT DEFAULT 'Applied',
                    notes TEXT,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS support_tickets (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    ticket_number TEXT UNIQUE NOT NULL,
                    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
                    subject TEXT NOT NULL,
                    category TEXT DEFAULT 'General Inquiry',
                    priority TEXT DEFAULT 'Medium',
                    status TEXT DEFAULT 'open',
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS support_ticket_replies (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    ticket_id INTEGER REFERENCES support_tickets(id) ON DELETE CASCADE,
                    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
                    message TEXT NOT NULL,
                    is_admin_reply INTEGER DEFAULT 0,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS products (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    slug TEXT UNIQUE NOT NULL,
                    description TEXT NOT NULL,
                    category TEXT DEFAULT 'SOP Vault',
                    price_usd REAL DEFAULT 19.00,
                    price_coins INTEGER DEFAULT 300,
                    file_url TEXT NOT NULL,
                    image_url TEXT,
                    is_active INTEGER DEFAULT 1,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");

                $pdo->exec("CREATE TABLE IF NOT EXISTS user_purchases (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
                    item_type TEXT DEFAULT 'product',
                    item_id INTEGER NOT NULL,
                    payment_method TEXT DEFAULT 'coins',
                    amount_paid REAL DEFAULT 0.0,
                    coins_spent INTEGER DEFAULT 0,
                    transaction_id TEXT UNIQUE NOT NULL,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                );");
            }
        } catch (Exception $e) {
            // Ignore if schema check in progress
        }
    }
}
