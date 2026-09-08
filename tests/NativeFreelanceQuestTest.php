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

class NativeFreelanceQuestTest extends TestCase
{
    protected function setUp(): void
    {
        seedDatabase();
        $_POST['csrf_token'] = \App\Services\SecurityService::getCsrfToken();

        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id FROM users WHERE role = 'student' LIMIT 1");
        $studentId = $stmt->fetchColumn();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }
        $_SESSION['user_id'] = $studentId;
    }

    public function testDatabaseSeedingIntegrity()
    {
        $pdo = Database::getConnection();
        $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $lessonCount = $pdo->query("SELECT COUNT(*) FROM lessons")->fetchColumn();
        $levelCount = $pdo->query("SELECT COUNT(*) FROM levels")->fetchColumn();

        $this->assertEquals(2, $userCount);
        $this->assertEquals(34, $lessonCount);
        $this->assertEquals(16, $levelCount);
    }

    public function testGameEngineXpAwardAndLevelUp()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id FROM users WHERE role = 'student' LIMIT 1");
        $userId = $stmt->fetchColumn();

        $gameEngine = new \App\Services\GameEngineService();
        $res = $gameEngine->awardXPAndCoins($userId, 500, 20);

        $this->assertGreaterThanOrEqual(500, $res['total_xp']);
        $this->assertGreaterThanOrEqual(20, $res['total_coins']);
    }

    public function testCertificateGenerationAndVerification()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id FROM users WHERE role = 'student' LIMIT 1");
        $userId = $stmt->fetchColumn();

        $certService = new \App\Services\CertificateService();
        $cert = $certService->generateCertificate($userId, 1, 'Freelancing Foundations Certificate', ['Admin' => 95]);

        $this->assertStringStartsWith('FQ-', $cert['certificate_code']);

        $stmtVerify = $pdo->prepare("SELECT * FROM certificates WHERE certificate_code = ?");
        $stmtVerify->execute([$cert['certificate_code']]);
        $found = $stmtVerify->fetch();

        $this->assertNotEmpty($found);
    }

    public function testMarketingControllerRendersDocuments()
    {
        $controller = new \App\Controllers\MarketingController();
        ob_start();
        $controller->showDocument('LANDING_PAGE_COPY.md');
        $output = ob_get_clean();

        $this->assertStringContainsString('FREELANCEQUEST', $output);
        $this->assertStringContainsString('MARKETING & SALES COLLATERAL', $output);
    }

    public function testResumePrintViewRenders()
    {
        $controller = new \App\Controllers\ResumeBuilderController();
        ob_start();
        $controller->printView();
        $output = ob_get_clean();

        $this->assertStringContainsString('PRINT / SAVE RESUME PDF', $output);
        $this->assertStringContainsString('Professional Summary', $output);
    }

    public function testDataManagementService()
    {
        \App\Services\DataManagementService::setSetting('xp_multiplier', '1.5');
        \App\Services\DataManagementService::setSetting('paypal_email', 'merchant@admin.com');
        \App\Services\DataManagementService::setSetting('gcash_number', '09170000000');

        $this->assertEquals('1.5', \App\Services\DataManagementService::getSetting('xp_multiplier'));
        $this->assertEquals('merchant@admin.com', \App\Services\DataManagementService::getSetting('paypal_email'));
        $this->assertEquals('09170000000', \App\Services\DataManagementService::getSetting('gcash_number'));

        \App\Services\DataManagementService::logActivity(1, 'TEST_ACTION', 'Testing details');
        $logs = \App\Services\DataManagementService::getAuditLogs(5);
        $this->assertNotEmpty($logs);

        $backupJson = \App\Services\DataManagementService::exportBackupJson();
        $this->assertStringContainsString('exported_at', $backupJson);
        $this->assertStringContainsString('users', $backupJson);
    }

    public function testUserSettingsController()
    {
        $controller = new \App\Controllers\UserController();
        ob_start();
        $controller->showSettings();
        $output = ob_get_clean();

        $this->assertStringContainsString('ACCOUNT & GAME SETTINGS', $output);
        $this->assertStringContainsString('Professional Headline', $output);
    }
}
