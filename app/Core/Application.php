<?php
declare(strict_types=1);

namespace VGTech\Core;

use VGTech\Core\ServiceProvider;

final class Application
{
    /**
     * Singleton instance
     */
    private static ?self $instance = null;

    /**
     * Container bindings
     */
    protected array $container = [];

    /**
     * Service providers
     */
    protected array $providers = [];

    /**
     * Prevent direct construct
     */
    private function __construct()
    {
    }

    /**
     * Get application instance
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Boot application
     */
    public function boot(): void
    {
        $this->registerProviders();
        $this->bootProviders();
        $this->registerHooks();
    }

    /**
     * Load providers list
     */
    protected function registerProviders(): void
    {
        $providers = require get_template_directory() . '/bootstrap/providers.php';

        foreach ($providers as $providerClass) {
            if (class_exists($providerClass)) {
                $provider = new $providerClass($this);

                if ($provider instanceof ServiceProvider) {
                    $this->providers[] = $provider;
                    $provider->register();
                }
            }
        }
    }

    /**
     * Boot providers
     */
    protected function bootProviders(): void
    {
        foreach ($this->providers as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }
    }

    /**
     * Register core WordPress hooks
     */
    protected function registerHooks(): void
    {
        add_action('after_setup_theme', [$this, 'afterSetupTheme']);
        add_action('init', [$this, 'init']);
    }

    /**
     * after_setup_theme
     */
    public function afterSetupTheme(): void
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'search-form',
            'gallery',
            'caption',
        ]);
    }

    /**
     * init
     */
    public function init(): void
    {
        // dành cho Router, CPT, Taxonomy
    }

    /* ===========================
     * Container helpers
     * ===========================
     */

    public function bind(string $key, mixed $value): void
    {
        $this->container[$key] = $value;
    }

    public function make(string $key): mixed
    {
        return $this->container[$key] ?? null;
    }
}
