<?php

namespace App\Services;

use Database;

class GameEngineService
{
    public function awardXPAndCoins(int $userId, int $xp, int $coins, string $source = 'activity'): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) return [];

        $newXp = $user['xp'] + $xp;
        $newCoins = $user['coins'] + $coins;

        // Check level up
        $stmtLvl = $pdo->query("SELECT * FROM levels ORDER BY level_number ASC");
        $levels = $stmtLvl->fetchAll();

        $newLevel = $user['level'];
        foreach ($levels as $lvl) {
            if ($newXp >= $lvl['required_xp']) {
                $newLevel = max($newLevel, $lvl['level_number']);
            }
        }

        $leveledUp = $newLevel > $user['level'];

        // Update user
        $stmtUp = $pdo->prepare("UPDATE users SET xp = ?, coins = ?, level = ?, last_activity_date = DATE('now') WHERE id = ?");
        $stmtUp->execute([$newXp, $newCoins, $newLevel, $userId]);

        $this->evaluateBadges($userId);

        return [
            'xp_awarded' => $xp,
            'coins_awarded' => $coins,
            'total_xp' => $newXp,
            'total_coins' => $newCoins,
            'leveled_up' => $leveledUp,
            'new_level' => $newLevel,
        ];
    }

    public function evaluateBadges(int $userId): array
    {
        $pdo = Database::getConnection();

        $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmtUser->execute([$userId]);
        $user = $stmtUser->fetch();

        $awarded = [];

        // Check First Step badge
        $stmtBadge = $pdo->prepare("SELECT id FROM badges WHERE slug = 'first-step'");
        $stmtBadge->execute();
        $badge = $stmtBadge->fetch();

        if ($badge) {
            $stmtHas = $pdo->prepare("SELECT * FROM user_badges WHERE user_id = ? AND badge_id = ?");
            $stmtHas->execute([$userId, $badge['id']]);
            if (!$stmtHas->fetch()) {
                $stmtIns = $pdo->prepare("INSERT INTO user_badges (user_id, badge_id) VALUES (?, ?)");
                $stmtIns->execute([$userId, $badge['id']]);
                $awarded[] = $badge['id'];
            }
        }

        return $awarded;
    }

    public function generateDailyQuests(int $userId): array
    {
        $pdo = Database::getConnection();
        $today = date('Y-m-d');

        $stmt = $pdo->prepare("SELECT * FROM daily_quests WHERE user_id = ? AND quest_date = ?");
        $stmt->execute([$userId, $today]);
        $quests = $stmt->fetchAll();

        if (!empty($quests)) {
            return $quests;
        }

        $templates = [
            ['title' => 'Complete 1 Learning Lesson', 'quest_type' => 'lesson', 'xp' => 50, 'coins' => 15],
            ['title' => 'Pass 1 Knowledge Quiz', 'quest_type' => 'quiz', 'xp' => 100, 'coins' => 25],
            ['title' => 'Complete 1 Interactive Mission', 'quest_type' => 'mission', 'xp' => 150, 'coins' => 40],
            ['title' => 'Update Portfolio or Resume', 'quest_type' => 'portfolio', 'xp' => 75, 'coins' => 20],
        ];

        $generated = [];
        foreach ($templates as $tpl) {
            $stmtIns = $pdo->prepare("INSERT INTO daily_quests (user_id, title, quest_type, xp_reward, coin_reward, quest_date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmtIns->execute([$userId, $tpl['title'], $tpl['quest_type'], $tpl['xp'], $tpl['coins'], $today]);
            $tpl['id'] = $pdo->lastInsertId();
            $tpl['completed'] = 0;
            $generated[] = $tpl;
        }

        return $generated;
    }
}
