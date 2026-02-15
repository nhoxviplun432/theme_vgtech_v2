<?php

namespace VGTech\Core;

defined('ABSPATH') || exit;

final class View
{
    /**
     * Base paths
     */
    protected string $viewsPath;
    protected string $componentsPath;

    /**
     * Global shared data for all views
     */
    protected array $shared = [];

    public function __construct(?string $viewsPath = null, ?string $componentsPath = null)
    {
        // resources/views + resources/components theo structure bạn mô tả
        $themePath = defined('VGTECH_THEME_PATH') ? VGTECH_THEME_PATH : trailingslashit(get_stylesheet_directory());

        $this->viewsPath      = $viewsPath      ? rtrim($viewsPath, '/\\') : $themePath . 'resources/views';
        $this->componentsPath = $componentsPath ? rtrim($componentsPath, '/\\') : $themePath . 'resources/components';
    }

    /**
     * Share global data for all views
     */
    public function share(string|array $key, mixed $value = null): self
    {
        if (is_array($key)) {
            $this->shared = array_merge($this->shared, $key);
            return $this;
        }

        $this->shared[$key] = $value;
        return $this;
    }

    /**
     * Render a view file from resources/views
     * Example: render('pages/home', ['title' => 'Hello'])
     */
    public function render(string $view, array $data = [], bool $echo = true): string
    {
        $file = $this->resolveViewPath($view);

        $payload = array_merge($this->shared, $data);

        $html = $this->includeWithData($file, $payload);

        if ($echo) {
            echo $html;
        }

        return $html;
    }

    /**
     * Render a partial (alias)
     * Example: partial('partials/header', ['menu' => $menu])
     */
    public function partial(string $view, array $data = [], bool $echo = true): string
    {
        return $this->render($view, $data, $echo);
    }

    /**
     * Render a component from resources/components
     * Example: component('card', ['title' => '...', 'content' => '...'])
     */
    public function component(string $name, array $data = [], bool $echo = true): string
    {
        $file = $this->resolveComponentPath($name);

        $payload = array_merge($this->shared, $data);

        $html = $this->includeWithData($file, $payload);

        if ($echo) {
            echo $html;
        }

        return $html;
    }

    /**
     * Helper: output asset url
     * - ưu tiên Vite build: /public/build (nếu bạn build ra đó)
     * - fallback: /public/assets
     */
    public function asset(string $path): string
    {
        $baseUrl = defined('VGTECH_THEME_URL')
            ? VGTECH_THEME_URL
            : trailingslashit(get_stylesheet_directory_uri());

        $path = ltrim($path, '/');

        // Bạn có thể đổi logic này theo cách build của bạn
        $buildCandidate = $baseUrl . 'public/build/' . $path;
        $fallback       = $baseUrl . 'public/assets/' . $path;

        // Không thể check file_exists với URL; check bằng file path
        $themePath = defined('VGTECH_THEME_PATH') ? VGTECH_THEME_PATH : trailingslashit(get_stylesheet_directory());
        $buildFile = $themePath . 'public/build/' . $path;

        return file_exists($buildFile) ? $buildCandidate : $fallback;
    }

    /**
     * Basic esc helper for views
     */
    public function e(mixed $value): string
    {
        return esc_html((string) $value);
    }

    /**
     * Basic attribute esc helper
     */
    public function ea(mixed $value): string
    {
        return esc_attr((string) $value);
    }

    // -------------------------
    // Internal
    // -------------------------

    protected function resolveViewPath(string $view): string
    {
        $view = $this->normalize($view);

        // allow passing full path
        if ($this->isAbsolutePath($view) && is_file($view)) {
            return $view;
        }

        $file = $this->viewsPath . '/' . $view . '.php';

        if (!is_file($file)) {
            // thử biến thể: nếu user truyền "home.php"
            if (str_ends_with($view, '.php') && is_file($this->viewsPath . '/' . $view)) {
                return $this->viewsPath . '/' . $view;
            }

            wp_die('View not found: ' . esc_html($file));
        }

        return $file;
    }

    protected function resolveComponentPath(string $name): string
    {
        $name = $this->normalize($name);

        if ($this->isAbsolutePath($name) && is_file($name)) {
            return $name;
        }

        $file = $this->componentsPath . '/' . $name . '.php';

        if (!is_file($file)) {
            if (str_ends_with($name, '.php') && is_file($this->componentsPath . '/' . $name)) {
                return $this->componentsPath . '/' . $name;
            }

            wp_die('Component not found: ' . esc_html($file));
        }

        return $file;
    }

    protected function includeWithData(string $file, array $data): string
    {
        // Tránh đè biến nhạy cảm
        $data = $this->sanitizeData($data);

        ob_start();
        try {
            extract($data, EXTR_SKIP);
            include $file;
        } finally {
            return (string) ob_get_clean();
        }
    }

    protected function sanitizeData(array $data): array
    {
        unset($data['GLOBALS'], $data['_SERVER'], $data['_ENV'], $data['_REQUEST'], $data['_POST'], $data['_GET'], $data['_COOKIE'], $data['_FILES']);
        return $data;
    }

    protected function normalize(string $path): string
    {
        $path = trim($path);
        $path = str_replace(['\\', '//'], ['/', '/'], $path);
        $path = ltrim($path, '/');
        return $path;
    }

    protected function isAbsolutePath(string $path): bool
    {
        // Windows: C:\...
        if (preg_match('/^[A-Z]:\\\\/i', $path)) {
            return true;
        }
        // Unix: /var/...
        return str_starts_with($path, '/');
    }
}
