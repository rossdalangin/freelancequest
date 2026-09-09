<?php

namespace App\Controllers;

use Database;
use App\Services\GameEngineService;
use App\Services\SecurityService;

class CommunityController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $groupSlug = $_GET['group'] ?? null;
        if ($groupSlug) {
            $stmtP = $pdo->prepare("SELECT cp.*, u.name as user_name, u.level as user_level FROM community_posts cp JOIN users u ON cp.user_id = u.id JOIN groups g ON cp.group_id = g.id WHERE g.slug = ? ORDER BY cp.id DESC LIMIT 20");
            $stmtP->execute([$groupSlug]);
        } else {
            $stmtP = $pdo->query("SELECT cp.*, u.name as user_name, u.level as user_level FROM community_posts cp JOIN users u ON cp.user_id = u.id ORDER BY cp.id DESC LIMIT 20");
        }
        $posts = $stmtP->fetchAll();

        foreach ($posts as &$post) {
            $stmtCm = $pdo->prepare("SELECT c.*, u.name as user_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.community_post_id = ? ORDER BY c.id ASC");
            $stmtCm->execute([$post['id']]);
            $post['comments'] = $stmtCm->fetchAll();
        }

        $stmtG = $pdo->query("SELECT * FROM groups");
        $groups = $stmtG->fetchAll();

        require __DIR__ . '/../../views/community/index.php';
    }

    public function storePost()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('community_post', 20, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

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

    public function upvotePost(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("UPDATE community_posts SET upvotes = upvotes + 1 WHERE id = ?");
        $stmt->execute([$id]);

        $gameEngine = new GameEngineService();
        $gameEngine->awardXPAndCoins($user['id'], 10, 2);

        header('Location: /community');
        exit;
    }

    public function deletePost(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        if (($user['role'] ?? '') === 'admin') {
            $stmt = $pdo->prepare("DELETE FROM community_posts WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $pdo->prepare("DELETE FROM community_posts WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $user['id']]);
        }

        header('Location: /community');
        exit;
    }

    public function storeComment(string $postId)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('community_comment', 30, 60)) {
            http_response_code(429);
            die("Rate limit exceeded.");
        }

        $pdo = Database::getConnection();

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
