<?php

require_once __DIR__ . '/../config/database.php';

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Router;
use App\Services\SecurityService;
use App\Controllers\DashboardController;
use App\Controllers\OnboardingController;
use App\Controllers\LearningController;
use App\Controllers\ResumeBuilderController;
use App\Controllers\PortfolioController;
use App\Controllers\CertificateController;
use App\Controllers\CommunityController;
use App\Controllers\SubscriptionController;
use App\Controllers\AdminController;
use App\Controllers\ResourceVaultController;
use App\Controllers\MarketingController;

SecurityService::setSecurityHeaders();
SecurityService::startSecureSession();

$router = new Router();

$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/onboarding', [OnboardingController::class, 'index']);
$router->post('/onboarding', [OnboardingController::class, 'store']);

$router->get('/learn', [LearningController::class, 'index']);
$router->get('/learn/{slug}', [LearningController::class, 'showLesson']);
$router->post('/learn/{slug}/complete', [LearningController::class, 'completeLesson']);
$router->post('/quiz/{id}/submit', [LearningController::class, 'submitQuiz']);

$router->get('/mission/{id}', [LearningController::class, 'showMission']);
$router->post('/mission/{id}/submit', [LearningController::class, 'submitMission']);

$router->get('/resume-builder', [ResumeBuilderController::class, 'index']);
$router->post('/resume-builder', [ResumeBuilderController::class, 'update']);

$router->get('/portfolio-builder', [PortfolioController::class, 'index']);
$router->post('/portfolio-builder', [PortfolioController::class, 'update']);
$router->get('/p/{username}', [PortfolioController::class, 'showPublic']);

$router->get('/verify/{code}', [CertificateController::class, 'verify']);

$router->get('/resources', [ResourceVaultController::class, 'index']);

$router->get('/community', [CommunityController::class, 'index']);
$router->post('/community/post', [CommunityController::class, 'storePost']);
$router->post('/community/post/{id}/comment', [CommunityController::class, 'storeComment']);

$router->get('/pricing', [SubscriptionController::class, 'index']);
$router->post('/pricing/subscribe', [SubscriptionController::class, 'subscribe']);

$router->get('/admin', [AdminController::class, 'index']);
$router->post('/admin/ai-course-builder', [AdminController::class, 'generateAiCourse']);

$router->get('/marketing', [MarketingController::class, 'index']);
$router->get('/marketing/doc/{doc}', [MarketingController::class, 'showDocument']);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

$router->dispatch($method, $uri);
