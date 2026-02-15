<?php
defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<?php require __DIR__ . '/head.php'; ?>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php require get_theme_file_path('resources/views/partials/header.php'); ?>

<main id="main-content" class="site-main">
    <?php
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
    endif;
    ?>
</main>

<?php require get_theme_file_path('resources/views/partials/footer.php'); ?>

<?php wp_footer(); ?>
</body>
</html>
