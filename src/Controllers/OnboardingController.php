<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;

class OnboardingController
{
    public function index()
    {
        require __DIR__ . '/../../views/onboarding/index.php';
    }

    public function store()
    {
        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        $answers = [
            'experience_level' => $_POST['experience_level'] ?? '',
            'target_career' => $_POST['target_career'] ?? '',
            'weekly_hours' => $_POST['weekly_hours'] ?? '',
            'income_goal' => $_POST['income_goal'] ?? '',
        ];

        $stmtUp = $pdo->prepare("UPDATE users SET onboarding_answers = ? WHERE id = ?");
        $stmtUp->execute([json_encode($answers), $user['id']]);

        header('Location: /dashboard');
        exit;
    }
}
