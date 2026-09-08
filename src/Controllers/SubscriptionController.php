<?php

namespace App\Controllers;

use Database;

class SubscriptionController
{
    public function index()
    {
        $pdo = Database::getConnection();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

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
        $pdo = Database::getConnection();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        $planName = $_POST['plan_name'] ?? 'pro';
        $price = $planName === 'master' ? 49.00 : ($planName === 'pro' ? 19.00 : 0.00);

        $stmtUp = $pdo->prepare("UPDATE users SET subscription_tier = ?, subscription_ends_at = DATE('now', '+1 month') WHERE id = ?");
        $stmtUp->execute([$planName, $user['id']]);

        $stmtIns = $pdo->prepare("INSERT INTO subscriptions (user_id, plan_name, status, price, starts_at, ends_at) VALUES (?, ?, 'active', ?, DATE('now'), DATE('now', '+1 month'))");
        $stmtIns->execute([$user['id'], $planName, $price]);

        header('Location: /dashboard');
        exit;
    }
}
