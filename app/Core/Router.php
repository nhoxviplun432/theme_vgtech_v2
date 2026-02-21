<?php

namespace VGTech\Core;

defined('ABSPATH') || exit;

class Router
{
    /**
     * Đăng ký REST API
     *
     * @param string   $method   GET|POST|PUT|DELETE
     * @param string   $route    vd: ai/chat
     * @param callable $callback [Controller::class, 'method']
     * @param callable|null $permission
     */
    public static function rest(
        string $method,
        string $route,
        callable $callback,
        ?callable $permission = null
    ): void {
        add_action('rest_api_init', function () use ($method, $route, $callback, $permission) {
            register_rest_route('vgtech/v1', '/' . ltrim($route, '/'), [
                'methods'             => strtoupper($method),
                'callback'            => $callback,
                'permission_callback' => $permission ?? '__return_true',
            ]);
        });
    }

    /**
     * Đăng ký AJAX
     *
     * @param string   $action
     * @param callable $callback
     * @param bool     $public
     */
    public static function ajax(
        string $action,
        callable $callback,
        bool $public = false
    ): void {
        add_action("wp_ajax_{$action}", $callback);

        if ($public) {
            add_action("wp_ajax_nopriv_{$action}", $callback);
        }
    }

    public function handle(): void
    {
        if (is_admin()) {
            return;
        }

        if (is_front_page()) {
            app()->make('view')->render('pages/home');
            exit;
        }

        if (is_single()) {
            app()->make('view')->render('pages/single');
            exit;
        }

        if (is_archive()) {
            app()->make('view')->render('pages/archive');
            exit;
        }

        // fallback
        app()->make('view')->render('pages/404');
        exit;
    }
}
