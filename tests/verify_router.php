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
use App\Controllers\MarketingController;

$router = new Router();
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/marketing/doc/{doc}', [MarketingController::class, 'showDocument']);

ob_start();
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/marketing/doc/LANDING_PAGE_COPY.md';
$router->dispatch('GET', '/marketing/doc/LANDING_PAGE_COPY.md');
$output = ob_get_clean();

if (str_contains($output, 'MARKETING & SALES COLLATERAL') && str_contains($output, 'FREELANCEQUEST')) {
    echo "Router & Marketing document test passed!\n";
} else {
    echo "Router simulation failed.\n";
    exit(1);
}
