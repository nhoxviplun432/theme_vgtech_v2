<?php
defined('ABSPATH') || exit;

global $post;
?>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (is_singular() && has_post_thumbnail()) : ?>
        <meta property="og:image" content="<?= esc_url(get_the_post_thumbnail_url($post, 'full')); ?>">
    <?php endif; ?>

    <meta name="description" content="<?= esc_attr(get_bloginfo('description')); ?>">
    <meta name="author" content="<?= esc_attr(get_bloginfo('name')); ?>">

    <meta property="og:title" content="<?= esc_attr(wp_get_document_title()); ?>">
    <meta property="og:description" content="<?= esc_attr(get_bloginfo('description')); ?>">
    <meta property="og:type" content="<?= is_singular() ? 'article' : 'website'; ?>">
    <meta property="og:url" content="<?= esc_url(home_url(add_query_arg([], $_SERVER['REQUEST_URI']))); ?>">
    <meta property="og:site_name" content="<?= esc_attr(get_bloginfo('name')); ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc_attr(wp_get_document_title()); ?>">
    <meta name="twitter:description" content="<?= esc_attr(get_bloginfo('description')); ?>">

    <link rel="canonical" href="<?= esc_url(home_url(add_query_arg([], $_SERVER['REQUEST_URI']))); ?>">

    <?php wp_head(); ?>
</head>
