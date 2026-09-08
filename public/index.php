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
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\PageController;
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

// Public Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [PageController::class, 'about']);
$router->get('/features', [PageController::class, 'features']);
$router->get('/faq', [PageController::class, 'faq']);

// Auth Routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'processLogin']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'processRegister']);
$router->post('/logout', [AuthController::class, 'logout']);

// Game Protected Routes
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
$router->get('/resume-builder/print', [ResumeBuilderController::class, 'printView']);
$router->post('/resume-builder', [ResumeBuilderController::class, 'update']);

$router->get('/portfolio-builder', [PortfolioController::class, 'index']);
$router->post('/portfolio-builder', [PortfolioController::class, 'update']);
$router->get('/p/{username}', [PortfolioController::class, 'showPublic']);

$router->get('/verify/{code}', [CertificateController::class, 'verify']);
$router->get('/verify/{code}/print', [CertificateController::class, 'printView']);

$router->get('/resources', [ResourceVaultController::class, 'index']);

$router->get('/community', [CommunityController::class, 'index']);
$router->post('/community/post', [CommunityController::class, 'storePost']);
$router->post('/community/post/{id}/comment', [CommunityController::class, 'storeComment']);

$router->get('/pricing', [SubscriptionController::class, 'index']);
$router->post('/pricing/subscribe', [SubscriptionController::class, 'subscribe']);

$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/users', [AdminController::class, 'manageUsers']);
$router->post('/admin/users/{id}/plan', [AdminController::class, 'updateUserPlan']);
$router->get('/admin/lessons', [AdminController::class, 'manageLessons']);
$router->post('/admin/lessons/create', [AdminController::class, 'createLesson']);
$router->get('/admin/quizzes', [AdminController::class, 'manageQuizzes']);
$router->post('/admin/quizzes/create', [AdminController::class, 'createQuiz']);
$router->get('/admin/missions', [AdminController::class, 'manageMissions']);
$router->post('/admin/missions/create', [AdminController::class, 'createMission']);
$router->get('/admin/payments', [AdminController::class, 'managePayments']);
$router->post('/admin/payments/{id}/refund', [AdminController::class, 'refundPayment']);
$router->get('/admin/logs', [AdminController::class, 'viewLogs']);
$router->post('/admin/settings', [AdminController::class, 'updateSettings']);
$router->post('/admin/ai-course-builder', [AdminController::class, 'generateAiCourse']);
$router->get('/admin/export-data', [AdminController::class, 'exportData']);

$router->get('/marketing', [MarketingController::class, 'index']);
$router->get('/marketing/doc/{doc}', [MarketingController::class, 'showDocument']);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

$router->dispatch($method, $uri);
