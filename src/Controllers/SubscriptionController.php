<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\DataManagementService;

class SubscriptionController
{
    public function index()
    {
        $user = AuthController::getCurrentUser();
        $pdo = Database::getConnection();

        $plans = [
            [
                'name' => 'free',
                'title' => 'Free Starter',
                'price' => '$0',
                'features' => [
                    'Access to Levels 1 - 3',
                    'Basic Quizzes & Interactive Missions',
                    'Resource Vault Starter Files',
                    'Community Forum Access',
                ],
            ],
            [
                'name' => 'pro',
                'title' => 'Pro Freelancer',
                'price' => '$19/mo',
                'features' => [
                    'Access to ALL 15 Career Levels',
                    'Unlimited Quizzes & Advanced Missions',
                    'AI Tutor & Proposal Analyzer',
                    'Interactive Resume & Portfolio Builders',
                    'Official Verifiable Certificates',
                    'Exclusive Pro Resource Vault Downloads',
                ],
            ],
            [
                'name' => 'master',
                'title' => 'Master Agency',
                'price' => '$49/mo',
                'features' => [
                    'Everything in Pro Plan',
                    'AI Interview Arena Simulator',
                    '1-on-1 Mentor Session Coaching',
                    'Agency SOP & Scaling Blueprint',
                    'Priority Job Placement Matching',
                    'Custom Portfolio Domain Branding',
                ],
            ],
        ];

        require __DIR__ . '/../../views/subscription/index.php';
    }

    public function subscribe()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $planName = $_POST['plan_name'] ?? 'pro';
        $paymentGateway = strtolower($_POST['payment_gateway'] ?? 'paypal');
        if (!in_array($paymentGateway, ['paypal', 'stripe', 'gcash'])) {
            $paymentGateway = 'paypal';
        }

        $amount = $planName === 'master' ? 49.00 : ($planName === 'pro' ? 19.00 : 0.00);

        if ($amount > 0) {
            $txnId = strtoupper($paymentGateway) . '-' . strtoupper(bin2hex(random_bytes(6)));

            // Record Payment
            $stmtPay = $pdo->prepare("INSERT INTO payments (user_id, payment_gateway, transaction_id, amount, currency, status, details) VALUES (?, ?, ?, ?, 'USD', 'completed', ?)");
            $stmtPay->execute([
                $user['id'],
                $paymentGateway,
                $txnId,
                $amount,
                json_encode(['plan' => $planName, 'gateway' => $paymentGateway, 'date' => date('Y-m-d H:i:s')])
            ]);

            DataManagementService::logActivity($user['id'], 'MEMBERSHIP_UPGRADE_PAYMENT', "Upgraded to {$planName} via {$paymentGateway} (\${$amount}). Txn: {$txnId}");
        }

        $stmtUp = $pdo->prepare("UPDATE users SET subscription_tier = ?, subscription_ends_at = DATE('now', '+1 month') WHERE id = ?");
        $stmtUp->execute([$planName, $user['id']]);

        $stmtIns = $pdo->prepare("INSERT INTO subscriptions (user_id, plan_name, status, price, starts_at, ends_at) VALUES (?, ?, 'active', ?, DATE('now'), DATE('now', '+1 month'))");
        $stmtIns->execute([$user['id'], $planName, $amount]);

        header('Location: /dashboard');
        exit;
    }
}
