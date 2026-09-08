<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../database/seed.php';

seedDatabase();

// Setup autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $file = $baseDir . str_replace('\\', '/', substr($class, $len)) . '.php';
    if (file_exists($file)) require $file;
});

use App\Router;
use App\Controllers\DashboardController;
use App\Controllers\ResumeBuilderController;
use App\Controllers\CertificateController;

$router = new Router();
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/resume-builder/print', [ResumeBuilderController::class, 'printView']);
$router->get('/verify/{code}/print', [CertificateController::class, 'printView']);

ob_start();
$router->dispatch('GET', '/resume-builder/print');
$output = ob_get_clean();

if (str_contains($output, 'PRINT / SAVE RESUME PDF')) {
    echo "Resume print route test passed!\n";
} else {
    echo "Resume print route test failed.\n";
    exit(1);
}
