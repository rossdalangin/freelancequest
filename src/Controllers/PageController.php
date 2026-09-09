<?php

namespace App\Controllers;

class PageController
{
    public function about()
    {
        $user = AuthController::getCurrentUser();
        require __DIR__ . '/../../views/pages/about.php';
    }

    public function features()
    {
        $user = AuthController::getCurrentUser();
        require __DIR__ . '/../../views/pages/features.php';
    }

    public function faq()
    {
        $user = AuthController::getCurrentUser();
        require __DIR__ . '/../../views/pages/faq.php';
    }

    public function terms()
    {
        $user = AuthController::getCurrentUser();
        require __DIR__ . '/../../views/pages/terms.php';
    }

    public function privacy()
    {
        $user = AuthController::getCurrentUser();
        require __DIR__ . '/../../views/pages/privacy.php';
    }

    public function disclaimer()
    {
        $user = AuthController::getCurrentUser();
        require __DIR__ . '/../../views/pages/disclaimer.php';
    }

    public function contact()
    {
        $user = AuthController::getCurrentUser();
        $submitted = $_SESSION['contact_success'] ?? null;
        unset($_SESSION['contact_success']);

        require __DIR__ . '/../../views/pages/contact.php';
    }

    public function submitContact()
    {
        $user = AuthController::getCurrentUser();
        $_SESSION['contact_success'] = 'Thank you for reaching out! Our support team will get back to you within 24 hours.';
        header('Location: /contact');
        exit;
    }
}
