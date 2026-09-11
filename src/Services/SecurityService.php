<?php

namespace App\Services;

class SecurityService
{
    public static function startSecureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            session_start();
        }
    }

    public static function setSecurityHeaders(): void
    {
        if (!headers_sent()) {
            header('X-Frame-Options: SAMEORIGIN');
            header('X-Content-Type-Options: nosniff');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; img-src 'self' data: https:; connect-src 'self';");
        }
    }

    public static function regenerateSession(): void
    {
        self::startSecureSession();
        session_regenerate_id(true);
    }

    public static function getCsrfToken(): string
    {
        self::startSecureSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken(?string $token): bool
    {
        self::startSecureSession();
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function checkRateLimit(string $key, int $maxRequests = 30, int $windowSeconds = 60): bool
    {
        self::startSecureSession();
        $now = time();
        if (!isset($_SESSION['rate_limits'][$key])) {
            $_SESSION['rate_limits'][$key] = ['count' => 1, 'start' => $now];
            return true;
        }

        $limit = &$_SESSION['rate_limits'][$key];
        if ($now - $limit['start'] > $windowSeconds) {
            $limit['count'] = 1;
            $limit['start'] = $now;
            return true;
        }

        $limit['count']++;
        return $limit['count'] <= $maxRequests;
    }
}
