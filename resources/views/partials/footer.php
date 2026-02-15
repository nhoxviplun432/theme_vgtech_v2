<?php
defined('ABSPATH') || exit;
?>
<footer id="footer" class="site-footer bg-gray-900 text-white py-8">
    <div class="container mx-auto text-center">

        <p>
            &copy; <?= date('Y'); ?>
            <?= esc_html(get_bloginfo('name')); ?>.
            All rights reserved.
        </p>

        <nav class="footer-navigation mt-4">
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container'      => false,
                'menu_class'     => 'flex justify-center gap-4',
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>

    </div>
</footer>
