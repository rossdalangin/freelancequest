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
}
