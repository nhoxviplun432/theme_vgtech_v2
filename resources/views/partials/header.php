<?php
defined('ABSPATH') || exit;
?>

<header class="bg-white dark:bg-gray-900 border-b dark:border-gray-800">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center h-16">

      <div class="text-xl font-bold text-gray-900 dark:text-white">
        VGTECH
      </div>

      <nav class="hidden md:flex gap-8 text-gray-700 dark:text-gray-300">
        <a href="#" class="hover:text-black dark:hover:text-white">Trang chủ</a>
        <a href="#">Blog</a>
      </nav>

      <button id="theme-toggle"
        class="px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm">
        🌙
      </button>

    </div>
  </div>
</header>

<!-- HEADER MOBILE -->
<div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
    <div class="px-4 py-4 space-y-4">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'space-y-3 text-gray-700',
        ]);
        ?>

        <a href="<?= esc_url(home_url('/lien-he')); ?>"
           class="block text-center px-4 py-2 bg-black text-white rounded-lg">
            Liên hệ
        </a>
    </div>
</div>