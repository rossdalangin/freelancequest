<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../database/schema.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Services/GameEngineService.php';
require_once __DIR__ . '/../src/Services/CertificateService.php';

initializeSchema();

$userId = \App\Models\User::create([
    'name' => 'Test Runner',
    'email' => 'test@example.com',
    'password' => password_hash('password', PASSWORD_BCRYPT),
    'role' => 'student',
]);

echo "Created User ID: {$userId}\n";

$gameEngine = new \App\Services\GameEngineService();
$res = $gameEngine->awardXPAndCoins($userId, 200, 50);
echo "Awarded XP. Total XP: {$res['total_xp']}\n";

$certService = new \App\Services\CertificateService();
$cert = $certService->generateCertificate($userId, 1, 'Test Foundations', ['Admin' => 90]);
echo "Generated Certificate Code: {$cert['certificate_code']}\n";

echo "Service Verification Passed Successfully.\n";
