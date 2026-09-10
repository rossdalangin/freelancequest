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

        // Check if testimonials setting is ON
        $showTestimonials = \App\Services\DataManagementService::getSetting('show_homepage_testimonials', '1') === '1';
        $testimonials = [];

        if ($showTestimonials) {
            $stmtTst = $pdo->query("SELECT t.*, u.name as user_name, u.username as user_username, u.level as user_level, u.role as user_role FROM testimonials t JOIN users u ON t.user_id = u.id WHERE t.is_approved = 1 ORDER BY t.id DESC LIMIT 6");
            $testimonials = $stmtTst->fetchAll() ?: [];
        }

        require __DIR__ . '/../../views/home.php';
    }
}
