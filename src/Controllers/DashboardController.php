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

        // Skill categories with dynamic user mastery progress calculation
        $stmtCats = $pdo->query("SELECT * FROM skill_categories ORDER BY id ASC");
        $skillCategories = $stmtCats->fetchAll();

        // Count user completed lessons to calculate overall skill tree unlock level
        $stmtCompletedLessons = $pdo->prepare("SELECT COUNT(*) FROM lesson_progress WHERE user_id = ? AND completed = 1");
        $stmtCompletedLessons->execute([$userId]);
        $userCompletedLessonsCount = (int)$stmtCompletedLessons->fetchColumn();

        foreach ($skillCategories as &$cat) {
            $stmtS = $pdo->prepare("SELECT MIN(id) as id, name, slug, description FROM skills WHERE skill_category_id = ? GROUP BY name ORDER BY id ASC");
            $stmtS->execute([$cat['id']]);
            $cat['skills'] = $stmtS->fetchAll();

            $totalSkillsInCat = count($cat['skills']);
            $catMasteryPoints = 0;

            foreach ($cat['skills'] as &$sk) {
                // Determine skill proficiency & unlock status based on user level and completed lessons
                $isUnlocked = $user['level'] >= 1 || $userCompletedLessonsCount > 0;
                $sk['is_unlocked'] = $isUnlocked;
                $sk['proficiency'] = $isUnlocked ? min(100, max(25, round(($user['level'] / 15) * 100) + ($userCompletedLessonsCount * 2))) : 0;
                $sk['level_tier'] = $sk['proficiency'] >= 90 ? 'Master' : ($sk['proficiency'] >= 60 ? 'Pro' : ($sk['proficiency'] >= 30 ? 'Adept' : 'Novice'));
                $catMasteryPoints += $sk['proficiency'];
            }

            $cat['mastery_percentage'] = $totalSkillsInCat > 0 ? min(100, round($catMasteryPoints / $totalSkillsInCat)) : 0;
        }

        // Recent Badges
        $stmtB = $pdo->prepare("SELECT b.* FROM user_badges ub JOIN badges b ON ub.badge_id = b.id WHERE ub.user_id = ? ORDER BY ub.id DESC LIMIT 4");
        $stmtB->execute([$userId]);
        $recentBadges = $stmtB->fetchAll();

        require __DIR__ . '/../../views/dashboard/index.php';
    }
}
