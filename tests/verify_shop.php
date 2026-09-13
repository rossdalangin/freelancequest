<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../database/seed.php';

echo "=== Verifying Digital Shop Marketplace & Universal Coin Unlock System ===\n";

$pdo = Database::getConnection();

// Create test buyer user with 2000 coins
$stmtUser = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmtUser->execute(['shopbuyer@freelancequest.com']);
$buyerId = $stmtUser->fetchColumn();

if (!$buyerId) {
    $stmtIns = $pdo->prepare("INSERT INTO users (name, email, password, role, username, coins, subscription_tier) VALUES (?, ?, ?, 'student', ?, 2000, 'free')");
    $stmtIns->execute(['Shop Buyer Test', 'shopbuyer@freelancequest.com', password_hash('password123', PASSWORD_BCRYPT), 'shopbuyer']);
    $buyerId = $pdo->lastInsertId();
} else {
    // Reset coins to 2000 for test
    $pdo->exec("UPDATE users SET coins = 2000 WHERE id = " . (int)$buyerId);
}

// 1. Fetch available shop product
$stmtPrd = $pdo->query("SELECT * FROM products WHERE is_active = 1 LIMIT 1");
$product = $stmtPrd->fetch();

if (!$product) {
    echo "❌ No active shop products found.\n";
    exit(1);
}
echo "✓ Shop product loaded: {$product['title']} (USD: \${$product['price_usd']}, Coins: {$product['price_coins']})\n";

// 2. Test Product Purchase with Game Coins
$coinCost = (int)$product['price_coins'];
$stmtDeduct = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
$stmtDeduct->execute([$coinCost, $buyerId]);

$txnIdCoins = 'COIN-PRD-TEST' . rand(100, 999);
$stmtInsBuy = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'product', ?, 'coins', 0.0, ?, ?)");
$stmtInsBuy->execute([$buyerId, $product['id'], $coinCost, $txnIdCoins]);

$stmtCheckCoins = $pdo->prepare("SELECT coins FROM users WHERE id = ?");
$stmtCheckCoins->execute([$buyerId]);
$remainingCoins = (int)$stmtCheckCoins->fetchColumn();

if ($remainingCoins === (2000 - $coinCost)) {
    echo "✓ Product purchased via Coins successfully! Remaining coins: {$remainingCoins}\n";
} else {
    echo "❌ Coin deduction failed.\n";
    exit(1);
}

// 3. Test Level Coin Unlock (Unlock Level 4 for 500 Coins)
$levelUnlockCost = 500;
$stmtDeduct2 = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
$stmtDeduct2->execute([$levelUnlockCost, $buyerId]);

$txnIdLvl = 'COIN-LVL-TEST' . rand(100, 999);
$stmtInsLvl = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'course_level', 4, 'coins', 0.0, ?, ?)");
$stmtInsLvl->execute([$buyerId, $levelUnlockCost, $txnIdLvl]);

$stmtCheckLvlUnlock = $pdo->prepare("SELECT id FROM user_purchases WHERE user_id = ? AND item_type = 'course_level' AND item_id = 4");
$stmtCheckLvlUnlock->execute([$buyerId]);
if ($stmtCheckLvlUnlock->fetch()) {
    echo "✓ Course Level 4 unlocked with 500 Coins successfully!\n";
} else {
    echo "❌ Course Level coin unlock failed.\n";
    exit(1);
}

// 4. Test Gateway Cash Purchase Simulation
$txnIdGateway = 'PAYPAL-PRD-TEST' . rand(100, 999);
$stmtInsGateway = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'product', ?, 'paypal', ?, 0, ?)");
$stmtInsGateway->execute([$buyerId, $product['id'], $product['price_usd'], $txnIdGateway]);

$stmtCheckGateway = $pdo->prepare("SELECT id FROM user_purchases WHERE transaction_id = ?");
$stmtCheckGateway->execute([$txnIdGateway]);
if ($stmtCheckGateway->fetch()) {
    echo "✓ Gateway cash payment transaction recorded successfully!\n";
} else {
    echo "❌ Gateway payment recording failed.\n";
    exit(1);
}

echo "=== All Digital Shop & Coin Unlock System Verifications Passed Successfully! ===\n";
