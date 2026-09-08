<?php

namespace App\Controllers;

use Database;
use App\Services\GameEngineService;
use App\Services\CertificateService;
use App\Services\SecurityService;

class LearningController
{
    public function index()
    {
        $pdo = Database::getConnection();

        $stmtC = $pdo->query("SELECT * FROM courses");
        $courses = $stmtC->fetchAll();
        foreach ($courses as &$c) {
            $stmtL = $pdo->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY sort_order ASC");
            $stmtL->execute([$c['id']]);
            $c['lessons'] = $stmtL->fetchAll();
        }

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        require __DIR__ . '/../../views/learning/index.php';
    }

    public function showLesson(string $slug)
    {
        $pdo = Database::getConnection();

        $stmtL = $pdo->prepare("SELECT * FROM lessons WHERE slug = ?");
        $stmtL->execute([$slug]);
        $lesson = $stmtL->fetch();

        if (!$lesson) {
            http_response_code(404);
            echo "Lesson not found";
            return;
        }

        $stmtQ = $pdo->prepare("SELECT * FROM quizzes WHERE lesson_id = ?");
        $stmtQ->execute([$lesson['id']]);
        $quizzes = $stmtQ->fetchAll();

        foreach ($quizzes as &$q) {
            $stmtQn = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
            $stmtQn->execute([$q['id']]);
            $q['questions'] = $stmtQn->fetchAll();
            foreach ($q['questions'] as &$qn) {
                $qn['options'] = json_decode($qn['options'], true);
            }
        }

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        $stmtProg = $pdo->prepare("SELECT * FROM lesson_progress WHERE user_id = ? AND lesson_id = ? AND completed = 1");
        $stmtProg->execute([$user['id'], $lesson['id']]);
        $isCompleted = (bool)$stmtProg->fetch();

        require __DIR__ . '/../../views/learning/show.php';
    }

    public function completeLesson(string $slug)
    {
        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('complete_lesson', 30, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $stmtL = $pdo->prepare("SELECT * FROM lessons WHERE slug = ?");
        $stmtL->execute([$slug]);
        $lesson = $stmtL->fetch();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        $stmtCheck = $pdo->prepare("SELECT * FROM lesson_progress WHERE user_id = ? AND lesson_id = ?");
        $stmtCheck->execute([$user['id'], $lesson['id']]);
        $prog = $stmtCheck->fetch();

        if (!$prog || !$prog['completed']) {
            $stmtIns = $pdo->prepare("INSERT OR REPLACE INTO lesson_progress (user_id, lesson_id, completed, completed_at) VALUES (?, ?, 1, CURRENT_TIMESTAMP)");
            $stmtIns->execute([$user['id'], $lesson['id']]);

            $gameEngine = new GameEngineService();
            $gameEngine->awardXPAndCoins($user['id'], $lesson['xp_reward'], $lesson['coin_reward']);
        }

        header('Location: /learn/' . $slug);
        exit;
    }

    public function submitQuiz(string $quizId)
    {
        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('submit_quiz', 30, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        $stmtQ = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmtQ->execute([$quizId]);
        $quiz = $stmtQ->fetch();

        $stmtQn = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmtQn->execute([$quizId]);
        $questions = $stmtQn->fetchAll();

        $answers = $_POST['answers'] ?? [];
        $correct = 0;
        $total = count($questions);

        foreach ($questions as $q) {
            if (isset($answers[$q['id']]) && $answers[$q['id']] === $q['correct_option']) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100) : 0;
        $passed = $score >= $quiz['passing_score'];

        $stmtIns = $pdo->prepare("INSERT INTO quiz_attempts (user_id, quiz_id, score, passed) VALUES (?, ?, ?, ?)");
        $stmtIns->execute([$user['id'], $quizId, $score, $passed ? 1 : 0]);

        if ($passed) {
            $gameEngine = new GameEngineService();
            $gameEngine->awardXPAndCoins($user['id'], $quiz['xp_reward'], 20);
        }

        header('Location: /dashboard');
        exit;
    }

    public function showMission(string $id)
    {
        $pdo = Database::getConnection();

        $stmtM = $pdo->prepare("SELECT * FROM missions WHERE id = ?");
        $stmtM->execute([$id]);
        $mission = $stmtM->fetch();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        require __DIR__ . '/../../views/missions/show.php';
    }

    public function submitMission(string $id)
    {
        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('submit_mission', 30, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $stmtM = $pdo->prepare("SELECT * FROM missions WHERE id = ?");
        $stmtM->execute([$id]);
        $mission = $stmtM->fetch();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        $sub = $_POST['submission_text'] ?? '';
        $score = strlen(trim($sub)) > 20 ? 90 : 50;
        $passed = $score >= 70;

        $stmtIns = $pdo->prepare("INSERT INTO mission_attempts (user_id, mission_id, completed, score, submission_data, feedback) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtIns->execute([$user['id'], $mission['id'], $passed ? 1 : 0, $score, json_encode(['text' => $sub]), $passed ? 'Great execution!' : 'Needs improvement.']);

        if ($passed) {
            $gameEngine = new GameEngineService();
            $gameEngine->awardXPAndCoins($user['id'], $mission['xp_reward'], $mission['coin_reward']);

            $certService = new CertificateService();
            $certService->generateCertificate($user['id'], 1, 'Freelancing Foundations Certificate', ['Admin' => 90]);
        }

        header('Location: /dashboard');
        exit;
    }
}
