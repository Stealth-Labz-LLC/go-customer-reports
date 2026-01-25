<?php

namespace App\Models;

use App\Core\Database;

class Article
{
    public static function findBySlug(int $siteId, string $slug): ?object
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM content_articles WHERE site_id = ? AND slug = ? AND status = 'published'",
            [$siteId, $slug]
        );
    }

    public static function latest(int $siteId, int $limit = 12, int $offset = 0): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT a.*, c.slug as category_slug, c.name as category_name
             FROM content_articles a
             LEFT JOIN content_categories c ON a.primary_category_id = c.id
             WHERE a.site_id = ? AND a.status = 'published'
             ORDER BY a.published_at DESC LIMIT ? OFFSET ?",
            [$siteId, $limit, $offset]
        );
    }

    public static function byCategory(int $siteId, int $categoryId, int $limit = 12): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT a.*, c.slug as category_slug, c.name as category_name
             FROM content_articles a
             LEFT JOIN content_categories c ON a.primary_category_id = c.id
             JOIN content_article_category ac ON a.id = ac.article_id
             WHERE a.site_id = ? AND ac.category_id = ? AND a.status = 'published'
             ORDER BY a.published_at DESC LIMIT ?",
            [$siteId, $categoryId, $limit]
        );
    }

    public static function count(int $siteId): int
    {
        $db = Database::getInstance();
        $result = $db->fetchOne(
            "SELECT COUNT(*) as total FROM content_articles WHERE site_id = ? AND status = 'published'",
            [$siteId]
        );
        return (int) ($result->total ?? 0);
    }

    public static function getCategories(int $articleId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT c.* FROM content_categories c
             JOIN content_article_category ac ON c.id = ac.category_id
             WHERE ac.article_id = ?",
            [$articleId]
        );
    }
}
