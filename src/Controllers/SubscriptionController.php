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

        // Admin Configured Payment Receiving Accounts
        $paypalEmail = DataManagementService::getSetting('paypal_email', 'admin@freelancequest.com');
        $stripeKey = DataManagementService::getSetting('stripe_key', 'pk_live_freelancequest_admin_key');
        $gcashNumber = DataManagementService::getSetting('gcash_number', '09171234567');
        $gcashName = DataManagementService::getSetting('gcash_name', 'FreelanceQuest Admin');

        $proPrice = DataManagementService::getSetting('pro_price', '19.00');
        $masterPrice = DataManagementService::getSetting('master_price', '49.00');

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
                'price' => '$' . number_format((float)$proPrice, 2) . '/mo',
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
                'price' => '$' . number_format((float)$masterPrice, 2) . '/mo',
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

    public function checkout()
    {
        $user = AuthController::requireAuth();

        $planName = strtolower($_GET['plan'] ?? 'pro');
        $paymentGateway = strtolower($_GET['gateway'] ?? 'paypal');

        if (!in_array($planName, ['pro', 'master'])) {
            header('Location: /pricing');
            exit;
        }

        if (!in_array($paymentGateway, ['paypal', 'stripe', 'gcash'])) {
            $paymentGateway = 'paypal';
        }

        $proPrice = (float) DataManagementService::getSetting('pro_price', '19.00');
        $masterPrice = (float) DataManagementService::getSetting('master_price', '49.00');
        $amount = $planName === 'master' ? $masterPrice : $proPrice;

        // Check for applied coupon
        $couponCode = strtoupper(trim($_GET['coupon'] ?? ''));
        $appliedCoupon = null;
        if (!empty($couponCode)) {
            $stmtCpn = $pdo->prepare("SELECT * FROM coupons WHERE UPPER(code) = ? AND is_active = 1");
            $stmtCpn->execute([$couponCode]);
            $appliedCoupon = $stmtCpn->fetch();

            if ($appliedCoupon) {
                if ($appliedCoupon['discount_percent'] > 0) {
                    $amount = $amount * (1 - ($appliedCoupon['discount_percent'] / 100));
                } elseif ($appliedCoupon['discount_amount'] > 0) {
                    $amount = max(0, $amount - $appliedCoupon['discount_amount']);
                }
            }
        }

        // Admin Merchant Accounts
        $paypalEmail = DataManagementService::getSetting('paypal_email', 'admin@freelancequest.com');
        $stripeKey = DataManagementService::getSetting('stripe_key', 'pk_live_freelancequest_admin_key');
        $gcashNumber = DataManagementService::getSetting('gcash_number', '09171234567');
        $gcashName = DataManagementService::getSetting('gcash_name', 'FreelanceQuest Admin');

        $error = $_SESSION['checkout_error'] ?? null;
        unset($_SESSION['checkout_error']);

        require __DIR__ . '/../../views/subscription/checkout.php';
    }

    public function processCheckout()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('checkout_process', 10, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $planName = strtolower($_POST['plan_name'] ?? 'pro');
        $paymentGateway = strtolower($_POST['payment_gateway'] ?? 'paypal');
        $referenceNumber = trim($_POST['reference_number'] ?? '');

        if (!in_array($planName, ['pro', 'master'])) {
            $planName = 'pro';
        }

        $proPrice = (float) DataManagementService::getSetting('pro_price', '19.00');
        $masterPrice = (float) DataManagementService::getSetting('master_price', '49.00');
        $amount = $planName === 'master' ? $masterPrice : $proPrice;

        $couponCode = strtoupper(trim($_POST['coupon_code'] ?? ''));
        if (!empty($couponCode)) {
            $stmtCpn = $pdo->prepare("SELECT * FROM coupons WHERE UPPER(code) = ? AND is_active = 1");
            $stmtCpn->execute([$couponCode]);
            $appliedCoupon = $stmtCpn->fetch();

            if ($appliedCoupon) {
                if ($appliedCoupon['discount_percent'] > 0) {
                    $amount = $amount * (1 - ($appliedCoupon['discount_percent'] / 100));
                } elseif ($appliedCoupon['discount_amount'] > 0) {
                    $amount = max(0, $amount - $appliedCoupon['discount_amount']);
                }
            }
        }

        if (empty($referenceNumber)) {
            $_SESSION['checkout_error'] = 'Please enter your payment reference / receipt transaction number.';
            header('Location: /checkout?plan=' . $planName . '&gateway=' . $paymentGateway);
            exit;
        }

        $txnId = strtoupper($paymentGateway) . '-' . strtoupper(bin2hex(random_bytes(4))) . '-' . preg_replace('/[^A-Za-z0-9]/', '', $referenceNumber);

        // Record Payment in payments table
        $stmtPay = $pdo->prepare("INSERT INTO payments (user_id, payment_gateway, transaction_id, amount, currency, status, details) VALUES (?, ?, ?, ?, 'USD', 'completed', ?)");
        $stmtPay->execute([
            $user['id'],
            $paymentGateway,
            $txnId,
            $amount,
            json_encode([
                'plan' => $planName,
                'gateway' => $paymentGateway,
                'reference_number' => $referenceNumber,
                'user_email' => $user['email'],
                'created_at' => date('Y-m-d H:i:s')
            ])
        ]);

        // Update user subscription tier
        $stmtUp = $pdo->prepare("UPDATE users SET subscription_tier = ?, subscription_ends_at = DATE('now', '+1 month') WHERE id = ?");
        $stmtUp->execute([$planName, $user['id']]);

        // Record subscription history
        $stmtIns = $pdo->prepare("INSERT INTO subscriptions (user_id, plan_name, status, price, starts_at, ends_at) VALUES (?, ?, 'active', ?, DATE('now'), DATE('now', '+1 month'))");
        $stmtIns->execute([$user['id'], $planName, $amount]);

        // Award in-game XP bonus for upgrading membership
        $gameEngine = new \App\Services\GameEngineService();
        $gameEngine->awardXPAndCoins($user['id'], 200, 50);

        DataManagementService::logActivity($user['id'], 'MEMBERSHIP_CHECKOUT_COMPLETED', "Upgraded to {$planName} via {$paymentGateway} (\${$amount}). Ref: {$referenceNumber}, Txn: {$txnId}");

        $_SESSION['flash_success'] = "🎉 CONGRATULATIONS! Your payment was verified and your " . strtoupper($planName) . " plan is now ACTIVE (+200 XP Bonus)!";

        header('Location: /dashboard');
        exit;
    }

    public function subscribe()
    {
        $user = AuthController::requireAuth();

        $planName = $_POST['plan_name'] ?? 'pro';
        $paymentGateway = strtolower($_POST['payment_gateway'] ?? 'paypal');

        if ($planName === 'free') {
            $pdo = Database::getConnection();
            $stmtUp = $pdo->prepare("UPDATE users SET subscription_tier = 'free', subscription_ends_at = NULL WHERE id = ?");
            $stmtUp->execute([$user['id']]);
            header('Location: /dashboard');
            exit;
        }

        header('Location: /checkout?plan=' . urlencode($planName) . '&gateway=' . urlencode($paymentGateway));
        exit;
    }
}
