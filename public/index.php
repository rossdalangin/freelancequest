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
use App\Controllers\UserController;
use App\Controllers\JobController;
use App\Controllers\CoverLetterController;
use App\Controllers\ApplicationTrackerController;

SecurityService::setSecurityHeaders();
SecurityService::startSecureSession();

$router = new Router();

// Public Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [PageController::class, 'about']);
$router->get('/features', [PageController::class, 'features']);
$router->get('/faq', [PageController::class, 'faq']);
$router->get('/terms', [PageController::class, 'terms']);
$router->get('/privacy', [PageController::class, 'privacy']);
$router->get('/disclaimer', [PageController::class, 'disclaimer']);
$router->get('/contact', [PageController::class, 'contact']);
$router->post('/contact', [PageController::class, 'submitContact']);

// Auth Routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'processLogin']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'processRegister']);
$router->post('/logout', [AuthController::class, 'logout']);

// Game Protected Routes
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/settings', [UserController::class, 'showSettings']);
$router->post('/settings', [UserController::class, 'updateSettings']);

// Cover Letter & Application Tracker Routes
$router->get('/cover-letter-builder', [CoverLetterController::class, 'index']);
$router->post('/cover-letter-builder/generate', [CoverLetterController::class, 'generate']);

$router->get('/application-tracker', [ApplicationTrackerController::class, 'index']);
$router->post('/application-tracker/add', [ApplicationTrackerController::class, 'add']);
$router->post('/application-tracker/{id}/status', [ApplicationTrackerController::class, 'updateStatus']);
$router->post('/application-tracker/{id}/delete', [ApplicationTrackerController::class, 'delete']);

// Job Marketplace Routes
$router->get('/jobs', [JobController::class, 'index']);
$router->get('/jobs/create', [JobController::class, 'showCreateForm']);
$router->post('/jobs/create', [JobController::class, 'store']);
$router->get('/jobs/{id}', [JobController::class, 'show']);
$router->post('/jobs/{id}/apply', [JobController::class, 'apply']);
$router->get('/my-applications', [JobController::class, 'myApplications']);

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
$router->post('/community/post/{id}/upvote', [CommunityController::class, 'upvotePost']);
$router->post('/community/post/{id}/delete', [CommunityController::class, 'deletePost']);
$router->post('/community/post/{id}/comment', [CommunityController::class, 'storeComment']);

$router->get('/pricing', [SubscriptionController::class, 'index']);
$router->post('/pricing/subscribe', [SubscriptionController::class, 'subscribe']);
$router->get('/checkout', [SubscriptionController::class, 'checkout']);
$router->post('/checkout/process', [SubscriptionController::class, 'processCheckout']);

$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/users', [AdminController::class, 'manageUsers']);
$router->post('/admin/users/{id}/plan', [AdminController::class, 'updateUserPlan']);
$router->get('/admin/lessons', [AdminController::class, 'manageLessons']);
$router->post('/admin/lessons/create', [AdminController::class, 'createLesson']);
$router->post('/admin/lessons/{id}/edit', [AdminController::class, 'editLesson']);
$router->post('/admin/lessons/{id}/delete', [AdminController::class, 'deleteLesson']);

$router->get('/admin/courses', [AdminController::class, 'manageCourses']);
$router->post('/admin/courses/create', [AdminController::class, 'createCourse']);
$router->post('/admin/courses/{id}/edit', [AdminController::class, 'editCourse']);
$router->post('/admin/courses/{id}/delete', [AdminController::class, 'deleteCourse']);

$router->get('/admin/quizzes', [AdminController::class, 'manageQuizzes']);
$router->post('/admin/quizzes/create', [AdminController::class, 'createQuiz']);
$router->post('/admin/quizzes/{id}/edit', [AdminController::class, 'editQuiz']);
$router->post('/admin/quizzes/{id}/delete', [AdminController::class, 'deleteQuiz']);
$router->post('/admin/quizzes/{id}/question/add', [AdminController::class, 'addQuizQuestion']);
$router->post('/admin/questions/{id}/edit', [AdminController::class, 'editQuestion']);
$router->post('/admin/questions/{id}/delete', [AdminController::class, 'deleteQuestion']);

$router->get('/admin/missions', [AdminController::class, 'manageMissions']);
$router->post('/admin/missions/create', [AdminController::class, 'createMission']);
$router->post('/admin/missions/{id}/edit', [AdminController::class, 'editMission']);
$router->post('/admin/missions/{id}/delete', [AdminController::class, 'deleteMission']);

$router->get('/admin/resources', [AdminController::class, 'manageResources']);
$router->post('/admin/resources/create', [AdminController::class, 'createResource']);
$router->post('/admin/resources/{id}/edit', [AdminController::class, 'editResource']);
$router->post('/admin/resources/{id}/delete', [AdminController::class, 'deleteResource']);
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
