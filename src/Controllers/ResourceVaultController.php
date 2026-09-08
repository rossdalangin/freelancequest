<?php

namespace App\Controllers;

use Database;

class ResourceVaultController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT * FROM resources ORDER BY level_number ASC, id ASC");
        $resources = $stmt->fetchAll();

        require __DIR__ . '/../../views/resources/index.php';
    }
}
