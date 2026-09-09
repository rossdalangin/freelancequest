<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\DataManagementService;

class ApplicationTrackerController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM applications_tracker WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$user['id']]);
        $apps = $stmt->fetchAll();

        // Calculate pipeline analytics
        $totalApps = count($apps);
        $interviews = 0;
        $offers = 0;
        $hired = 0;

        foreach ($apps as $a) {
            if ($a['status'] === 'Interview') $interviews++;
            if ($a['status'] === 'Offer') $offers++;
            if ($a['status'] === 'Hired') $hired++;
        }

        $responseRate = $totalApps > 0 ? round((($interviews + $offers + $hired) / $totalApps) * 100) : 0;

        require __DIR__ . '/../../views/applications/tracker.php';
    }

    public function add()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $company = trim($_POST['company'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $appliedDate = $_POST['applied_date'] ?? date('Y-m-d');
        $status = $_POST['status'] ?? 'Applied';
        $notes = trim($_POST['notes'] ?? '');

        if ($company && $position) {
            $stmt = $pdo->prepare("INSERT INTO applications_tracker (user_id, company, position, applied_date, status, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user['id'], $company, $position, $appliedDate, $status, $notes]);

            DataManagementService::logActivity($user['id'], 'APPLICATION_TRACKER_ADD', "Tracked application: {$position} at {$company}");
        }

        header('Location: /application-tracker');
        exit;
    }

    public function updateStatus(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $status = $_POST['status'] ?? 'Applied';

        $stmt = $pdo->prepare("UPDATE applications_tracker SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$status, $id, $user['id']]);

        header('Location: /application-tracker');
        exit;
    }

    public function delete(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM applications_tracker WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user['id']]);

        header('Location: /application-tracker');
        exit;
    }
}
