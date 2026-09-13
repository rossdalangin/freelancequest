<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\GameEngineService;
use App\Services\DataManagementService;

class JobController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;

        if ($category && $search) {
            $stmt = $pdo->prepare("SELECT j.*, (SELECT COUNT(*) FROM job_applications ja WHERE ja.job_id = j.id) as applicants_count FROM jobs j WHERE j.status = 'open' AND j.category = ? AND (j.title LIKE ? OR j.company LIKE ? OR j.description LIKE ?) ORDER BY j.id DESC");
            $stmt->execute([$category, "%{$search}%", "%{$search}%", "%{$search}%"]);
        } elseif ($category) {
            $stmt = $pdo->prepare("SELECT j.*, (SELECT COUNT(*) FROM job_applications ja WHERE ja.job_id = j.id) as applicants_count FROM jobs j WHERE j.status = 'open' AND j.category = ? ORDER BY j.id DESC");
            $stmt->execute([$category]);
        } elseif ($search) {
            $stmt = $pdo->prepare("SELECT j.*, (SELECT COUNT(*) FROM job_applications ja WHERE ja.job_id = j.id) as applicants_count FROM jobs j WHERE j.status = 'open' AND (j.title LIKE ? OR j.company LIKE ? OR j.description LIKE ?) ORDER BY j.id DESC");
            $stmt->execute(["%{$search}%", "%{$search}%", "%{$search}%"]);
        } else {
            $stmt = $pdo->query("SELECT j.*, (SELECT COUNT(*) FROM job_applications ja WHERE ja.job_id = j.id) as applicants_count FROM jobs j WHERE j.status = 'open' ORDER BY j.id DESC");
        }
        $jobs = $stmt->fetchAll();

        require __DIR__ . '/../../views/jobs/index.php';
    }

    public function show(string $id)
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT j.*, (SELECT COUNT(*) FROM job_applications ja WHERE ja.job_id = j.id) as applicants_count FROM jobs j WHERE j.id = ?");
        $stmt->execute([$id]);
        $job = $stmt->fetch();

        if (!$job) {
            http_response_code(404);
            echo "Job posting not found";
            return;
        }

        // Fetch all job applications for job poster / employer view ordered by priority boost
        $stmtAllApps = $pdo->prepare("SELECT ja.*, u.name as applicant_name, u.email as applicant_email, u.level as applicant_level
            FROM job_applications ja
            JOIN users u ON ja.user_id = u.id
            WHERE ja.job_id = ?
            ORDER BY ja.is_boosted DESC, ja.id DESC");
        $stmtAllApps->execute([$id]);
        $allApplications = $stmtAllApps->fetchAll();

        // Check if user already applied
        $stmtApp = $pdo->prepare("SELECT * FROM job_applications WHERE job_id = ? AND user_id = ?");
        $stmtApp->execute([$id, $user['id']]);
        $existingApplication = $stmtApp->fetch();

        $success = $_SESSION['job_app_success'] ?? null;
        $error = $_SESSION['job_app_error'] ?? null;
        unset($_SESSION['job_app_success'], $_SESSION['job_app_error']);

        require __DIR__ . '/../../views/jobs/show.php';
    }

    public function apply(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('job_application', 10, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->execute([$id]);
        $job = $stmt->fetch();

        if (!$job) {
            http_response_code(404);
            die("Job posting not found.");
        }

        $coverLetter = trim($_POST['cover_letter'] ?? '');
        $proposedRate = trim($_POST['proposed_rate'] ?? '');
        $portfolioUrl = trim($_POST['portfolio_url'] ?? ('/p/' . ($user['username'] ?? $user['id'])));

        if (empty($coverLetter) || empty($proposedRate)) {
            $_SESSION['job_app_error'] = 'Please complete all application fields.';
            header('Location: /jobs/' . $id);
            exit;
        }

        // Check if already applied
        $stmtApp = $pdo->prepare("SELECT id FROM job_applications WHERE job_id = ? AND user_id = ?");
        $stmtApp->execute([$id, $user['id']]);
        if ($stmtApp->fetch()) {
            $_SESSION['job_app_error'] = 'You have already submitted an application for this position.';
            header('Location: /jobs/' . $id);
            exit;
        }

        $stmtIns = $pdo->prepare("INSERT INTO job_applications (job_id, user_id, cover_letter, proposed_rate, portfolio_url) VALUES (?, ?, ?, ?, ?)");
        $stmtIns->execute([$id, $user['id'], $coverLetter, $proposedRate, $portfolioUrl]);

        // Award in-game XP for submitting a job application
        $gameEngine = new GameEngineService();
        $gameEngine->awardXPAndCoins($user['id'], 150, 30);

        DataManagementService::logActivity($user['id'], 'JOB_APPLICATION_SUBMITTED', "Applied for job #{$id}: {$job['title']}");

        $_SESSION['job_app_success'] = 'Your application has been submitted successfully! You earned +150 XP and +30 Coins!';
        header('Location: /jobs/' . $id);
        exit;
    }

    public function showCreateForm()
    {
        $user = AuthController::requireAuth();
        require __DIR__ . '/../../views/jobs/create.php';
    }

    public function store()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $title = trim($_POST['title'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $category = $_POST['category'] ?? 'General VA';
        $budget = trim($_POST['budget'] ?? '$15/hr');
        $jobType = $_POST['job_type'] ?? 'Part-Time';
        $description = trim($_POST['description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');

        if (empty($title) || empty($company) || empty($description)) {
            $_SESSION['job_create_error'] = 'Please fill in all required job details.';
            header('Location: /jobs/create');
            exit;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO jobs (user_id, title, company, category, budget, job_type, description, requirements) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user['id'], $title, $company, $category, $budget, $jobType, $description, $requirements]);

        $newJobId = $pdo->lastInsertId();
        DataManagementService::logActivity($user['id'], 'JOB_POSTED', "Posted new job: {$title}");

        header('Location: /jobs/' . $newJobId);
        exit;
    }

    public function boostApplication(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $boostCost = 50;
        if (($user['coins'] ?? 0) < $boostCost) {
            $_SESSION['job_app_error'] = "Insufficient coins! Boosting your proposal requires {$boostCost} coins. You currently have {$user['coins']} coins.";
            header('Location: /my-applications');
            exit;
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM job_applications WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user['id']]);
        $app = $stmt->fetch();

        if (!$app) {
            http_response_code(404);
            die("Application not found.");
        }

        if (!empty($app['is_boosted'])) {
            $_SESSION['job_app_success'] = "This proposal is already boosted as a Priority Applicant!";
            header('Location: /my-applications');
            exit;
        }

        // Deduct coins & boost application
        $stmtDeduct = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
        $stmtDeduct->execute([$boostCost, $user['id']]);

        $stmtBoost = $pdo->prepare("UPDATE job_applications SET is_boosted = 1 WHERE id = ?");
        $stmtBoost->execute([$id]);

        DataManagementService::logActivity($user['id'], 'JOB_PROPOSAL_BOOSTED', "Boosted job application #{$id} for 50 coins");

        $_SESSION['job_app_success'] = "⚡ Proposal boosted! Your application now displays with the Priority Applicant badge at the top of the client inbox.";
        header('Location: /my-applications');
        exit;
    }

    public function myApplications()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT ja.*, j.title as job_title, j.company as job_company, j.budget as job_budget FROM job_applications ja JOIN jobs j ON ja.job_id = j.id WHERE ja.user_id = ? ORDER BY ja.id DESC");
        $stmt->execute([$user['id']]);
        $applications = $stmt->fetchAll();

        require __DIR__ . '/../../views/jobs/applications.php';
    }
}
