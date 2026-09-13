<?php

namespace App\Controllers;

use Database;
use App\Services\SecurityService;
use App\Services\DataManagementService;

class SupportTicketController
{
    public function index()
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $status = $_GET['status'] ?? null;

        if ($status && in_array($status, ['open', 'in_progress', 'resolved', 'closed'])) {
            $stmt = $pdo->prepare("SELECT st.*,
                (SELECT COUNT(*) FROM support_ticket_replies str WHERE str.ticket_id = st.id) as replies_count
                FROM support_tickets st
                WHERE st.user_id = ? AND st.status = ?
                ORDER BY st.updated_at DESC");
            $stmt->execute([$user['id'], $status]);
        } else {
            $stmt = $pdo->prepare("SELECT st.*,
                (SELECT COUNT(*) FROM support_ticket_replies str WHERE str.ticket_id = st.id) as replies_count
                FROM support_tickets st
                WHERE st.user_id = ?
                ORDER BY st.updated_at DESC");
            $stmt->execute([$user['id']]);
        }
        $tickets = $stmt->fetchAll();

        // Get summary stats
        $stmtStats = $pdo->prepare("SELECT status, COUNT(*) as count FROM support_tickets WHERE user_id = ? GROUP BY status");
        $stmtStats->execute([$user['id']]);
        $statsRaw = $stmtStats->fetchAll();

        $stats = [
            'open' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'closed' => 0,
            'total' => 0
        ];
        foreach ($statsRaw as $s) {
            if (isset($stats[$s['status']])) {
                $stats[$s['status']] = (int)$s['count'];
            }
            $stats['total'] += (int)$s['count'];
        }

        $success = $_SESSION['ticket_success'] ?? null;
        $error = $_SESSION['ticket_error'] ?? null;
        unset($_SESSION['ticket_success'], $_SESSION['ticket_error']);

        require __DIR__ . '/../../views/support/index.php';
    }

    public function showCreateForm()
    {
        $user = AuthController::requireAuth();
        require __DIR__ . '/../../views/support/create.php';
    }

    public function store()
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        if (!SecurityService::checkRateLimit('ticket_creation', 5, 60)) {
            http_response_code(429);
            die("Rate limit exceeded. Please wait a minute before submitting another support ticket.");
        }

        $subject = trim($_POST['subject'] ?? '');
        $category = $_POST['category'] ?? 'General Inquiry';
        $priority = $_POST['priority'] ?? 'Medium';
        $message = trim($_POST['message'] ?? '');

        if (empty($subject) || empty($message)) {
            $_SESSION['ticket_error'] = 'Please provide both a subject and details for your support ticket.';
            header('Location: /support/create');
            exit;
        }

        $pdo = Database::getConnection();

        // Generate unique ticket number (e.g., TCK-8F3A29)
        $ticketNumber = 'TCK-' . strtoupper(substr(md5(uniqid((string)rand(), true)), 0, 6));

        $stmt = $pdo->prepare("INSERT INTO support_tickets (ticket_number, user_id, subject, category, priority, status) VALUES (?, ?, ?, ?, ?, 'open')");
        $stmt->execute([$ticketNumber, $user['id'], $subject, $category, $priority]);
        $ticketId = $pdo->lastInsertId();

        // Add initial message as reply
        $stmtReply = $pdo->prepare("INSERT INTO support_ticket_replies (ticket_id, user_id, message, is_admin_reply) VALUES (?, ?, ?, 0)");
        $stmtReply->execute([$ticketId, $user['id'], $message]);

        DataManagementService::logActivity($user['id'], 'TICKET_CREATED', "Created support ticket #{$ticketNumber}: {$subject}");

        $_SESSION['ticket_success'] = "Support ticket #{$ticketNumber} submitted successfully! Our help desk team will review your ticket shortly.";
        header('Location: /support/tickets/' . $ticketId);
        exit;
    }

    public function show(string $id)
    {
        $user = AuthController::requireAuth();
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT st.*, u.name as user_name, u.email as user_email, u.role as user_role
            FROM support_tickets st
            JOIN users u ON st.user_id = u.id
            WHERE st.id = ?");
        $stmt->execute([$id]);
        $ticket = $stmt->fetch();

        if (!$ticket) {
            http_response_code(404);
            die("Support ticket not found.");
        }

        // Only ticket owner or admin can view ticket
        if ($ticket['user_id'] != $user['id'] && ($user['role'] ?? 'student') !== 'admin') {
            http_response_code(403);
            die("Unauthorized access to support ticket.");
        }

        $stmtReplies = $pdo->prepare("SELECT str.*, u.name as author_name, u.role as author_role, u.avatar_url
            FROM support_ticket_replies str
            JOIN users u ON str.user_id = u.id
            WHERE str.ticket_id = ?
            ORDER BY str.id ASC");
        $stmtReplies->execute([$id]);
        $replies = $stmtReplies->fetchAll();

        $success = $_SESSION['ticket_success'] ?? null;
        $error = $_SESSION['ticket_error'] ?? null;
        unset($_SESSION['ticket_success'], $_SESSION['ticket_error']);

        require __DIR__ . '/../../views/support/show.php';
    }

    public function reply(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $message = trim($_POST['message'] ?? '');
        if (empty($message)) {
            $_SESSION['ticket_error'] = 'Reply message cannot be empty.';
            header('Location: /support/tickets/' . $id);
            exit;
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM support_tickets WHERE id = ?");
        $stmt->execute([$id]);
        $ticket = $stmt->fetch();

        if (!$ticket) {
            http_response_code(404);
            die("Ticket not found.");
        }

        $isAdmin = ($user['role'] ?? 'student') === 'admin';

        if ($ticket['user_id'] != $user['id'] && !$isAdmin) {
            http_response_code(403);
            die("Unauthorized to reply to this ticket.");
        }

        $isAdminReply = $isAdmin ? 1 : 0;

        $stmtReply = $pdo->prepare("INSERT INTO support_ticket_replies (ticket_id, user_id, message, is_admin_reply) VALUES (?, ?, ?, ?)");
        $stmtReply->execute([$id, $user['id'], $message, $isAdminReply]);

        // If admin replies, update ticket status to in_progress or open if closed
        $newStatus = $ticket['status'];
        if ($isAdmin && $ticket['status'] === 'open') {
            $newStatus = 'in_progress';
        } elseif (!$isAdmin && ($ticket['status'] === 'resolved' || $ticket['status'] === 'closed')) {
            $newStatus = 'open';
        }

        $stmtUpd = $pdo->prepare("UPDATE support_tickets SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmtUpd->execute([$newStatus, $id]);

        DataManagementService::logActivity($user['id'], 'TICKET_REPLIED', "Replied to support ticket #{$ticket['ticket_number']}");

        $_SESSION['ticket_success'] = 'Reply posted successfully.';
        header('Location: /support/tickets/' . $id);
        exit;
    }

    public function close(string $id)
    {
        $user = AuthController::requireAuth();

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT * FROM support_tickets WHERE id = ?");
        $stmt->execute([$id]);
        $ticket = $stmt->fetch();

        if (!$ticket) {
            http_response_code(404);
            die("Ticket not found.");
        }

        $isAdmin = ($user['role'] ?? 'student') === 'admin';
        if ($ticket['user_id'] != $user['id'] && !$isAdmin) {
            http_response_code(403);
            die("Unauthorized action.");
        }

        $stmtUpd = $pdo->prepare("UPDATE support_tickets SET status = 'closed', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmtUpd->execute([$id]);

        DataManagementService::logActivity($user['id'], 'TICKET_CLOSED', "Closed support ticket #{$ticket['ticket_number']}");

        $_SESSION['ticket_success'] = 'Ticket has been marked as closed.';
        header('Location: /support/tickets/' . $id);
        exit;
    }

    // Admin Helpdesk Portal
    public function adminTickets()
    {
        $user = AuthController::requireAuth();
        if (($user['role'] ?? 'student') !== 'admin') {
            http_response_code(403);
            die("Access Denied. Admin privileges required.");
        }

        $pdo = Database::getConnection();

        $statusFilter = $_GET['status'] ?? null;
        $priorityFilter = $_GET['priority'] ?? null;
        $search = $_GET['search'] ?? null;

        $query = "SELECT st.*, u.name as user_name, u.email as user_email,
            (SELECT COUNT(*) FROM support_ticket_replies str WHERE str.ticket_id = st.id) as replies_count
            FROM support_tickets st
            JOIN users u ON st.user_id = u.id
            WHERE 1=1";
        $params = [];

        if ($statusFilter) {
            $query .= " AND st.status = ?";
            $params[] = $statusFilter;
        }

        if ($priorityFilter) {
            $query .= " AND st.priority = ?";
            $params[] = $priorityFilter;
        }

        if ($search) {
            $query .= " AND (st.ticket_number LIKE ? OR st.subject LIKE ? OR u.name LIKE ? OR u.email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $query .= " ORDER BY CASE st.status WHEN 'open' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'resolved' THEN 3 ELSE 4 END, st.updated_at DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $tickets = $stmt->fetchAll();

        // Get global helpdesk stats
        $openCount = (int)$pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'open'")->fetchColumn();
        $inProgressCount = (int)$pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'in_progress'")->fetchColumn();
        $resolvedCount = (int)$pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'resolved'")->fetchColumn();
        $closedCount = (int)$pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'closed'")->fetchColumn();
        $totalCount = (int)$pdo->query("SELECT COUNT(*) FROM support_tickets")->fetchColumn();

        $success = $_SESSION['admin_ticket_success'] ?? null;
        $error = $_SESSION['admin_ticket_error'] ?? null;
        unset($_SESSION['admin_ticket_success'], $_SESSION['admin_ticket_error']);

        require __DIR__ . '/../../views/admin/tickets.php';
    }

    public function adminUpdateStatus(string $id)
    {
        $user = AuthController::requireAuth();
        if (($user['role'] ?? 'student') !== 'admin') {
            http_response_code(403);
            die("Access Denied. Admin privileges required.");
        }

        if (!SecurityService::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die("Invalid CSRF Token.");
        }

        $status = $_POST['status'] ?? 'open';
        $priority = $_POST['priority'] ?? 'Medium';

        if (!in_array($status, ['open', 'in_progress', 'resolved', 'closed'])) {
            $status = 'open';
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE support_tickets SET status = ?, priority = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$status, $priority, $id]);

        DataManagementService::logActivity($user['id'], 'ADMIN_TICKET_STATUS_UPDATED', "Updated status of ticket #{$id} to {$status} and priority to {$priority}");

        $_SESSION['admin_ticket_success'] = "Ticket status updated to '{$status}' successfully.";
        header('Location: /admin/tickets');
        exit;
    }
}
