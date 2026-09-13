<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../database/seed.php';

echo "=== Verifying Support Ticket & Help Desk System ===\n";

$pdo = Database::getConnection();

// Create test user if not exists
$stmtUser = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmtUser->execute(['supporttest@freelancequest.com']);
$userId = $stmtUser->fetchColumn();

if (!$userId) {
    $stmtIns = $pdo->prepare("INSERT INTO users (name, email, password, role, username) VALUES (?, ?, ?, 'student', ?)");
    $stmtIns->execute(['Support Test Student', 'supporttest@freelancequest.com', password_hash('password123', PASSWORD_BCRYPT), 'supportstudent']);
    $userId = $pdo->lastInsertId();
}

// Create test admin if not exists
$stmtAdmin = $pdo->prepare("SELECT id FROM users WHERE role = 'admin'");
$stmtAdmin->execute();
$adminId = $stmtAdmin->fetchColumn();

if (!$adminId) {
    $stmtInsAdmin = $pdo->prepare("INSERT INTO users (name, email, password, role, username) VALUES (?, ?, ?, 'admin', ?)");
    $stmtInsAdmin->execute(['System Admin', 'admin@freelancequest.com', password_hash('admin123', PASSWORD_BCRYPT), 'admin']);
    $adminId = $pdo->lastInsertId();
}

// 1. Test Ticket Creation
$ticketNumber = 'TCK-TEST' . rand(100, 999);
$stmtTicket = $pdo->prepare("INSERT INTO support_tickets (ticket_number, user_id, subject, category, priority, status) VALUES (?, ?, ?, ?, ?, 'open')");
$stmtTicket->execute([$ticketNumber, $userId, 'Test Support Request', 'Technical & Platform Issue', 'High']);
$ticketId = $pdo->lastInsertId();

if ($ticketId) {
    echo "✓ Support ticket created successfully (ID: {$ticketId}, Ticket #: {$ticketNumber})\n";
} else {
    echo "❌ Failed to create support ticket.\n";
    exit(1);
}

// 2. Test User Initial Message & Admin Reply
$stmtReply1 = $pdo->prepare("INSERT INTO support_ticket_replies (ticket_id, user_id, message, is_admin_reply) VALUES (?, ?, ?, 0)");
$stmtReply1->execute([$ticketId, $userId, 'Initial issue description by student']);

$stmtReply2 = $pdo->prepare("INSERT INTO support_ticket_replies (ticket_id, user_id, message, is_admin_reply) VALUES (?, ?, ?, 1)");
$stmtReply2->execute([$ticketId, $adminId, 'Thank you for reaching out! We are investigating this issue.']);

$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM support_ticket_replies WHERE ticket_id = ?");
$stmtCount->execute([$ticketId]);
$replyCount = $stmtCount->fetchColumn();

if ($replyCount == 2) {
    echo "✓ Replies added to support thread successfully (Total: {$replyCount})\n";
} else {
    echo "❌ Failed to add replies to support ticket.\n";
    exit(1);
}

// 3. Test Status Update
$stmtUpd = $pdo->prepare("UPDATE support_tickets SET status = 'in_progress' WHERE id = ?");
$stmtUpd->execute([$ticketId]);

$stmtCheck = $pdo->prepare("SELECT status FROM support_tickets WHERE id = ?");
$stmtCheck->execute([$ticketId]);
$status = $stmtCheck->fetchColumn();

if ($status === 'in_progress') {
    echo "✓ Ticket status updated to 'in_progress' successfully.\n";
} else {
    echo "❌ Failed to update ticket status.\n";
    exit(1);
}

// 4. Test Ticket Resolution / Close
$stmtClose = $pdo->prepare("UPDATE support_tickets SET status = 'resolved' WHERE id = ?");
$stmtClose->execute([$ticketId]);

$stmtCheckClose = $pdo->prepare("SELECT status FROM support_tickets WHERE id = ?");
$stmtCheckClose->execute([$ticketId]);
$finalStatus = $stmtCheckClose->fetchColumn();

if ($finalStatus === 'resolved') {
    echo "✓ Ticket resolved successfully.\n";
} else {
    echo "❌ Failed to resolve ticket.\n";
    exit(1);
}

echo "=== All Support Ticket System Verifications Passed Successfully! ===\n";
