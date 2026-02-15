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
        add_action('after_setup_theme', function () {

            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');

            register_nav_menus([
                'primary' => __('Primary Menu', 'vgtech'),
                'footer'  => __('Footer Menu', 'vgtech'),
            ]);
        });
    }
}
