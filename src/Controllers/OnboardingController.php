<?php

namespace App\Controllers;

use Database;

class OnboardingController
{
    public function index()
    {
        require __DIR__ . '/../../views/onboarding/index.php';
    }

    public function store()
    {
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
