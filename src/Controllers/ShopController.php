<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\GameEngineService;
use App\Services\DataManagementService;

class ShopController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;

        $query = "SELECT * FROM products WHERE is_active = 1";
        $params = [];

        if ($category) {
            $query .= " AND category = ?";
            $params[] = $category;
        }

        if ($search) {
            $query .= " AND (title LIKE ? OR description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $query .= " ORDER BY id DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        // Get user's purchased product IDs
        $stmtP = $pdo->prepare("SELECT item_id FROM user_purchases WHERE user_id = ? AND item_type = 'product'");
        $stmtP->execute([$user['id']]);
        $purchasedProductIds = $stmtP->fetchAll(\PDO::FETCH_COLUMN);

        $success = $_SESSION['shop_success'] ?? null;
        $error = $_SESSION['shop_error'] ?? null;
        unset($_SESSION['shop_success'], $_SESSION['shop_error']);

        require __DIR__ . '/../../views/shop/index.php';
    }

    public function show(string $id)
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            http_response_code(404);
            die("Product not found.");
        }

        // Check if user already owns product
        $stmtPurchased = $pdo->prepare("SELECT * FROM user_purchases WHERE user_id = ? AND item_type = 'product' AND item_id = ?");
        $stmtPurchased->execute([$user['id'], $id]);
        $purchaseRecord = $stmtPurchased->fetch();

        $success = $_SESSION['shop_success'] ?? null;
        $error = $_SESSION['shop_error'] ?? null;
        unset($_SESSION['shop_success'], $_SESSION['shop_error']);

        require __DIR__ . '/../../views/shop/show.php';
    }

    public function purchaseWithCoins(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('purchase_coins', 10, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            http_response_code(404);
            die("Product not found.");
        }

        // Check if already purchased
        $stmtCheck = $pdo->prepare("SELECT id FROM user_purchases WHERE user_id = ? AND item_type = 'product' AND item_id = ?");
        $stmtCheck->execute([$user['id'], $id]);
        if ($stmtCheck->fetch()) {
            $_SESSION['shop_success'] = "You already own this digital product! Check your Digital Library.";
            header('Location: /shop/library');
            exit;
        }

        $coinPrice = (int)$product['price_coins'];
        if (($user['coins'] ?? 0) < $coinPrice) {
            $_SESSION['shop_error'] = "Insufficient system coins! You need {$coinPrice} coins but currently have {$user['coins']} coins. Complete more lessons and daily quests to earn coins!";
            header('Location: /shop/' . $id);
            exit;
        }

        // Deduct coins & record purchase
        $stmtDeduct = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
        $stmtDeduct->execute([$coinPrice, $user['id']]);

        $txnId = 'COIN-PRD-' . strtoupper(substr(md5(uniqid((string)rand(), true)), 0, 8));
        $stmtIns = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'product', ?, 'coins', 0.0, ?, ?)");
        $stmtIns->execute([$user['id'], $id, $coinPrice, $txnId]);

        DataManagementService::logActivity($user['id'], 'PRODUCT_PURCHASED_COINS', "Unlocked product #{$id}: {$product['title']} using {$coinPrice} coins");

        $_SESSION['shop_success'] = "🎉 Congratulations! You unlocked '{$product['title']}' for {$coinPrice} coins!";
        header('Location: /shop/library');
        exit;
    }

    public function checkout(string $id)
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            http_response_code(404);
            die("Product not found.");
        }

        // Fetch admin receiving payment account settings
        $stmtSett = $pdo->query("SELECT key_name, value_text FROM settings WHERE key_name IN ('paypal_email', 'stripe_key', 'gcash_number', 'gcash_name')");
        $settingsRaw = $stmtSett->fetchAll();
        $paymentSettings = [];
        foreach ($settingsRaw as $s) {
            $paymentSettings[$s['key_name']] = $s['value_text'];
        }

        $paypalEmail = $paymentSettings['paypal_email'] ?? 'admin@freelancequest.com';
        $stripeKey = $paymentSettings['stripe_key'] ?? 'pk_live_freelancequest_admin_key';
        $gcashNumber = $paymentSettings['gcash_number'] ?? '09171234567';
        $gcashName = $paymentSettings['gcash_name'] ?? 'FreelanceQuest Admin';

        require __DIR__ . '/../../views/shop/checkout.php';
    }

    public function processPayment(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('process_product_payment', 10, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            http_response_code(404);
            die("Product not found.");
        }

        $paymentGateway = $_POST['payment_gateway'] ?? 'paypal';
        if (!in_array($paymentGateway, ['paypal', 'stripe', 'gcash'])) {
            $paymentGateway = 'paypal';
        }

        $txnId = strtoupper($paymentGateway) . '-PRD-' . strtoupper(substr(md5(uniqid((string)rand(), true)), 0, 8));
        $amountPaid = (float)$product['price_usd'];

        // Record purchase in user_purchases
        $stmtIns = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'product', ?, ?, ?, 0, ?)");
        $stmtIns->execute([$user['id'], $id, $paymentGateway, $amountPaid, $txnId]);

        // Record payment in payments audit log
        $stmtPay = $pdo->prepare("INSERT INTO payments (user_id, payment_gateway, transaction_id, amount, status, details) VALUES (?, ?, ?, ?, 'completed', ?)");
        $stmtPay->execute([$user['id'], $paymentGateway, $txnId, $amountPaid, "Purchased Digital Product #{$id}: {$product['title']}"]);

        // Bonus: award 100 XP and 50 coins for digital store cash purchases
        $gameEngine = new GameEngineService();
        $gameEngine->awardXPAndCoins($user['id'], 100, 50);

        DataManagementService::logActivity($user['id'], 'PRODUCT_PURCHASED_GATEWAY', "Purchased product #{$id} via {$paymentGateway} for \${$amountPaid}");

        $_SESSION['shop_success'] = "Payment successful! '{$product['title']}' is now in your Digital Library.";
        header('Location: /shop/library');
        exit;
    }

    public function myPurchases()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        // Product purchases
        $stmtP = $pdo->prepare("SELECT up.*, p.title as item_title, p.description as item_desc, p.file_url, p.category as item_cat, p.image_url
            FROM user_purchases up
            JOIN products p ON up.item_id = p.id
            WHERE up.user_id = ? AND up.item_type = 'product'
            ORDER BY up.id DESC");
        $stmtP->execute([$user['id']]);
        $purchasedProducts = $stmtP->fetchAll();

        // Level/Course unlocked via coins
        $stmtL = $pdo->prepare("SELECT up.*, lvl.title as item_title, lvl.level_number as item_level
            FROM user_purchases up
            JOIN levels lvl ON up.item_id = lvl.level_number
            WHERE up.user_id = ? AND up.item_type = 'course_level'
            ORDER BY up.id DESC");
        $stmtL->execute([$user['id']]);
        $unlockedLevels = $stmtL->fetchAll();

        // Resource vault items unlocked via coins
        $stmtR = $pdo->prepare("SELECT up.*, r.title as item_title, r.file_content_or_url as file_url
            FROM user_purchases up
            JOIN resources r ON up.item_id = r.id
            WHERE up.user_id = ? AND up.item_type = 'resource'
            ORDER BY up.id DESC");
        $stmtR->execute([$user['id']]);
        $unlockedResources = $stmtR->fetchAll();

        $success = $_SESSION['shop_success'] ?? null;
        unset($_SESSION['shop_success']);

        require __DIR__ . '/../../views/shop/library.php';
    }
}
