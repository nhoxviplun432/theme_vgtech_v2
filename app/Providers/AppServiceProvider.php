<?php
declare(strict_types=1);

namespace VGTech\Providers;

use VGTech\Core\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');

        register_nav_menus([
            'primary' => __('Primary Menu', 'vgtech'),
            'footer'  => __('Footer Menu', 'vgtech'),
        ]);

        // Tắt block patterns
        remove_theme_support('core-block-patterns');

        // Tắt remote patterns từ WP.org
        add_filter('should_load_remote_block_patterns', '__return_false');
        add_action('admin_menu', function () {
            remove_submenu_page('themes.php', 'site-editor.php');
            remove_submenu_page('themes.php', 'gutenberg-edit-site');
            remove_submenu_page('themes.php', 'edit.php?post_type=wp_block');
        }, 999);
    }

}
