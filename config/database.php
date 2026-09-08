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
            $stmt = $pdo->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='users'");
            $exists = (int)$stmt->fetchColumn() > 0;
            if (!$exists) {
                require_once __DIR__ . '/../database/seed.php';
                seedDatabase();
            }
        } catch (Exception $e) {
            // Ignore if seeding in progress
        }
    }
}
