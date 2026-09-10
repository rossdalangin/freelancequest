<?php

namespace App\Controllers;

use Database;

class HomeController
{
    public function index()
    {
        $user = AuthController::getCurrentUser();

        $pdo = Database::getConnection();

        // Fetch stats for homepage
        $stmtL = $pdo->query("SELECT COUNT(*) FROM levels");
        $totalLevels = $stmtL->fetchColumn() ?: 16;

        $stmtLes = $pdo->query("SELECT COUNT(*) FROM lessons");
        $totalLessons = $stmtLes->fetchColumn() ?: 34;

        $stmtM = $pdo->query("SELECT COUNT(*) FROM missions");
        $totalMissions = $stmtM->fetchColumn() ?: 50;

        $stmtRes = $pdo->query("SELECT COUNT(*) FROM resources");
        $totalResources = $stmtRes->fetchColumn() ?: 15;

        $stmtSk = $pdo->query("SELECT COUNT(*) FROM skills");
        $totalSkills = $stmtSk->fetchColumn() ?: 24;

        // Fetch all levels with descriptions and badges
        $stmtAllLevels = $pdo->query("SELECT * FROM levels ORDER BY level_number ASC");
        $careerLevels = $stmtAllLevels->fetchAll() ?: [];

        require __DIR__ . '/../../views/home.php';
    }
}
