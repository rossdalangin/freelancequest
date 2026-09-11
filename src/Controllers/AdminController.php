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
        $proPrice = DataManagementService::getSetting('pro_price', '19.00');
        $masterPrice = DataManagementService::getSetting('master_price', '49.00');
        $showHomepageTestimonials = DataManagementService::getSetting('show_homepage_testimonials', '1');

        // Testimonials
        $stmtTst = $pdo->query("SELECT t.*, u.name as user_name, u.username as user_username FROM testimonials t JOIN users u ON t.user_id = u.id ORDER BY t.id DESC");
        $testimonialsList = $stmtTst->fetchAll() ?: [];

        // Payment Account Settings
        $paypalEmail = DataManagementService::getSetting('paypal_email', 'admin@freelancequest.com');
        $stripeKey = DataManagementService::getSetting('stripe_key', 'pk_live_freelancequest_admin_key');
        $gcashNumber = DataManagementService::getSetting('gcash_number', '09171234567');
        $gcashName = DataManagementService::getSetting('gcash_name', 'FreelanceQuest Admin');

        // Coupons
        $coupons = $pdo->query("SELECT * FROM coupons ORDER BY id DESC")->fetchAll() ?: [];

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

    public function editLesson(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? '';
        $courseId = $_POST['course_id'] ?? 1;
        $levelNum = $_POST['level_number'] ?? 1;
        $summary = $_POST['summary'] ?? '';
        $content = $_POST['content'] ?? '';
        $xpReward = $_POST['xp_reward'] ?? 50;
        $coinReward = $_POST['coin_reward'] ?? 10;

        $stmt = $pdo->prepare("UPDATE lessons SET course_id = ?, level_number = ?, title = ?, summary = ?, content = ?, xp_reward = ?, coin_reward = ? WHERE id = ?");
        $stmt->execute([$courseId, $levelNum, $title, $summary, $content, $xpReward, $coinReward, $id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_LESSON_EDIT', "Updated lesson #{$id}: {$title}");

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

    public function editQuiz(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $lessonId = $_POST['lesson_id'] ?? 1;
        $title = $_POST['title'] ?? '';
        $xpReward = $_POST['xp_reward'] ?? 100;
        $coinReward = $_POST['coin_reward'] ?? 25;

        $stmt = $pdo->prepare("UPDATE quizzes SET lesson_id = ?, title = ?, xp_reward = ?, coin_reward = ? WHERE id = ?");
        $stmt->execute([$lessonId, $title, $xpReward, $coinReward, $id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_QUIZ_EDIT', "Updated quiz #{$id}: {$title}");

        header('Location: /admin/quizzes');
        exit;
    }

    public function editQuestion(string $id)
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
            $stmt = $pdo->prepare("UPDATE questions SET question_text = ?, options = ?, correct_option = ?, explanation = ? WHERE id = ?");
            $stmt->execute([$questionText, json_encode(array_values($options)), $correctOption, $explanation, $id]);

            DataManagementService::logActivity($admin['id'], 'ADMIN_QUESTION_EDIT', "Updated question #{$id}");
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

    public function manageCourses()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT * FROM courses ORDER BY level_number ASC, id DESC");
        $courses = $stmt->fetchAll();

        require __DIR__ . '/../../views/admin/courses.php';
    }

    public function createCourse()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? '';
        $slug = strtolower(str_replace(' ', '-', $title)) . '-' . rand(100, 999);
        $description = $_POST['description'] ?? '';
        $levelNum = $_POST['level_number'] ?? 1;
        $category = $_POST['category'] ?? 'Foundations';

        $stmt = $pdo->prepare("INSERT INTO courses (title, slug, description, level_number, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $description, $levelNum, $category]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_COURSE_CREATE', "Created course: {$title}");

        header('Location: /admin/courses');
        exit;
    }

    public function editCourse(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $levelNum = $_POST['level_number'] ?? 1;
        $category = $_POST['category'] ?? 'Foundations';

        $stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, level_number = ?, category = ? WHERE id = ?");
        $stmt->execute([$title, $description, $levelNum, $category, $id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_COURSE_EDIT', "Updated course #{$id}: {$title}");

        header('Location: /admin/courses');
        exit;
    }

    public function deleteCourse(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_COURSE_DELETE', "Deleted course #{$id}");

        header('Location: /admin/courses');
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

    public function editMission(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? '';
        $levelNum = $_POST['level_number'] ?? 1;
        $type = $_POST['type'] ?? 'interactive';
        $scenario = $_POST['scenario'] ?? '';
        $instructions = $_POST['instructions'] ?? '';
        $xpReward = $_POST['xp_reward'] ?? 250;
        $coinReward = $_POST['coin_reward'] ?? 50;

        $stmt = $pdo->prepare("UPDATE missions SET level_number = ?, title = ?, type = ?, scenario = ?, instructions = ?, xp_reward = ?, coin_reward = ? WHERE id = ?");
        $stmt->execute([$levelNum, $title, $type, $scenario, $instructions, $xpReward, $coinReward, $id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_MISSION_EDIT', "Updated mission #{$id}: {$title}");

        header('Location: /admin/missions');
        exit;
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

    public function manageResources()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT * FROM resources ORDER BY level_number ASC, id DESC");
        $resources = $stmt->fetchAll();

        require __DIR__ . '/../../views/admin/resources.php';
    }

    public function createResource()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        $levelNum = $_POST['level_number'] ?? 1;
        $type = $_POST['type'] ?? 'pdf';
        $fileUrl = $_POST['file_content_or_url'] ?? '';
        $isPremium = isset($_POST['is_premium']) ? 1 : 0;

        $stmt = $pdo->prepare("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$levelNum, $title, $desc, $type, $fileUrl, $isPremium]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_RESOURCE_CREATE', "Created resource: {$title}");

        header('Location: /admin/resources');
        exit;
    }

    public function editResource(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? '';
        $desc = $_POST['description'] ?? '';
        $levelNum = $_POST['level_number'] ?? 1;
        $type = $_POST['type'] ?? 'pdf';
        $fileUrl = $_POST['file_content_or_url'] ?? '';
        $isPremium = isset($_POST['is_premium']) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE resources SET level_number = ?, title = ?, description = ?, type = ?, file_content_or_url = ?, is_premium = ? WHERE id = ?");
        $stmt->execute([$levelNum, $title, $desc, $type, $fileUrl, $isPremium, $id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_RESOURCE_EDIT', "Updated resource #{$id}: {$title}");

        header('Location: /admin/resources');
        exit;
    }

    public function deleteResource(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM resources WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_RESOURCE_DELETE', "Deleted resource #{$id}");

        header('Location: /admin/resources');
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

    public function managePages()
    {
        $admin = $this->checkAdminAuth();

        $pagesList = [
            'home_hero' => [
                'title' => 'Homepage Hero & Tagline Section',
                'url' => '/',
                'content' => DataManagementService::getSetting('page_content_home_hero', '')
            ],
            'about' => [
                'title' => 'About Us Page & Founder Story',
                'url' => '/about',
                'content' => DataManagementService::getSetting('page_content_about', '')
            ],
            'features' => [
                'title' => 'Platform Features & Gameplay Section',
                'url' => '/features',
                'content' => DataManagementService::getSetting('page_content_features', '')
            ],
            'pricing_hero' => [
                'title' => 'Pricing & Membership Plans Section',
                'url' => '/pricing',
                'content' => DataManagementService::getSetting('page_content_pricing_hero', '')
            ],
            'faq' => [
                'title' => 'Frequently Asked Questions (FAQ)',
                'url' => '/faq',
                'content' => DataManagementService::getSetting('page_content_faq', '')
            ],
            'terms' => [
                'title' => 'Terms of Service Page',
                'url' => '/terms',
                'content' => DataManagementService::getSetting('page_content_terms', '')
            ],
            'privacy' => [
                'title' => 'Privacy Policy Page',
                'url' => '/privacy',
                'content' => DataManagementService::getSetting('page_content_privacy', '')
            ],
            'disclaimer' => [
                'title' => 'Earnings Disclaimer Page',
                'url' => '/disclaimer',
                'content' => DataManagementService::getSetting('page_content_disclaimer', '')
            ],
            'contact' => [
                'title' => 'Contact & Support Section',
                'url' => '/contact',
                'content' => DataManagementService::getSetting('page_content_contact', '')
            ],
        ];

        require __DIR__ . '/../../views/admin/pages.php';
    }

    public function updatePageContent()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pageSlug = trim($_POST['page_slug'] ?? '');
        $pageContent = trim($_POST['page_content'] ?? '');

        $validSlugs = ['home_hero', 'about', 'features', 'pricing_hero', 'faq', 'terms', 'privacy', 'disclaimer', 'contact'];

        if (in_array($pageSlug, $validSlugs)) {
            DataManagementService::setSetting('page_content_' . $pageSlug, $pageContent);
            DataManagementService::logActivity($admin['id'], 'ADMIN_PAGE_UPDATE', "Updated public page content for: {$pageSlug}");
            $_SESSION['flash_success'] = "Updated page content for " . strtoupper($pageSlug) . " successfully!";
        }

        header('Location: /admin/pages');
        exit;
    }

    public function manageCertificates()
    {
        $admin = $this->checkAdminAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT c.*, u.name as user_name, u.email as user_email FROM certificates c JOIN users u ON c.user_id = u.id ORDER BY c.id DESC");
        $certificates = $stmt->fetchAll();

        require __DIR__ . '/../../views/admin/certificates.php';
    }

    public function revokeCertificate(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM certificates WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_CERTIFICATE_REVOKE', "Revoked certificate #{$id}");

        header('Location: /admin/certificates');
        exit;
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
        $proPrice = $_POST['pro_price'] ?? '19.00';
        $masterPrice = $_POST['master_price'] ?? '49.00';
        $showTestimonials = isset($_POST['show_homepage_testimonials']) ? '1' : '0';

        $paypalEmail = $_POST['paypal_email'] ?? '';
        $stripeKey = $_POST['stripe_key'] ?? '';
        $gcashNumber = $_POST['gcash_number'] ?? '';
        $gcashName = $_POST['gcash_name'] ?? '';

        DataManagementService::setSetting('xp_multiplier', $xpMult);
        DataManagementService::setSetting('streak_bonus_xp', $streakBonus);
        DataManagementService::setSetting('pro_price', $proPrice);
        DataManagementService::setSetting('master_price', $masterPrice);
        DataManagementService::setSetting('show_homepage_testimonials', $showTestimonials);
        DataManagementService::setSetting('paypal_email', $paypalEmail);
        DataManagementService::setSetting('stripe_key', $stripeKey);
        DataManagementService::setSetting('gcash_number', $gcashNumber);
        DataManagementService::setSetting('gcash_name', $gcashName);

        DataManagementService::logActivity($admin['id'], 'SYSTEM_SETTINGS_UPDATE', "Updated Settings. Pro: \${$proPrice}, Master: \${$masterPrice}");

        header('Location: /admin');
        exit;
    }

    public function toggleTestimonialApproval(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmtCheck = $pdo->prepare("SELECT is_approved FROM testimonials WHERE id = ?");
        $stmtCheck->execute([$id]);
        $tst = $stmtCheck->fetch();

        if ($tst) {
            $newStatus = $tst['is_approved'] ? 0 : 1;
            $stmtUp = $pdo->prepare("UPDATE testimonials SET is_approved = ? WHERE id = ?");
            $stmtUp->execute([$newStatus, $id]);

            DataManagementService::logActivity($admin['id'], 'ADMIN_TESTIMONIAL_TOGGLE', "Updated testimonial #{$id} approval status to {$newStatus}");
        }

        header('Location: /admin');
        exit;
    }

    public function deleteTestimonial(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_TESTIMONIAL_DELETE', "Deleted testimonial #{$id}");

        header('Location: /admin');
        exit;
    }

    public function createCoupon()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $code = strtoupper(trim($_POST['code'] ?? ''));
        $percent = (int)($_POST['discount_percent'] ?? 0);
        $amount = (float)($_POST['discount_amount'] ?? 0);

        if (!empty($code)) {
            $stmt = $pdo->prepare("INSERT INTO coupons (code, discount_percent, discount_amount, is_active) VALUES (?, ?, ?, 1)");
            $stmt->execute([$code, $percent, $amount]);

            DataManagementService::logActivity($admin['id'], 'ADMIN_COUPON_CREATE', "Created coupon: {$code} ({$percent}% / \${$amount})");
        }

        header('Location: /admin');
        exit;
    }

    public function deleteCoupon(string $id)
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM coupons WHERE id = ?");
        $stmt->execute([$id]);

        DataManagementService::logActivity($admin['id'], 'ADMIN_COUPON_DELETE', "Deleted coupon #{$id}");

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

        $topic = trim($_POST['topic'] ?? 'Lead Generation');
        if (empty($topic)) {
            $topic = 'Lead Generation';
        }

        $cleanTopic = htmlspecialchars($topic, ENT_QUOTES, 'UTF-8');
        $slugBase = strtolower(preg_replace('/[^a-z0-9]+/', '-', $topic));
        $uniqueSuffix = rand(100, 999);
        $courseSlug = 'ai-module-' . $slugBase . '-' . $uniqueSuffix;

        // 1. Create Course Module
        $stmtC = $pdo->prepare("INSERT INTO courses (title, slug, description, level_number, category) VALUES (?, ?, ?, 4, 'AI Masterclass')");
        $stmtC->execute([
            'AI Masterclass: ' . $topic,
            $courseSlug,
            'Comprehensive AI-generated masterclass curriculum covering tools, SOPs, and client execution for ' . $cleanTopic
        ]);
        $courseId = $pdo->lastInsertId();

        // 2. Build 3 Comprehensive Lessons with structured HTML content
        $lessonsData = [
            [
                'title' => 'Foundations & Tooling for ' . $topic,
                'slug' => 'foundations-' . $slugBase . '-' . $uniqueSuffix,
                'summary' => 'Essential tools, concepts, and industry requirements for ' . $cleanTopic,
                'content' => "<h2>Executive Masterclass: Foundations & Tooling for " . $cleanTopic . "</h2>
                    <p>Welcome to the AI-generated masterclass for <strong>" . $cleanTopic . "</strong>. Mastering this core capability allows Virtual Assistants and freelancers to deliver high-impact results for global clients.</p>
                    <h3>1. Why This Topic is Essential for Remote VAs</h3>
                    <p>Clients actively hire specialists who possess structured workflows in " . $cleanTopic . " to increase efficiency, automate manual processes, and drive business growth.</p>
                    <h3>2. Standard Operating Procedure (SOP) Blueprint</h3>
                    <ul>
                        <li><strong>Step 1:</strong> Audit current client requirements and establish key metrics.</li>
                        <li><strong>Step 2:</strong> Configure industry-standard software tools and permission settings.</li>
                        <li><strong>Step 3:</strong> Execute daily task workflows with automated quality checks.</li>
                    </ul>
                    <h3>3. Copy-Paste Client Script</h3>
                    <p><em>'Hi [Client Name], I have set up our " . $cleanTopic . " operational workflow according to standard SOPs. I will provide daily updates on our execution status.'</em></p>",
                'quiz_question' => "What is the primary operational benefit of mastering " . $cleanTopic . " for remote freelancers?",
                'quiz_options' => ["Systematizing client workflows and delivering high-value business outcomes", "Working without internet access", "Eliminating the need for client communication", "Decreasing hourly rate potential"],
                'correct_option' => "Systematizing client workflows and delivering high-value business outcomes",
                'explanation' => "Structured SOPs in " . $cleanTopic . " increase service efficiency and client retention."
            ],
            [
                'title' => 'Advanced SOP Execution & Best Practices for ' . $topic,
                'slug' => 'advanced-sop-' . $slugBase . '-' . $uniqueSuffix,
                'summary' => 'Step-by-step SOP execution, error prevention, and quality assurance.',
                'content' => "<h2>Executive Masterclass: Advanced SOP Execution for " . $cleanTopic . "</h2>
                    <p>Deep-dive into advanced operational techniques and quality assurance for <strong>" . $cleanTopic . "</strong>.</p>
                    <h3>1. Advanced Execution Framework</h3>
                    <p>Executing " . $cleanTopic . " requires strict adherence to quality benchmarks. Avoid common pitfalls by testing output deliverability prior to final presentation.</p>
                    <h3>2. Error Handling & Quality Control Checklist</h3>
                    <ul>
                        <li>✓ Verify all input data entries for 100% accuracy.</li>
                        <li>✓ Double-check time zone formats and delivery schedules.</li>
                        <li>✓ Store deliverables in structured cloud storage folders.</li>
                    </ul>",
                'quiz_question' => "Which step ensures quality control prior to delivering " . $cleanTopic . " tasks to clients?",
                'quiz_options' => ["Executing automated tests and double-checking accuracy against SOP benchmarks", "Publishing raw unedited files immediately", "Ignoring client feedback", "Deleting project backup files"],
                'correct_option' => "Executing automated tests and double-checking accuracy against SOP benchmarks",
                'explanation' => "Quality control benchmarks prevent operational mistakes and maintain client trust."
            ],
            [
                'title' => 'Client Pitching & Monetization for ' . $topic,
                'slug' => 'pitching-monetization-' . $slugBase . '-' . $uniqueSuffix,
                'summary' => 'How to offer, price, and package ' . $cleanTopic . ' as a high-value retainer service.',
                'content' => "<h2>Executive Masterclass: Monetizing " . $cleanTopic . " Services</h2>
                    <p>Learn how to position <strong>" . $cleanTopic . "</strong> as a high-ticket $1,000+/mo recurring retainer offer.</p>
                    <h3>1. Value-Based Pricing Strategy</h3>
                    <p>Do not sell " . $cleanTopic . " by cheap hourly rates. Package your offer into results-focused monthly retainer tiers (Basic, Growth, Enterprise).</p>
                    <h3>2. Proposal Pitch Formula</h3>
                    <p><em>'I help growing companies scale their " . $cleanTopic . " operations, saving 15+ executive hours weekly while guaranteeing 99% task accuracy.'</em></p>",
                'quiz_question' => "How should freelancers price their specialized " . $cleanTopic . " services?",
                'quiz_options' => ["As value-based monthly retainer packages aligned with business outcomes", "By competing on being the cheapest option on freelancing sites", "By offering unlimited free labor", "By charging unpredictable random rates"],
                'correct_option' => "As value-based monthly retainer packages aligned with business outcomes",
                'explanation' => "Value-based retainers maximize freelancer earnings and provide cash flow predictability."
            ]
        ];

        $stmtL = $pdo->prepare("INSERT INTO lessons (course_id, level_number, title, slug, summary, content, xp_reward, coin_reward, sort_order) VALUES (?, 4, ?, ?, ?, ?, 150, 30, ?)");
        $stmtQz = $pdo->prepare("INSERT INTO quizzes (lesson_id, title, passing_score, xp_reward) VALUES (?, ?, 70, 100)");
        $stmtQn = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, options, correct_option, explanation) VALUES (?, ?, ?, ?, ?)");

        $sort = 1;
        foreach ($lessonsData as $les) {
            $stmtL->execute([$courseId, $les['title'], $les['slug'], $les['summary'], $les['content'], $sort]);
            $lessonId = $pdo->lastInsertId();

            // Create Quiz & Question
            $stmtQz->execute([$lessonId, 'Knowledge Check: ' . $les['title']]);
            $quizId = $pdo->lastInsertId();

            $stmtQn->execute([
                $quizId,
                $les['quiz_question'],
                json_encode($les['quiz_options']),
                $les['correct_option'],
                $les['explanation']
            ]);

            $sort++;
        }

        // 3. Create Downloadable Resource File
        $stmtR = $pdo->prepare("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (4, ?, ?, 'template', ?, 1)");
        $stmtR->execute([
            $cleanTopic . ' SOP & Execution Template',
            'Official AI-generated action template and client script for ' . $cleanTopic,
            '/downloads/level-04-social-media-content-calendar.md'
        ]);

        DataManagementService::logActivity($admin['id'], 'AI_COURSE_GENERATED', "Generated 3 lessons, quizzes, and resources for topic: {$topic}");

        $_SESSION['flash_success'] = "AI Course Module for '{$topic}' generated successfully with 3 lessons, quizzes, and resources!";
        header('Location: /admin/courses');
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

    public function reseedDatabase()
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        require_once __DIR__ . '/../../database/seed.php';
        seedDatabase();

        DataManagementService::logActivity($admin['id'], 'ADMIN_DATABASE_RESEED', "Triggered full database reseed for all course levels, lessons, quizzes, and resources.");

        header('Location: /admin');
        exit;
    }

    public function exportDatabase(): void
    {
        $admin = $this->checkAdminAuth();
        $dbPath = __DIR__ . '/../../database/database.sqlite';

        if (!file_exists($dbPath)) {
            $_SESSION['flash_error'] = "Database file not found.";
            header('Location: /admin');
            exit;
        }

        $filename = 'freelancequest-backup-' . date('Y-m-d-His') . '.sqlite';

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.sqlite3');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($dbPath));
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        readfile($dbPath);
        exit;
    }

    public function importDatabase(): void
    {
        $admin = $this->checkAdminAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = "File upload failed or no backup file provided.";
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
            exit;
        }

        $fileTmpPath = $_FILES['backup_file']['tmp_name'];
        $fileName = $_FILES['backup_file']['name'];

        // Validate file extension or content
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($ext !== 'sqlite' && $ext !== 'db') {
            $_SESSION['flash_error'] = "Invalid file type. Please upload a valid .sqlite or .db backup file.";
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
            exit;
        }

        // Validate SQLite header
        $header = file_get_contents($fileTmpPath, false, null, 0, 16);
        if ($header !== "SQLite format 3 ") {
            $_SESSION['flash_error'] = "Uploaded file is not a valid SQLite format 3 database.";
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
            exit;
        }

        $targetDbPath = __DIR__ . '/../../database/database.sqlite';

        try {
            copy($fileTmpPath, $targetDbPath);
            DataManagementService::logActivity($admin['id'], 'ADMIN_DATABASE_IMPORT', "Imported database backup: {$fileName}");
            $_SESSION['flash_success'] = "Database backup imported successfully! All records have been updated.";
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = "Failed to import database backup: " . $e->getMessage();
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
        exit;
    }
}
