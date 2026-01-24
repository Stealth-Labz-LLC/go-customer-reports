<?php

namespace App\Models;

use App\Core\Database;

class Category
{
    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_categories WHERE site_id = ? AND slug = ?",
            [$siteId, $slug]
        );
    }

    public static function all(int $siteId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM content_categories WHERE site_id = ? ORDER BY sort_order, name",
            [$siteId]
        );
    }

    public static function topLevel(int $siteId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT * FROM content_categories WHERE site_id = ? AND parent_id IS NULL ORDER BY sort_order, name",
            [$siteId]
        );
    }
}
