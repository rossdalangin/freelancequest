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

        // Experience parsing (Format: Role | Company | Period | Details)
        $expRaw = $_POST['experience_text'] ?? '';
        $experience = [];
        $expLines = array_filter(array_map('trim', explode("\n", $expRaw)));
        foreach ($expLines as $line) {
            $parts = explode('|', $line);
            if (count($parts) >= 4) {
                $experience[] = [
                    'role' => trim($parts[0]),
                    'company' => trim($parts[1]),
                    'period' => trim($parts[2]),
                    'details' => trim($parts[3])
                ];
            } else {
                $experience[] = [
                    'role' => 'Virtual Assistant Specialist',
                    'company' => 'Remote Client Operations',
                    'period' => '2025 - Present',
                    'details' => trim($line)
                ];
            }
        }

        // Education parsing (Format: Degree | Institution | Year)
        $eduRaw = $_POST['education_text'] ?? '';
        $education = [];
        $eduLines = array_filter(array_map('trim', explode("\n", $eduRaw)));
        foreach ($eduLines as $line) {
            $parts = explode('|', $line);
            if (count($parts) >= 3) {
                $education[] = [
                    'degree' => trim($parts[0]),
                    'institution' => trim($parts[1]),
                    'year' => trim($parts[2])
                ];
            } else {
                $education[] = [
                    'degree' => 'VA Masterclass Certification',
                    'institution' => 'FreelanceQuest Academy',
                    'year' => '2025'
                ];
            }
        }

        $stmtUp = $pdo->prepare("UPDATE resumes SET full_name = ?, professional_title = ?, email = ?, phone = ?, location = ?, summary = ?, skills = ?, tools = ?, experience = ?, education = ? WHERE user_id = ?");
        $stmtUp->execute([$fullName, $title, $email, $phone, $location, $summary, json_encode($skills), json_encode($tools), json_encode($experience), json_encode($education), $user['id']]);

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
