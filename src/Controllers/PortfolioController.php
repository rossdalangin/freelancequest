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
        if (is_string($portfolio['contact_info'] ?? null)) {
            $portfolio['contact_info'] = json_decode($portfolio['contact_info'], true) ?? [];
        }

        require __DIR__ . '/../../views/portfolio/builder.php';
    }

    public function updateTheme()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $theme = $_POST['theme'] ?? 'default';
        $themeCost = ($theme === 'default') ? 0 : 100;

        $pdo = Database::getConnection();

        if ($themeCost > 0) {
            $stmtPurchased = $pdo->prepare("SELECT id FROM user_purchases WHERE user_id = ? AND item_type = 'portfolio_theme' AND transaction_id LIKE ?");
            $stmtPurchased->execute([$user['id'], "%{$theme}%"]);
            if (!$stmtPurchased->fetch()) {
                if (($user['coins'] ?? 0) < $themeCost) {
                    $_SESSION['portfolio_error'] = "Insufficient coins! Unlocking premium Portfolio themes requires {$themeCost} coins.";
                    header('Location: /portfolio-builder');
                    exit;
                }

                $stmtDeduct = $pdo->prepare("UPDATE users SET coins = coins - ? WHERE id = ?");
                $stmtDeduct->execute([$themeCost, $user['id']]);

                $txnId = 'COIN-THM-PRT-' . strtoupper($theme) . '-' . rand(100, 999);
                $stmtIns = $pdo->prepare("INSERT INTO user_purchases (user_id, item_type, item_id, payment_method, amount_paid, coins_spent, transaction_id) VALUES (?, 'portfolio_theme', 1, 'coins', 0.0, ?, ?)");
                $stmtIns->execute([$user['id'], $themeCost, $txnId]);
            }
        }

        $stmtUp = $pdo->prepare("UPDATE portfolios SET theme = ? WHERE user_id = ?");
        $stmtUp->execute([$theme, $user['id']]);

        $_SESSION['portfolio_success'] = "Portfolio website theme updated to '" . ucfirst($theme) . "'!";
        header('Location: /portfolio-builder');
        exit;
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
        $email = $_POST['email'] ?? $user['email'];
        $linkedin = $_POST['linkedin'] ?? '';

        $servicesRaw = $_POST['services_text'] ?? '';
        $services = [];
        $lines = array_filter(array_map('trim', explode("\n", $servicesRaw)));
        foreach ($lines as $line) {
            $parts = explode('|', $line);
            if (count($parts) >= 2) {
                $services[] = ['title' => trim($parts[0]), 'description' => trim($parts[1])];
            } else {
                $services[] = ['title' => trim($line), 'description' => 'Professional Virtual Assistant Service'];
            }
        }

        $contactInfo = json_encode(['email' => $email, 'linkedin' => $linkedin]);

        $stmtUp = $pdo->prepare("UPDATE portfolios SET title = ?, tagline = ?, about = ?, services = ?, contact_info = ? WHERE user_id = ?");
        $stmtUp->execute([$title, $tagline, $about, json_encode($services), $contactInfo, $user['id']]);

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
