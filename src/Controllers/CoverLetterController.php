<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\GameEngineService;

class CoverLetterController
{
    public function index()
    {
        $user = AuthController::requireAuth();

        $generatedLetter = $_SESSION['generated_cover_letter'] ?? null;
        $letterMode = $_SESSION['letter_mode'] ?? 'KISS Method (Short, Direct & High-Trust)';
        unset($_SESSION['generated_cover_letter'], $_SESSION['letter_mode']);

        require __DIR__ . '/../../views/cover-letter/builder.php';
    }

    public function generate()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $jobTitle = trim($_POST['job_title'] ?? 'Executive Virtual Assistant');
        $company = trim($_POST['company'] ?? 'Acme Startup Inc');
        $keySkills = trim($_POST['skills'] ?? 'Google Workspace, Calendar Management, Inbox Zero');
        $experience = trim($_POST['experience'] ?? 'Managed C-suite calendar scheduling, travel itineraries, and inbox zero organization.');
        $mode = trim($_POST['mode'] ?? 'standard');

        if ($mode === 'kiss') {
            // KISS Method: Keep It Short & Sweet / Keep It Simple, Stupid
            // Ultra-direct, 3-paragraph, expert-level pitch that cuts fluff and builds instant trust
            $letter = "Hi " . htmlspecialchars($company) . " Team,\n\n"
                . "I saw you are looking for an expert " . htmlspecialchars($jobTitle) . " who can step in and take care of " . htmlspecialchars($keySkills) . " with zero micromanagement.\n\n"
                . "Here is how I can help you immediately:\n"
                . "• " . htmlspecialchars($experience) . "\n"
                . "• Proven track record saving executives 10+ hours weekly with rapid, error-free execution\n"
                . "• Daily async updates (Slack/Loom) so you always know task progress without having to ask\n\n"
                . "You can review my verified work samples and portfolio here: freelancequest.com/p/" . htmlspecialchars($user['username'] ?? $user['id']) . "\n\n"
                . "Are you open for a quick 10-minute call tomorrow at 2 PM EST to discuss your target timeline?\n\n"
                . "Best regards,\n"
                . htmlspecialchars($user['name']) . "\n"
                . htmlspecialchars($user['headline'] ?? 'Virtual Assistant Specialist') . "\n"
                . htmlspecialchars($user['email']);

            $_SESSION['letter_mode'] = 'KISS Method (Short, Direct & High-Trust)';
        } else {
            // Standard Proposal Letter
            $letter = "Dear " . htmlspecialchars($company) . " Hiring Team,\n\n"
                . "I am writing to express my strong enthusiasm for the " . htmlspecialchars($jobTitle) . " role at " . htmlspecialchars($company) . ". Having reviewed your job requirements, I am confident that my experience in " . htmlspecialchars($keySkills) . " makes me an ideal partner for your team.\n\n"
                . "In my previous virtual assistance projects, I specialized in saving founders and executives 10+ hours per week by streamlining daily administrative workflows. Specifically:\n"
                . "• Proactive inbox management and calendar scheduling across time zones\n"
                . "• Rapid response times and zero-defect accuracy in task execution\n"
                . "• " . htmlspecialchars($experience) . "\n\n"
                . "What excites me about " . htmlspecialchars($company) . " is your commitment to growth and efficiency. "
                . "I would welcome the opportunity to discuss how I can immediately take administrative burdens off your plate this week.\n\n"
                . "Thank you for your time and consideration. You can view my verified skill portfolio and client work samples at: freelancequest.com/p/" . htmlspecialchars($user['username'] ?? $user['id']) . "\n\n"
                . "Sincerely,\n"
                . htmlspecialchars($user['name']) . "\n"
                . htmlspecialchars($user['headline'] ?? 'Virtual Assistant Specialist') . "\n"
                . htmlspecialchars($user['email']);

            $_SESSION['letter_mode'] = 'Standard Detailed Proposal';
        }

        // Award in-game XP for generating a cover letter
        $gameEngine = new GameEngineService();
        $gameEngine->awardXPAndCoins($user['id'], 100, 20);

        $_SESSION['generated_cover_letter'] = $letter;
        header('Location: /cover-letter-builder');
        exit;
    }
}
