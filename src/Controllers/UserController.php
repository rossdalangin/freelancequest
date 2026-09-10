<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\DataManagementService;

class UserController
{
    public function showSettings()
    {
        $user = AuthController::requireAuth();

        $success = $_SESSION['settings_success'] ?? null;
        $error = $_SESSION['settings_error'] ?? null;
        unset($_SESSION['settings_success'], $_SESSION['settings_error']);

        require __DIR__ . '/../../views/user/settings.php';
    }

    public function updateSettings()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $name = trim($_POST['name'] ?? '');
        $headline = trim($_POST['headline'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $oldPassword = $_POST['old_password'] ?? '';

        if (empty($name)) {
            $_SESSION['settings_error'] = 'Name cannot be empty.';
            header('Location: /settings');
            exit;
        }

        $pdo = Database::getConnection();

        if (!empty($newPassword)) {
            if (empty($oldPassword) || !password_verify($oldPassword, $user['password'])) {
                $_SESSION['settings_error'] = 'Current password verification failed.';
                header('Location: /settings');
                exit;
            }
            if (strlen($newPassword) < 6) {
                $_SESSION['settings_error'] = 'New password must be at least 6 characters.';
                header('Location: /settings');
                exit;
            }

            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET name = ?, headline = ?, bio = ?, password = ? WHERE id = ?");
            $stmt->execute([$name, $headline, $bio, $newHash, $user['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, headline = ?, bio = ? WHERE id = ?");
            $stmt->execute([$name, $headline, $bio, $user['id']]);
        }

        DataManagementService::logActivity($user['id'], 'USER_PROFILE_UPDATE', "Updated profile details for {$name}");

        $_SESSION['settings_success'] = 'Account settings updated successfully!';
        header('Location: /settings');
        exit;
    }

    public function submitTestimonial()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $rating = (int)($_POST['rating'] ?? 5);
        $reviewText = trim($_POST['review_text'] ?? '');

        if ($rating < 1 || $rating > 5) {
            $rating = 5;
        }

        if (empty($reviewText)) {
            $_SESSION['settings_error'] = 'Testimonial review text cannot be empty.';
            header('Location: /settings');
            exit;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO testimonials (user_id, rating, review_text, is_approved) VALUES (?, ?, ?, 1)");
        $stmt->execute([$user['id'], $rating, $reviewText]);

        $gameEngine = new \App\Services\GameEngineService();
        $gameEngine->awardXPAndCoins($user['id'], 150, 30);

        DataManagementService::logActivity($user['id'], 'TESTIMONIAL_SUBMITTED', "Submitted {$rating}-star testimonial for FreelanceQuest");

        $_SESSION['settings_success'] = 'Thank you for your testimonial! Your review was submitted and +150 XP was awarded.';
        header('Location: /settings');
        exit;
    }
}
