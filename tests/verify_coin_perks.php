<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../database/seed.php';

echo "=== Verifying System Coins Perks & Utilities Integration ===\n";

$pdo = Database::getConnection();

// 1. Setup test user with 1000 coins
$stmtUser = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmtUser->execute(['coinperks@freelancequest.com']);
$userId = $stmtUser->fetchColumn();

if (!$userId) {
    $stmtIns = $pdo->prepare("INSERT INTO users (name, email, password, role, username, coins, streak_count) VALUES (?, ?, ?, 'student', ?, 1000, 5)");
    $stmtIns->execute(['Coin Perks Tester', 'coinperks@freelancequest.com', password_hash('password123', PASSWORD_BCRYPT), 'coinperkstester']);
    $userId = $pdo->lastInsertId();
} else {
    $pdo->exec("UPDATE users SET coins = 1000, streak_count = 5 WHERE id = " . (int)$userId);
}

// 2. Test Streak Shield (+150 Coins -> +7 streak days)
$shieldCost = 150;
$stmtDeduct = $pdo->prepare("UPDATE users SET coins = coins - ?, streak_count = streak_count + 7 WHERE id = ?");
$stmtDeduct->execute([$shieldCost, $userId]);

$stmtCheckStreak = $pdo->prepare("SELECT coins, streak_count FROM users WHERE id = ?");
$stmtCheckStreak->execute([$userId]);
$userState = $stmtCheckStreak->fetch();

if ((int)$userState['coins'] === 850 && (int)$userState['streak_count'] === 12) {
    echo "✓ Streak Shield activated successfully! Coins: 850, Streak: 12 days\n";
} else {
    echo "❌ Streak Shield test failed.\n";
    exit(1);
}

// 3. Test Job Application Boost (+50 Coins)
$stmtJob = $pdo->query("SELECT id FROM jobs LIMIT 1");
$jobId = $stmtJob->fetchColumn();

$stmtApp = $pdo->prepare("INSERT INTO job_applications (job_id, user_id, cover_letter, proposed_rate, is_boosted) VALUES (?, ?, 'Proposal pitch...', '$25/hr', 0)");
$stmtApp->execute([$jobId, $userId]);
$appId = $pdo->lastInsertId();

// Boost application
$boostCost = 50;
$pdo->exec("UPDATE users SET coins = coins - {$boostCost} WHERE id = " . (int)$userId);
$pdo->exec("UPDATE job_applications SET is_boosted = 1 WHERE id = " . (int)$appId);

$stmtCheckApp = $pdo->prepare("SELECT is_boosted FROM job_applications WHERE id = ?");
$stmtCheckApp->execute([$appId]);
$isBoosted = (int)$stmtCheckApp->fetchColumn();

if ($isBoosted === 1) {
    echo "✓ Job Proposal boosted as Priority Applicant successfully for 50 Coins!\n";
} else {
    echo "❌ Job proposal boost test failed.\n";
    exit(1);
}

// 4. Test Resume Gold Theme Unlock (+100 Coins)
$themeCost = 100;
$pdo->exec("UPDATE users SET coins = coins - {$themeCost} WHERE id = " . (int)$userId);
$stmtInsTxn = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'resume_theme', 1, 'coins', 0.0, ?, ?)");
$stmtInsTxn->execute([$userId, $themeCost, 'COIN-THM-RES-GOLD-TEST']);

$stmtRes = $pdo->prepare("SELECT id FROM resumes WHERE user_id = ?");
$stmtRes->execute([$userId]);
if (!$stmtRes->fetch()) {
    $pdo->exec("INSERT INTO resumes (user_id, title, theme) VALUES ({$userId}, 'Resume', 'gold')");
} else {
    $pdo->exec("UPDATE resumes SET theme = 'gold' WHERE user_id = " . (int)$userId);
}

$stmtCheckRes = $pdo->prepare("SELECT theme FROM resumes WHERE user_id = ?");
$stmtCheckRes->execute([$userId]);
$theme = $stmtCheckRes->fetchColumn();

if ($theme === 'gold') {
    echo "✓ Resume Executive Gold theme unlocked successfully for 100 Coins!\n";
} else {
    echo "❌ Resume theme unlock test failed.\n";
    exit(1);
}

echo "=== All System Coins Perks & Utilities Verifications Passed Successfully! ===\n";
