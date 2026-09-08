<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;

class AdminController
{
    public function index()
    {
        $pdo = Database::getConnection();

        $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $lessonsCount = $pdo->query("SELECT COUNT(*) FROM lessons")->fetchColumn();
        $missionsCount = $pdo->query("SELECT COUNT(*) FROM missions")->fetchColumn();
        $quizzesCount = $pdo->query("SELECT COUNT(*) FROM quizzes")->fetchColumn();

        $latestUsers = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 5")->fetchAll();

        require __DIR__ . '/../../views/admin/dashboard.php';
    }

    public function generateAiCourse()
    {
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

        header('Location: /admin');
        exit;
    }
}
