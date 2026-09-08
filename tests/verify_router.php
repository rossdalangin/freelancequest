<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../database/seed.php';

ob_start();
seedDatabase();
ob_clean();

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
use App\Controllers\AdminController;

$router = new Router();
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/admin/export-data', [AdminController::class, 'exportData']);

$router->dispatch('GET', '/admin/export-data');
$output = ob_get_clean();

if (str_contains($output, 'exported_at') && str_contains($output, 'users')) {
    echo "Admin export data route test passed!\n";
} else {
    echo "Admin export data route test failed.\n";
    exit(1);
}
