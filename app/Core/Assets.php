<?php

namespace VGTech\Core;

defined('ABSPATH') || exit;

final class Assets
{
    protected string $themeUrl;
    protected string $themePath;
    protected string $version;

    public function __construct()
    {
        $this->themeUrl  = defined('VGTECH_THEME_URL')
            ? VGTECH_THEME_URL
            : trailingslashit(get_stylesheet_directory_uri());

        $this->themePath = defined('VGTECH_THEME_PATH')
            ? VGTECH_THEME_PATH
            : trailingslashit(get_stylesheet_directory());

        $this->version   = defined('VGTECH_THEME_VERSION')
            ? VGTECH_THEME_VERSION
            : '1.0.0';
    }

    /**
     * Register hooks
     */
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontend']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin']);
        add_filter('script_loader_tag', [$this, 'handleScriptAttributes'], 10, 3);
    }

    /**
     * Frontend assets
     */
    public function enqueueFrontend(): void
    {
        // CSS
        $this->enqueueStyle(
            'vgtech-style',
            $this->asset('css/app.css')
        );

        // JS
        $this->enqueueScript(
            'vgtech-app',
            $this->asset('js/app.js'),
            ['jquery'],
            true,
            [
                'defer' => true,
            ]
        );

        // Localize
        wp_localize_script('vgtech-app', 'VGTECH', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('vgtech_nonce'),
            'siteUrl' => home_url('/'),
        ]);
    }

    /**
     * Admin assets
     */
    public function enqueueAdmin(): void
    {
        $this->enqueueStyle(
            'vgtech-admin',
            $this->asset('css/admin.css')
        );

        $this->enqueueScript(
            'vgtech-admin',
            $this->asset('js/admin.js'),
            ['jquery'],
            true
        );
    }

    /**
     * Enqueue style helper
     */
    protected function enqueueStyle(
        string $handle,
        string $src,
        array $deps = [],
        ?string $ver = null,
        string $media = 'all'
    ): void {
        wp_enqueue_style(
            $handle,
            $src,
            $deps,
            $ver ?? $this->version,
            $media
        );
    }

    /**
     * Enqueue script helper
     */
    protected function enqueueScript(
        string $handle,
        string $src,
        array $deps = [],
        bool $inFooter = true,
        array $attrs = [],
        ?string $ver = null
    ): void {
        wp_enqueue_script(
            $handle,
            $src,
            $deps,
            $ver ?? $this->version,
            $inFooter
        );

        if (!empty($attrs)) {
            wp_script_add_data($handle, 'vgtech_attrs', $attrs);
        }
    }

    /**
     * Add async / defer attributes
     */
    public function handleScriptAttributes(string $tag, string $handle, string $src): string
    {
        $script = wp_scripts()->registered[$handle] ?? null;
        if (!$script) {
            return $tag;
        }

        $attrs = $script->extra['vgtech_attrs'] ?? [];

        foreach (['async', 'defer'] as $attr) {
            if (!empty($attrs[$attr])) {
                if (!str_contains($tag, $attr)) {
                    $tag = str_replace(
                        '<script ',
                        '<script ' . $attr . ' ',
                        $tag
                    );
                }
            }
        }

        return $tag;
    }

    /**
     * Resolve asset path
     * Ưu tiên build (Vite / Webpack) → fallback assets
     */
    protected function asset(string $path): string
    {
        $path = ltrim($path, '/');

        // build/
        $buildFile = $this->themePath . 'public/build/' . $path;
        if (file_exists($buildFile)) {
            return $this->themeUrl . 'public/build/' . $path;
        }

        // assets/
        return $this->themeUrl . 'public/assets/' . $path;
    }
}
