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
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmtC = $pdo->query("SELECT * FROM courses");
        $courses = $stmtC->fetchAll();
        $certService = new CertificateService();

        foreach ($courses as &$c) {
            $stmtL = $pdo->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY sort_order ASC");
            $stmtL->execute([$c['id']]);
            $c['lessons'] = $stmtL->fetchAll();

            // Check if user completed all lessons in this course
            $totalLessons = count($c['lessons']);
            $stmtComp = $pdo->prepare("SELECT COUNT(*) FROM lesson_progress lp JOIN lessons l ON lp.lesson_id = l.id WHERE lp.user_id = ? AND l.course_id = ? AND lp.completed = 1");
            $stmtComp->execute([$user['id'], $c['id']]);
            $completedLessons = (int)$stmtComp->fetchColumn();

            $c['is_completed'] = ($totalLessons > 0 && $completedLessons >= $totalLessons);
            $c['certificate'] = null;

            if ($c['is_completed']) {
                // Auto-issue certificate for completing all lessons in this course level
                $c['certificate'] = $certService->generateCertificate($user['id'], $c['level_number'], $c['title'] . ' Completion Certificate', [
                    'Course Level' => $c['level_number'],
                    'Lessons Completed' => $totalLessons . '/' . $totalLessons
                ]);
            }
        }

        require __DIR__ . '/../../views/learning/index.php';
    }

    public function showLesson(string $slug)
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmtL = $pdo->prepare("SELECT * FROM lessons WHERE slug = ?");
        $stmtL->execute([$slug]);
        $lesson = $stmtL->fetch();

        if (!$lesson) {
            http_response_code(404);
            echo "Lesson not found";
            return;
        }

        // Check subscription level restriction: Levels 4+ require Pro or Master subscription UNLESS unlocked with coins
        $userTier = strtolower($user['subscription_tier'] ?? 'free');
        $levelNum = (int)$lesson['level_number'];

        $isUnlockedViaCoins = false;
        if ($levelNum >= 4) {
            $stmtCoins = $pdo->prepare("SELECT id FROM user_purchases WHERE user_id = ? AND item_type = 'course_level' AND item_id = ?");
            $stmtCoins->execute([$user['id'], $levelNum]);
            $isUnlockedViaCoins = (bool)$stmtCoins->fetch();
        }

        if ($levelNum >= 4 && !in_array($userTier, ['pro', 'master']) && !$isUnlockedViaCoins && ($user['role'] ?? '') !== 'admin') {
            $_SESSION['checkout_error'] = "🔒 Course Level " . $lesson['level_number'] . " is locked on Free Tier. Upgrade to Pro/Master or unlock this level for 500 Coins!";
            header('Location: /pricing');
            exit;
        }

        $stmtQ = $pdo->prepare("SELECT * FROM quizzes WHERE lesson_id = ?");
        $stmtQ->execute([$lesson['id']]);
        $quizzes = $stmtQ->fetchAll();

        foreach ($quizzes as &$q) {
            $stmtQn = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
            $stmtQn->execute([$q['id']]);
            $q['questions'] = $stmtQn->fetchAll();
            shuffle($q['questions']); // Randomize questions display order
            foreach ($q['questions'] as &$qn) {
                $opts = json_decode($qn['options'], true);
                if (is_array($opts)) {
                    shuffle($opts); // Randomize options display order
                }
                $qn['options'] = $opts;
            }
        }

        $stmtProg = $pdo->prepare("SELECT * FROM lesson_progress WHERE user_id = ? AND lesson_id = ? AND completed = 1");
        $stmtProg->execute([$user['id'], $lesson['id']]);
        $isCompleted = (bool)$stmtProg->fetch();

        require __DIR__ . '/../../views/learning/show.php';
    }

    public function completeLesson(string $slug)
    {
        $user = AuthController::requireAuth();

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

        // Enforce subscription lock or coin unlock on complete action
        $userTier = strtolower($user['subscription_tier'] ?? 'free');
        $levelNum = (int)$lesson['level_number'];

        $isUnlockedViaCoins = false;
        if ($levelNum >= 4) {
            $stmtCoins = $pdo->prepare("SELECT id FROM user_purchases WHERE user_id = ? AND item_type = 'course_level' AND item_id = ?");
            $stmtCoins->execute([$user['id'], $levelNum]);
            $isUnlockedViaCoins = (bool)$stmtCoins->fetch();
        }

        if ($levelNum >= 4 && !in_array($userTier, ['pro', 'master']) && !$isUnlockedViaCoins && ($user['role'] ?? '') !== 'admin') {
            header('Location: /pricing');
            exit;
        }

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
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('submit_quiz', 30, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

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

        // Check previous highest passed score to prevent unlimited points farming
        $stmtPrev = $pdo->prepare("SELECT MAX(score) as max_score FROM quiz_attempts WHERE user_id = ? AND quiz_id = ? AND passed = 1");
        $stmtPrev->execute([$user['id'], $quizId]);
        $prevMaxScore = (int) ($stmtPrev->fetchColumn() ?? 0);

        $stmtIns = $pdo->prepare("INSERT INTO quiz_attempts (user_id, quiz_id, score, passed) VALUES (?, ?, ?, ?)");
        $stmtIns->execute([$user['id'], $quizId, $score, $passed ? 1 : 0]);

        if ($passed && $score > $prevMaxScore) {
            // Only award XP/Coins for score improvements above previous best score
            $scoreDelta = $score - $prevMaxScore;
            $quizCoinReward = $quiz['coin_reward'] ?? 25;
            $quizXpReward = $quiz['xp_reward'] ?? 100;

            $earnedXP = (int) round(($scoreDelta / 100) * $quizXpReward);
            $earnedCoins = (int) round(($scoreDelta / 100) * $quizCoinReward);

            if ($earnedXP > 0 || $earnedCoins > 0) {
                $gameEngine = new GameEngineService();
                $gameEngine->awardXPAndCoins($user['id'], $earnedXP, $earnedCoins);
            }
        }

        header('Location: /dashboard');
        exit;
    }

    public function showMission(string $id)
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmtM = $pdo->prepare("SELECT * FROM missions WHERE id = ?");
        $stmtM->execute([$id]);
        $mission = $stmtM->fetch();

        require __DIR__ . '/../../views/missions/show.php';
    }

    public function unlockLevelWithCoins(string $levelNumber)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $levelNum = (int)$levelNumber;
        $coinCost = 500;

        if (($user['coins'] ?? 0) < $coinCost) {
            $_SESSION['learning_error'] = "Insufficient coins! Unlocking Level {$levelNum} requires {$coinCost} coins. You currently have {$user['coins']} coins.";
            header('Location: /learn');
            exit;
        }

        $pdo = Database::getConnection();

        // Check if already unlocked
        $stmtCheck = $pdo->prepare("SELECT id FROM user_purchases WHERE user_id = ? AND item_type = 'course_level' AND item_id = ?");
        $stmtCheck->execute([$user['id'], $levelNum]);
        if ($stmtCheck->fetch()) {
            $_SESSION['learning_success'] = "Course Level {$levelNum} is already unlocked on your account!";
            header('Location: /learn');
            exit;
        }

        // Deduct coins & record purchase
        $stmtDeduct = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
        $stmtDeduct->execute([$coinCost, $user['id']]);

        $txnId = 'COIN-LVL-' . strtoupper(substr(md5(uniqid((string)rand(), true)), 0, 8));
        $stmtIns = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'course_level', ?, 'coins', 0.0, ?, ?)");
        $stmtIns->execute([$user['id'], $levelNum, $coinCost, $txnId]);

        \App\Services\DataManagementService::logActivity($user['id'], 'LEVEL_UNLOCKED_COINS', "Unlocked Course Level {$levelNum} using {$coinCost} coins");

        $_SESSION['learning_success'] = "🎉 Level {$levelNum} has been permanently unlocked for {$coinCost} Coins!";
        header('Location: /learn');
        exit;
    }

    public function submitMission(string $id)
    {
        $user = AuthController::requireAuth();

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
