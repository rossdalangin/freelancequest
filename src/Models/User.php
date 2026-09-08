<?php

namespace App\Models;

use Database;

class User
{
    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, username, headline, bio, level, xp, coins, streak_count, subscription_tier) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'] ?? 'student',
            $data['username'] ?? null,
            $data['headline'] ?? null,
            $data['bio'] ?? null,
            $data['level'] ?? 0,
            $data['xp'] ?? 0,
            $data['coins'] ?? 100,
            $data['streak_count'] ?? 0,
            $data['subscription_tier'] ?? 'free',
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $fields = [];
        $params = [];
        foreach ($data as $key => $val) {
            $fields[] = "$key = ?";
            $params[] = is_array($val) ? json_encode($val) : $val;
        }
        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
}
