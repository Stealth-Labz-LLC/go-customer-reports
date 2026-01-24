<?php

namespace App\Models;

use App\Core\Database;

class Site
{
    public static function findByDomain(string $domain): ?object
    {
        $db = Database::getInstance();
        $site = $db->fetchOne(
            "SELECT * FROM content_sites WHERE domain = ? AND is_active = 1",
            [$domain]
        );

        if ($site && $site->config) {
            $site->config = json_decode($site->config, true);
        }
        if ($site && $site->meta_defaults) {
            $site->meta_defaults = json_decode($site->meta_defaults, true);
        }

        return $site;
    }

    public static function findById(int $id): ?object
    {
        $db = Database::getInstance();
        $site = $db->fetchOne(
            "SELECT * FROM content_sites WHERE id = ?",
            [$id]
        );

        if ($site && $site->config) {
            $site->config = json_decode($site->config, true);
        }

        return $site;
    }
}
