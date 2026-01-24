<?php

namespace App\Models;

use App\Core\Database;

class Page
{
    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_pages WHERE site_id = ? AND slug = ? AND status = 'published'",
            [$siteId, $slug]
        );
    }

    public static function all(int $siteId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM content_pages WHERE site_id = ? AND status = 'published'",
            [$siteId]
        );
    }
}
