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
}
