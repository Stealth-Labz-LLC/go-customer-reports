<?php

namespace App\Models;

use App\Core\Database;

class Listicle
{
    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_listicles WHERE site_id = ? AND slug = ? AND status = 'published'",
            [$siteId, $slug]
        );
    }

    public static function latest(int $siteId, int $limit = 12, int $offset = 0): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM content_listicles WHERE site_id = ? AND status = 'published' ORDER BY published_at DESC LIMIT ? OFFSET ?",
            [$siteId, $limit, $offset]
        );
    }

    public static function count(int $siteId): int
    {
        $db = Database::getInstance();
        $result = $db->fetchOne(
            "SELECT COUNT(*) as total FROM content_listicles WHERE site_id = ? AND status = 'published'",
            [$siteId]
        );
        return (int) ($result->total ?? 0);
    }
}
