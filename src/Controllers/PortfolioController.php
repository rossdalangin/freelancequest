<?php

namespace App\Controllers;

use Database;
use App\Services\GameEngineService;
use App\Services\SecurityService;

class PortfolioController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $slug = $user['username'] ?? 'user-' . $user['id'];

        $stmtP = $pdo->prepare("SELECT * FROM portfolios WHERE user_id = ?");
        $stmtP->execute([$user['id']]);
        $portfolio = $stmtP->fetch();

        if (!$portfolio) {
            $stmtIns = $pdo->prepare("INSERT INTO portfolios (user_id, slug, title, tagline, about, services, contact_info) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtIns->execute([
                $user['id'],
                $slug,
                $user['name'] . ' - Portfolio',
                'Professional Virtual Assistant & Executive Partner',
                'I help founders, executives, and growing businesses optimize their operations.',
                json_encode([
                    ['title' => 'Email & Calendar Management', 'description' => 'Inbox zero and seamless scheduling across time zones.'],
                    ['title' => 'Lead Generation & Market Research', 'description' => 'Target prospect list creation, contact validation, and CRM entry.'],
                ]),
                json_encode(['email' => $user['email']])
            ]);

            $stmtP->execute([$user['id']]);
            $portfolio = $stmtP->fetch();
        }

        $portfolio['services'] = json_decode($portfolio['services'], true) ?? [];

        require __DIR__ . '/../../views/portfolio/builder.php';
    }

    public function update()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $title = $_POST['title'] ?? '';
        $tagline = $_POST['tagline'] ?? '';
        $about = $_POST['about'] ?? '';

        $stmtUp = $pdo->prepare("UPDATE portfolios SET title = ?, tagline = ?, about = ? WHERE user_id = ?");
        $stmtUp->execute([$title, $tagline, $about, $user['id']]);

        $gameEngine = new GameEngineService();
        $gameEngine->awardXPAndCoins($user['id'], 250, 60);

        header('Location: /portfolio-builder');
        exit;
    }

    public function showPublic(string $username)
    {
        $username = basename($username);
        $pdo = Database::getConnection();

        $stmtP = $pdo->prepare("SELECT * FROM portfolios WHERE slug = ?");
        $stmtP->execute([$username]);
        $portfolio = $stmtP->fetch();

        if (!$portfolio) {
            http_response_code(404);
            echo "Portfolio not found";
            return;
        }

        $portfolio['services'] = json_decode($portfolio['services'], true) ?? [];
        $portfolio['contact_info'] = json_decode($portfolio['contact_info'], true) ?? [];

        require __DIR__ . '/../../views/portfolio/public.php';
    }
}
