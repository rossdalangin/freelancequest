<?php

namespace App\Controllers;

use Database;
use App\Services\GameEngineService;
use App\Services\SecurityService;

class ResumeBuilderController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmtR = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ?");
        $stmtR->execute([$user['id']]);
        $resume = $stmtR->fetch();

        if (!$resume) {
            $stmtIns = $pdo->prepare("INSERT INTO resumes (user_id, title, full_name, professional_title, email, summary, skills, tools, experience, education) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtIns->execute([
                $user['id'],
                'Virtual Assistant Resume',
                $user['name'],
                $user['headline'] ?? 'Virtual Assistant Specialist',
                $user['email'],
                $user['bio'] ?? 'Detail-oriented and proactive Virtual Assistant.',
                json_encode(['Google Workspace', 'Email Management', 'Calendar Scheduling', 'Customer Support', 'Data Entry']),
                json_encode(['Asana', 'Trello', 'Slack', 'Canva', 'HubSpot']),
                json_encode([
                    [
                        'role' => 'Virtual Assistant Apprentice',
                        'company' => 'FreelanceQuest Simulator',
                        'period' => '2025 - Present',
                        'details' => 'Managed calendar scheduling, inbox organization, and web research projects with 99% accuracy.'
                    ]
                ]),
                json_encode([
                    [
                        'degree' => 'Bachelor of Science / General VA Certification',
                        'institution' => 'FreelanceQuest Academy',
                        'year' => '2025'
                    ]
                ])
            ]);

            $stmtR->execute([$user['id']]);
            $resume = $stmtR->fetch();
        }

        $resume['skills'] = json_decode($resume['skills'], true) ?? [];
        $resume['tools'] = json_decode($resume['tools'], true) ?? [];
        $resume['experience'] = json_decode($resume['experience'], true) ?? [];
        $resume['education'] = json_decode($resume['education'], true) ?? [];

        require __DIR__ . '/../../views/resume/builder.php';
    }

    public function update()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $fullName = $_POST['full_name'] ?? $user['name'];
        $title = $_POST['professional_title'] ?? 'Virtual Assistant';
        $email = $_POST['email'] ?? $user['email'];
        $phone = $_POST['phone'] ?? '';
        $location = $_POST['location'] ?? '';
        $summary = $_POST['summary'] ?? '';

        $skills = array_filter(array_map('trim', explode(',', $_POST['skills'] ?? '')));
        $tools = array_filter(array_map('trim', explode(',', $_POST['tools'] ?? '')));

        $stmtUp = $pdo->prepare("UPDATE resumes SET full_name = ?, professional_title = ?, email = ?, phone = ?, location = ?, summary = ?, skills = ?, tools = ? WHERE user_id = ?");
        $stmtUp->execute([$fullName, $title, $email, $phone, $location, $summary, json_encode($skills), json_encode($tools), $user['id']]);

        $gameEngine = new GameEngineService();
        $gameEngine->awardXPAndCoins($user['id'], 200, 50);

        header('Location: /resume-builder');
        exit;
    }

    public function printView()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmtR = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ?");
        $stmtR->execute([$user['id']]);
        $resume = $stmtR->fetch();

        if ($resume) {
            $resume['skills'] = json_decode($resume['skills'], true) ?? [];
            $resume['tools'] = json_decode($resume['tools'], true) ?? [];
            $resume['experience'] = json_decode($resume['experience'], true) ?? [];
            $resume['education'] = json_decode($resume['education'], true) ?? [];
        }

        require __DIR__ . '/../../views/resume/print.php';
    }
}
