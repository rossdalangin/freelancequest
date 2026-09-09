<?php

namespace App\Controllers;

use Database;
use App\Services\DataManagementService;

class LeaderboardController
{
    public function index()
    {
        $user = AuthController::requireAuth();

        $leaderboardEnabled = DataManagementService::getSetting('enable_leaderboard', '1');
        if ($leaderboardEnabled !== '1' && ($user['role'] ?? '') !== 'admin') {
            $_SESSION['flash_error'] = "The public leaderboard is currently disabled by system administrators.";
            header('Location: /dashboard');
            exit;
        }

        $pdo = Database::getConnection();

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $sortBy = $_GET['sort'] ?? 'level';
        $allowedSorts = [
            'level' => 'level DESC, xp DESC',
            'xp' => 'xp DESC, level DESC',
            'coins' => 'coins DESC, level DESC'
        ];
        $orderBy = $allowedSorts[$sortBy] ?? $allowedSorts['level'];

        $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalPages = max(1, (int)ceil($totalUsers / $perPage));

        $stmt = $pdo->prepare("SELECT id, name, username, avatar_url, headline, level, xp, coins, streak_count, subscription_tier FROM users ORDER BY {$orderBy} LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $rankings = $stmt->fetchAll();

        require __DIR__ . '/../../views/leaderboard/index.php';
    }
}
