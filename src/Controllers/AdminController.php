<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\DataManagementService;

class AdminController
{
    private function checkAdminAuth(): array
    {
        $user = AuthController::requireAuth();
        if (($user['role'] ?? '') !== 'admin') {
            http_response_code(403);
            die("Access Denied: Administrator privileges required.");
        }
        return $user;
    }

    public function index()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $lessonsCount = $pdo->query("SELECT COUNT(*) FROM lessons")->fetchColumn();
        $missionsCount = $pdo->query("SELECT COUNT(*) FROM missions")->fetchColumn();
        $quizzesCount = $pdo->query("SELECT COUNT(*) FROM quizzes")->fetchColumn();

        $latestUsers = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 5")->fetchAll();

        // System Settings
        $xpMultiplier = DataManagementService::getSetting('xp_multiplier', '1.0');
        $streakBonus = DataManagementService::getSetting('streak_bonus_xp', '200');

        // Audit Logs
        $auditLogs = DataManagementService::getAuditLogs(10);

        require __DIR__ . '/../../views/admin/dashboard.php';
    }

    public function updateSettings()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $xpMult = $_POST['xp_multiplier'] ?? '1.0';
        $streakBonus = $_POST['streak_bonus_xp'] ?? '200';

        DataManagementService::setSetting('xp_multiplier', $xpMult);
        DataManagementService::setSetting('streak_bonus_xp', $streakBonus);
        DataManagementService::logActivity($admin['id'], 'SYSTEM_SETTINGS_UPDATE', "XP Multiplier: {$xpMult}, Streak Bonus: {$streakBonus}");

        header('Location: /admin');
        exit;
    }

    public function generateAiCourse()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $topic = $_POST['topic'] ?? 'Lead Generation';
        $slug = 'ai-gen-' . strtolower(str_replace(' ', '-', $topic)) . '-' . rand(100, 999);

        $stmtC = $pdo->prepare("INSERT INTO courses (title, slug, description, level_number, category) VALUES (?, ?, ?, 4, 'AI Specialization')");
        $stmtC->execute(['AI Generated: ' . $topic, $slug, 'Comprehensive module generated for ' . $topic]);
        $courseId = $pdo->lastInsertId();

        $stmtL = $pdo->prepare("INSERT INTO lessons (course_id, level_number, title, slug, summary, content, xp_reward, coin_reward) VALUES (?, 4, ?, ?, ?, ?, 100, 20)");
        $stmtL->execute([$courseId, 'Intro to ' . $topic, 'intro-' . $slug, 'Foundations of ' . $topic, "## " . $topic . "\n\nPractical guide."]);

        DataManagementService::logActivity($admin['id'], 'AI_COURSE_GENERATED', "Topic: {$topic}");

        header('Location: /admin');
        exit;
    }

    public function exportData()
    {
        $admin = $this->checkAdminAuth();

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="freelancequest-backup-' . date('Y-m-d') . '.json"');
        echo DataManagementService::exportBackupJson();
        exit;
    }
}
