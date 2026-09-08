<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../database/seed.php';

// Setup autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $file = $baseDir . str_replace('\\', '/', substr($class, $len)) . '.php';
    if (file_exists($file)) require $file;
});

class SecurityHardeningTest extends TestCase
{
    protected function setUp(): void
    {
        seedDatabase();
        $_POST = [];
    }

    public function testCsrfTokenGenerationAndVerification()
    {
        $token = \App\Services\SecurityService::getCsrfToken();
        $this->assertNotEmpty($token);
        $this->assertTrue(\App\Services\SecurityService::verifyCsrfToken($token));
        $this->assertFalse(\App\Services\SecurityService::verifyCsrfToken('invalid_token'));
    }

    public function testPathTraversalGuardsInMarketingController()
    {
        $controller = new \App\Controllers\MarketingController();
        ob_start();
        $controller->showDocument('../../etc/passwd');
        $output = ob_get_clean();

        $this->assertStringContainsString('Marketing document not found.', $output);
    }

    public function testRateLimiterKeyIncrement()
    {
        $key = 'test_rate_' . rand(100, 999);
        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue(\App\Services\SecurityService::checkRateLimit($key, 10, 60));
        }
    }
}
