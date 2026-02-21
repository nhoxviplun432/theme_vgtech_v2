<?php

namespace VGTech\Providers;

use VGTech\Core\ServiceProvider;

class AdminLockServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        add_action('admin_menu', [$this, 'removeAdminMenus'], 999);
        add_action('admin_init', [$this, 'blockDirectAccess']);
        add_action('init', [$this, 'disablePageSupport']);
        add_action('admin_bar_menu', [$this, 'removeAdminBarItems'], 999);
    }

    public function removeAdminMenus(): void
    {
        remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('themes.php');              // Appearance (Menu + Customizer)
        remove_menu_page('nav-menus.php');           // Menu trực tiếp
    }

    public function removeAdminBarItems($wp_admin_bar): void
    {
        $wp_admin_bar->remove_node('new-page');
        $wp_admin_bar->remove_node('customize');
    }

    public function blockDirectAccess(): void
    {
        global $pagenow;
        var_dump($pagenow);

        if ($pagenow === 'post-new.php' && ($_GET['post_type'] ?? '') === 'page') {
            wp_die('Page creation is disabled.');
        }

        if ($pagenow === 'nav-menus.php') {
            wp_die('Menu management is disabled.');
        }

        if ($pagenow === 'customize.php') {
            wp_die('Customizer is disabled.');
        }
    }

    public function disablePageSupport(): void
    {
        remove_post_type_support('page', 'editor');
        remove_post_type_support('page', 'thumbnail');
        remove_post_type_support('page', 'custom-fields');
    }
}
