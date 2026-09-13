<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\DataManagementService;

class ResourceVaultController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT * FROM resources ORDER BY level_number ASC, id ASC");
        $resources = $stmt->fetchAll();

        // Get unlocked resource IDs for user
        $stmtPurchased = $pdo->prepare("SELECT item_id FROM user_purchases WHERE user_id = ? AND item_type = 'resource'");
        $stmtPurchased->execute([$user['id']]);
        $unlockedResourceIds = $stmtPurchased->fetchAll(\PDO::FETCH_COLUMN);

        $success = $_SESSION['resource_success'] ?? null;
        $error = $_SESSION['resource_error'] ?? null;
        unset($_SESSION['resource_success'], $_SESSION['resource_error']);

        require __DIR__ . '/../../views/resources/index.php';
    }

    public function unlockResourceWithCoins(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM resources WHERE id = ?");
        $stmt->execute([$id]);
        $resource = $stmt->fetch();

        if (!$resource) {
            http_response_code(404);
            die("Resource not found.");
        }

        $coinCost = 150; // 150 coins to unlock a premium vault resource

        if (($user['coins'] ?? 0) < $coinCost) {
            $_SESSION['resource_error'] = "Insufficient coins! Unlocking '{$resource['title']}' requires {$coinCost} coins. You currently have {$user['coins']} coins.";
            header('Location: /resources');
            exit;
        }

        // Check if already unlocked
        $stmtCheck = $pdo->prepare("SELECT id FROM user_purchases WHERE user_id = ? AND item_type = 'resource' AND item_id = ?");
        $stmtCheck->execute([$user['id'], $id]);
        if ($stmtCheck->fetch()) {
            $_SESSION['resource_success'] = "Resource '{$resource['title']}' is already unlocked!";
            header('Location: /resources');
            exit;
        }

        // Deduct coins & record purchase
        $stmtDeduct = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
        $stmtDeduct->execute([$coinCost, $user['id']]);

        $txnId = 'COIN-RES-' . strtoupper(substr(md5(uniqid((string)rand(), true)), 0, 8));
        $stmtIns = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'resource', ?, 'coins', 0.0, ?, ?)");
        $stmtIns->execute([$user['id'], $id, $coinCost, $txnId]);

        DataManagementService::logActivity($user['id'], 'RESOURCE_UNLOCKED_COINS', "Unlocked premium resource #{$id}: {$resource['title']} using {$coinCost} coins");

        $_SESSION['resource_success'] = "🎉 Premium Resource '{$resource['title']}' has been unlocked for {$coinCost} Coins!";
        header('Location: /resources');
        exit;
    }
}
