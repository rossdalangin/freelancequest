<?php

namespace App\Controllers;

class MarketingController
{
    public function index()
    {
        require __DIR__ . '/../../views/marketing/index.php';
    }

    public function showDocument(string $doc)
    {
        $doc = basename($doc);
        $allowed = [
            'LANDING_PAGE_COPY.md' => 'marketing/LANDING_PAGE_COPY.md',
            'SALES_PITCH_DECK.md' => 'marketing/SALES_PITCH_DECK.md',
            'EMAIL_MARKETING_SEQUENCES.md' => 'marketing/EMAIL_MARKETING_SEQUENCES.md',
            'SOCIAL_MEDIA_CAMPAIGNS.md' => 'marketing/SOCIAL_MEDIA_CAMPAIGNS.md',
        ];

        if (!isset($allowed[$doc])) {
            http_response_code(404);
            echo "Marketing document not found.";
            return;
        }

        $filePath = __DIR__ . '/../../' . $allowed[$doc];
        $content = file_exists($filePath) ? file_get_contents($filePath) : "Document empty.";
        $docTitle = str_replace('_', ' ', pathinfo($doc, PATHINFO_FILENAME));

        require __DIR__ . '/../../views/marketing/show.php';
    }
}
