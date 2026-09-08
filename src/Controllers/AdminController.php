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
        $paymentsCount = $pdo->query("SELECT COUNT(*) FROM payments")->fetchColumn();

        $latestUsers = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 5")->fetchAll();

        // System Settings
        $xpMultiplier = DataManagementService::getSetting('xp_multiplier', '1.0');
        $streakBonus = DataManagementService::getSetting('streak_bonus_xp', '200');

        // Audit Logs
        $auditLogs = DataManagementService::getAuditLogs(10);

        require __DIR__ . '/../../views/admin/dashboard.php';
    }

    public function manageUsers()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll();

        require __DIR__ . '/../../views/admin/users.php';
    }

    public function manageLessons()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT l.*, c.title as course_title FROM lessons l LEFT JOIN courses c ON l.course_id = c.id ORDER BY l.id DESC");
        $lessons = $stmt->fetchAll();

        $courses = $pdo->query("SELECT * FROM courses")->fetchAll();

        require __DIR__ . '/../../views/admin/lessons.php';
    }

    public function createLesson()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? '';
        $slug = strtolower(str_replace(' ', '-', $title)) . '-' . rand(100, 999);
        $courseId = $_POST['course_id'] ?? 1;
        $levelNum = $_POST['level_number'] ?? 1;
        $summary = $_POST['summary'] ?? '';
        $content = $_POST['content'] ?? '';
        $xpReward = $_POST['xp_reward'] ?? 50;
        $coinReward = $_POST['coin_reward'] ?? 10;

        $stmt = $pdo->prepare("INSERT INTO lessons (course_id, level_number, title, slug, summary, content, xp_reward, coin_reward) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$courseId, $levelNum, $title, $slug, $summary, $content, $xpReward, $coinReward]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_LESSON_CREATE', "Created lesson: {$title}");

        header('Location: /admin/lessons');
        exit;
    }

    public function deleteLesson(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM lessons WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_LESSON_DELETE', "Deleted lesson #{$id}");

        header('Location: /admin/lessons');
        exit;
    }

    public function manageQuizzes()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT q.*, l.title as lesson_title FROM quizzes q LEFT JOIN lessons l ON q.lesson_id = l.id ORDER BY q.id DESC");
        $quizzes = $stmt->fetchAll();

        foreach ($quizzes as &$q) {
            $stmtQn = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
            $stmtQn->execute([$q['id']]);
            $q['questions'] = $stmtQn->fetchAll();
            foreach ($q['questions'] as &$qn) {
                $qn['options'] = json_decode($qn['options'], true) ?? [];
            }
        }

        $lessons = $pdo->query("SELECT id, title FROM lessons")->fetchAll();

        require __DIR__ . '/../../views/admin/quizzes.php';
    }

    public function deleteQuiz(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_QUIZ_DELETE', "Deleted quiz #{$id}");

        header('Location: /admin/quizzes');
        exit;
    }

    public function addQuizQuestion(string $quizId)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $questionText = $_POST['question_text'] ?? '';
        $options = array_filter(array_map('trim', explode("\n", $_POST['options'] ?? '')));
        $correctOption = $_POST['correct_option'] ?? ($options[0] ?? '');
        $explanation = $_POST['explanation'] ?? '';

        if ($questionText && count($options) >= 2) {
            $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, options, correct_option, explanation) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$quizId, $questionText, json_encode(array_values($options)), $correctOption, $explanation]);

            DataManagementService::logActivity($admin['id'], 'ADMIN_QUESTION_ADD', "Added question to quiz #{$quizId}");
        }

        header('Location: /admin/quizzes');
        exit;
    }

    public function deleteQuestion(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_QUESTION_DELETE', "Deleted question #{$id}");

        header('Location: /admin/quizzes');
        exit;
    }

    public function deleteMission(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM missions WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_MISSION_DELETE', "Deleted mission #{$id}");

        header('Location: /admin/missions');
        exit;
    }

    public function manageQuizzesOld()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT q.*, l.title as lesson_title FROM quizzes q LEFT JOIN lessons l ON q.lesson_id = l.id ORDER BY q.id DESC");
        $quizzes = $stmt->fetchAll();

        $lessons = $pdo->query("SELECT id, title FROM lessons")->fetchAll();

        require __DIR__ . '/../../views/admin/quizzes.php';
    }

    public function createQuiz()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $lessonId = $_POST['lesson_id'] ?? 1;
        $title = $_POST['title'] ?? 'Knowledge Check';
        $xpReward = $_POST['xp_reward'] ?? 100;
        $coinReward = $_POST['coin_reward'] ?? 25;

        $stmt = $pdo->prepare("INSERT INTO quizzes (lesson_id, title, xp_reward, coin_reward) VALUES (?, ?, ?, ?)");
        $stmt->execute([$lessonId, $title, $xpReward, $coinReward]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_QUIZ_CREATE', "Created quiz: {$title}");

        header('Location: /admin/quizzes');
        exit;
    }

    public function manageMissions()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT * FROM missions ORDER BY id DESC");
        $missions = $stmt->fetchAll();

        require __DIR__ . '/../../views/admin/missions.php';
    }

    public function createMission()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? 'New Mission';
        $levelNum = $_POST['level_number'] ?? 1;
        $type = $_POST['type'] ?? 'interactive';
        $scenario = $_POST['scenario'] ?? '';
        $instructions = $_POST['instructions'] ?? '';
        $xpReward = $_POST['xp_reward'] ?? 250;
        $coinReward = $_POST['coin_reward'] ?? 50;

        $stmt = $pdo->prepare("INSERT INTO missions (level_number, title, type, scenario, instructions, xp_reward, coin_reward) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$levelNum, $title, $type, $scenario, $instructions, $xpReward, $coinReward]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_MISSION_CREATE', "Created mission: {$title}");

        header('Location: /admin/missions');
        exit;
    }

    public function updateUserPlan(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $plan = $_POST['subscription_tier'] ?? 'free';
        $role = $_POST['role'] ?? 'student';

        $stmtUp = $pdo->prepare("UPDATE users SET subscription_tier = ?, role = ? WHERE id = ?");
        $stmtUp->execute([$plan, $role, $id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_USER_PLAN_UPDATE', "Updated User #{$id} Plan to {$plan}, Role: {$role}");

        header('Location: /admin/users');
        exit;
    }

    public function managePayments()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT p.*, u.name as user_name, u.email as user_email FROM payments p JOIN users u ON p.user_id = u.id ORDER BY p.id DESC");
        $payments = $stmt->fetchAll();

        require __DIR__ . '/../../views/admin/payments.php';
    }

    public function refundPayment(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $stmtPay = $pdo->prepare("SELECT * FROM payments WHERE id = ?");
        $stmtPay->execute([$id]);
        $payment = $stmtPay->fetch();

        if ($payment) {
            $stmtUp = $pdo->prepare("UPDATE payments SET status = 'refunded' WHERE id = ?");
            $stmtUp->execute([$id]);

            // Demote user to free tier
            $stmtUser = $pdo->prepare("UPDATE users SET subscription_tier = 'free' WHERE id = ?");
            $stmtUser->execute([$payment['user_id']]);

            DataManagementService::logActivity($admin['id'], 'ADMIN_PAYMENT_REFUND', "Refunded Payment #{$id} (\${$payment['amount']}) for User #{$payment['user_id']}");
        }

        header('Location: /admin/payments');
        exit;
    }

    public function viewLogs()
    {
        $admin = $this->checkAdminAuth();
        $auditLogs = DataManagementService::getAuditLogs(100);

        require __DIR__ . '/../../views/admin/logs.php';
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
