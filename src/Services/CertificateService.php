<?php

namespace App\Services;

use Database;

class CertificateService
{
    public function generateCertificate(int $userId, int $levelNumber, string $title, array $skillsBreakdown = []): array
    {
        $pdo = Database::getConnection();

        $stmtCheck = $pdo->prepare("SELECT * FROM certificates WHERE user_id = ? AND level_number = ?");
        $stmtCheck->execute([$userId, $levelNumber]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            return $existing;
        }

        $code = 'FQ-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 4)) . '-' . rand(1000, 9999);

        $stmtIns = $pdo->prepare("INSERT INTO certificates (user_id, certificate_code, title, level_number, skills_breakdown) VALUES (?, ?, ?, ?, ?)");
        $stmtIns->execute([$userId, $code, $title, $levelNumber, json_encode($skillsBreakdown)]);

        $id = $pdo->lastInsertId();

        return [
            'id' => $id,
            'user_id' => $userId,
            'certificate_code' => $code,
            'title' => $title,
            'level_number' => $levelNumber,
            'skills_breakdown' => $skillsBreakdown,
            'issued_at' => date('Y-m-d H:i:s'),
        ];
    }
}
