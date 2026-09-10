<?php

namespace App\Controllers;

class PageController
{
    private function renderDynamicPage(string $slug, string $viewPath)
    {
        $user = AuthController::getCurrentUser();
        $customContent = \App\Services\DataManagementService::getSetting('page_content_' . $slug);

        if (!empty($customContent)) {
            require __DIR__ . '/../../views/layout/header.php';
            echo '<div class="max-w-5xl mx-auto py-8 space-y-6 text-slate-100 leading-relaxed">';
            echo $customContent;
            echo '</div>';
            require __DIR__ . '/../../views/layout/footer.php';
            return;
        }

        require $viewPath;
    }

    public function about()
    {
        $this->renderDynamicPage('about', __DIR__ . '/../../views/pages/about.php');
    }

    public function features()
    {
        $this->renderDynamicPage('features', __DIR__ . '/../../views/pages/features.php');
    }

    public function faq()
    {
        $this->renderDynamicPage('faq', __DIR__ . '/../../views/pages/faq.php');
    }

    public function terms()
    {
        $this->renderDynamicPage('terms', __DIR__ . '/../../views/pages/terms.php');
    }

    public function privacy()
    {
        $this->renderDynamicPage('privacy', __DIR__ . '/../../views/pages/privacy.php');
    }

    public function disclaimer()
    {
        $this->renderDynamicPage('disclaimer', __DIR__ . '/../../views/pages/disclaimer.php');
    }

    public function contact()
    {
        $user = AuthController::getCurrentUser();
        $submitted = $_SESSION['contact_success'] ?? null;
        unset($_SESSION['contact_success']);

        $customContent = \App\Services\DataManagementService::getSetting('page_content_contact');
        if (!empty($customContent)) {
            require __DIR__ . '/../../views/layout/header.php';
            echo '<div class="max-w-5xl mx-auto py-8 space-y-6 text-slate-100 leading-relaxed">';
            echo $customContent;
            echo '</div>';
            require __DIR__ . '/../../views/layout/footer.php';
            return;
        }

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
