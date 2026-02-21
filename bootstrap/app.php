<?php
defined('ABSPATH') || exit;

use VGTech\Core\Application;

/**
 * Theme constants
 */
define('VGTECH_THEME_PATH', trailingslashit(get_stylesheet_directory()));
define('VGTECH_THEME_URL', trailingslashit(get_stylesheet_directory_uri()));

if (!defined('VGTECH_THEME_VERSION')) {
    $theme = wp_get_theme();
    define(
        'VGTECH_THEME_VERSION',
        ($theme && $theme->exists()) ? $theme->get('Version') : '1.0.0'
    );
}

/**
 * Composer autoload (required)
 */
$autoload = VGTECH_THEME_PATH . 'vendor/autoload.php';
if (!file_exists($autoload)) {
    wp_die(
        'Theme VGTECH requires Composer autoload. Please run composer install.',
        'Theme Error',
        ['response' => 500]
    );
}

require_once $autoload;

/**
 * Bootstrap Application
 */
$app = Application::getInstance();
$app->boot();

return $app;