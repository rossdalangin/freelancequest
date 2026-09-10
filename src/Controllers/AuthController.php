<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;

class AuthController
{
    public static function getCurrentUser(): ?array
    {
        SecurityService::startSecureSession();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return null;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public static function requireAuth(): array
    {
        $user = self::getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }
        return $user;
    }

    public function showLogin()
    {
        if (self::getCurrentUser()) {
            header('Location: /dashboard');
            exit;
        }

        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);

        require __DIR__ . '/../../views/auth/login.php';
    }

    public function processLogin()
    {
        SecurityService::startSecureSession();
        SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '');

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /dashboard');
            exit;
        }

        $_SESSION['auth_error'] = 'Invalid email address or password.';
        header('Location: /login');
        exit;
    }

    public function showRegister()
    {
        if (self::getCurrentUser()) {
            header('Location: /dashboard');
            exit;
        }

        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);

        require __DIR__ . '/../../views/auth/register.php';
    }

    public function processRegister()
    {
        SecurityService::startSecureSession();
        SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '');

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($username) || strlen($password) < 6) {
            $_SESSION['auth_error'] = 'Please fill in all fields correctly (password min 6 characters).';
            header('Location: /register');
            exit;
        }

        $pdo = Database::getConnection();

        // Check duplicate
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $_SESSION['auth_error'] = 'Email or username already registered.';
            header('Location: /register');
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, username, password, role, level, xp, coins, streak_count, subscription_tier) VALUES (?, ?, ?, ?, 'student', 0, 0, 100, 1, 'free')");
        $stmt->execute([$name, $email, $username, $hash]);

        $newUserId = $pdo->lastInsertId();

        // Referral reward processing
        $refCode = trim($_POST['ref'] ?? $_GET['ref'] ?? '');
        if (!empty($refCode)) {
            $stmtRef = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmtRef->execute([$refCode, $refCode]);
            $referrer = $stmtRef->fetch();

            if ($referrer && $referrer['id'] != $newUserId) {
                $gameEngine = new \App\Services\GameEngineService();
                $gameEngine->awardXPAndCoins($referrer['id'], 250, 50);
                \App\Services\DataManagementService::logActivity($referrer['id'], 'REFERRAL_REWARD', "Awarded +250 XP and +50 Coins for inviting new user: {$username}");
            }
        }

        $_SESSION['user_id'] = $newUserId;

        header('Location: /onboarding');
        exit;
    }

    public function logout()
    {
        SecurityService::startSecureSession();
        unset($_SESSION['user_id']);
        session_destroy();
        header('Location: /');
        exit;
    }
}
