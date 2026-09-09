<?php

namespace App\Controllers;

use Database;
use App\Services\GameEngineService;

class DashboardController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $userId = $user['id'];

        $pdo = Database::getConnection();

        $gameEngine = new GameEngineService();
        $quests = $gameEngine->generateDailyQuests($userId);

        // Fetch levels
        $stmtLevels = $pdo->query("SELECT * FROM levels ORDER BY level_number ASC");
        $levels = $stmtLevels->fetchAll();

        // Current Level details
        $stmtCurrLvl = $pdo->prepare("SELECT * FROM levels WHERE level_number = ?");
        $stmtCurrLvl->execute([$user['level']]);
        $currentLevelModel = $stmtCurrLvl->fetch() ?: ['title' => 'Explorer'];

        // Current active mission
        $stmtMission = $pdo->prepare("SELECT * FROM missions WHERE level_number <= ? ORDER BY id DESC LIMIT 1");
        $stmtMission->execute([max(1, $user['level'])]);
        $currentMission = $stmtMission->fetch();

        // Skill categories - query distinct skills per category to prevent duplicate skill names
        $stmtCats = $pdo->query("SELECT * FROM skill_categories ORDER BY id ASC");
        $skillCategories = $stmtCats->fetchAll();
        foreach ($skillCategories as &$cat) {
            $stmtS = $pdo->prepare("SELECT MIN(id) as id, name, slug, description FROM skills WHERE skill_category_id = ? GROUP BY name ORDER BY id ASC");
            $stmtS->execute([$cat['id']]);
            $cat['skills'] = $stmtS->fetchAll();
        }

        // Recent Badges
        $stmtB = $pdo->prepare("SELECT b.* FROM user_badges ub JOIN badges b ON ub.badge_id = b.id WHERE ub.user_id = ? ORDER BY ub.id DESC LIMIT 4");
        $stmtB->execute([$userId]);
        $recentBadges = $stmtB->fetchAll();

        require __DIR__ . '/../../views/dashboard/index.php';
    }
}
