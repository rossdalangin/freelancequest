<?php

namespace App\Services;

use Database;

class DataManagementService
{
    public static function getSetting(string $key, ?string $default = null): ?string
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT value_text FROM settings WHERE key_name = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : $default;
    }

    public static function setSetting(string $key, string $value): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO settings (key_name, value_text, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP) ON CONFLICT(key_name) DO UPDATE SET value_text = excluded.value_text, updated_at = CURRENT_TIMESTAMP");
        $stmt->execute([$key, $value]);
    }

    public static function logActivity(?int $userId, string $action, ?string $details = null): void
    {
        $pdo = Database::getConnection();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $action, $details, $ip]);
    }

    public static function getAuditLogs(int $limit = 20): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT a.*, u.name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.id DESC LIMIT ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function exportBackupJson(): string
    {
        $pdo = Database::getConnection();
        $data = [
            'exported_at' => date('Y-m-d H:i:s'),
            'users' => $pdo->query("SELECT id, name, email, role, level, xp, coins, subscription_tier FROM users")->fetchAll(),
            'levels' => $pdo->query("SELECT * FROM levels")->fetchAll(),
            'courses' => $pdo->query("SELECT * FROM courses")->fetchAll(),
            'lessons' => $pdo->query("SELECT id, course_id, level_number, title, slug, summary FROM lessons")->fetchAll(),
            'resources' => $pdo->query("SELECT * FROM resources")->fetchAll(),
            'settings' => $pdo->query("SELECT * FROM settings")->fetchAll(),
        ];

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
