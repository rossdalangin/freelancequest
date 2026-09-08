<?php

namespace App\Controllers;

use Database;
use App\Services\GameEngineService;
use App\Services\SecurityService;

class CommunityController
{
    public function index()
    {
        $pdo = Database::getConnection();

        $stmtP = $pdo->query("SELECT cp.*, u.name as user_name, u.level as user_level FROM community_posts cp JOIN users u ON cp.user_id = u.id ORDER BY cp.id DESC LIMIT 20");
        $posts = $stmtP->fetchAll();

        foreach ($posts as &$post) {
            $stmtCm = $pdo->prepare("SELECT c.*, u.name as user_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.community_post_id = ? ORDER BY c.id ASC");
            $stmtCm->execute([$post['id']]);
            $post['comments'] = $stmtCm->fetchAll();
        }

        $stmtG = $pdo->query("SELECT * FROM groups");
        $groups = $stmtG->fetchAll();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        require __DIR__ . '/../../views/community/index.php';
    }

    public function storePost()
    {
        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('community_post', 20, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $groupId = $_POST['group_id'] ?? null;

        if ($title && $content) {
            $stmtIns = $pdo->prepare("INSERT INTO community_posts (user_id, group_id, title, content) VALUES (?, ?, ?, ?)");
            $stmtIns->execute([$user['id'], $groupId ?: null, $title, $content]);

            $gameEngine = new GameEngineService();
            $gameEngine->awardXPAndCoins($user['id'], 50, 10);
        }

        header('Location: /community');
        exit;
    }

    public function storeComment(string $postId)
    {
        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('community_comment', 30, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

        $stmtUser = $pdo->query("SELECT * FROM users WHERE role = 'student' LIMIT 1");
        $user = $stmtUser->fetch();

        $content = $_POST['content'] ?? '';

        if ($content) {
            $stmtIns = $pdo->prepare("INSERT INTO comments (community_post_id, user_id, content) VALUES (?, ?, ?)");
            $stmtIns->execute([$postId, $user['id'], $content]);

            $gameEngine = new GameEngineService();
            $gameEngine->awardXPAndCoins($user['id'], 25, 5);
        }

        header('Location: /community');
        exit;
    }
}
