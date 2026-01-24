<?php
/**
 * Application Bootstrap
 * Loads all core classes and models
 */

define('ROOT_PATH', dirname(__DIR__));

// Config
if (file_exists(ROOT_PATH . '/config/environment.php')) {
    require_once ROOT_PATH . '/config/environment.php';
}

// Core
require_once ROOT_PATH . '/app/Core/Database.php';
require_once ROOT_PATH . '/app/Core/Router.php';
require_once ROOT_PATH . '/app/Core/Security.php';
require_once ROOT_PATH . '/app/Core/LeadStorage.php';
require_once ROOT_PATH . '/app/Core/Logger.php';

// Models
require_once ROOT_PATH . '/app/Models/Site.php';
require_once ROOT_PATH . '/app/Models/Article.php';
require_once ROOT_PATH . '/app/Models/Review.php';
require_once ROOT_PATH . '/app/Models/Listicle.php';
require_once ROOT_PATH . '/app/Models/Category.php';
require_once ROOT_PATH . '/app/Models/Page.php';
