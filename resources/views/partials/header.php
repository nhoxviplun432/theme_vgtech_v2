<?php
defined('ABSPATH') || exit;
?>
<header id="site-header" class="site-header">
    <div class="container mx-auto flex justify-between items-center py-4">
        
        <div class="logo">
            <a href="<?= esc_url(home_url('/')); ?>" rel="home">
                <?= esc_html(get_bloginfo('name')); ?>
            </a>
        </div>

        <nav class="main-navigation" aria-label="Main Navigation">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex gap-6',
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>

    </div>
</header>
