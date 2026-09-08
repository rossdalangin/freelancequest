<?php

namespace App\Controllers;

use Database;

class ResourceVaultController
{
    public function index()
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->query("SELECT * FROM resources ORDER BY level_number ASC");
        $resources = $stmt->fetchAll();

        require __DIR__ . '/../../views/resources/index.php';
    }
}
