<?php

namespace App\Controllers;

use Database;

class CertificateController
{
    public function verify(string $code)
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("SELECT c.*, u.name as user_name FROM certificates c JOIN users u ON c.user_id = u.id WHERE c.certificate_code = ?");
        $stmt->execute([$code]);
        $certificate = $stmt->fetch();

        if ($certificate) {
            $certificate['skills_breakdown'] = json_decode($certificate['skills_breakdown'], true) ?? [];
        }

        require __DIR__ . '/../../views/certificate/verify.php';
    }
}
