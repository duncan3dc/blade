<?php

namespace duncan3dc\Laravel;

use Illuminate\Contracts\View\View as ViewInterface;

use function is_dir;
use function is_string;
use function realpath;

/**
 * Standalone class for generating text using blade templates.
 */
class Blade
{
    private static ?BladeInterface $instance = null;


    /**
     * Set the instance object to use.
     */
    public static function setInstance(BladeInterface $instance): void
    {
        self::$instance = $instance;
    }


    /**
     * Get the instance object.
     */
    public static function getInstance(): BladeInterface
    {
        if (!self::$instance) {
            # Calculate the parent of the vendor directory
            $path = realpath(__DIR__ . "/../../../..");
            if (!is_string($path) || !is_dir($path)) {
                throw new \RuntimeException("Unable to locate the root directory: {$path}");
            }

            self::$instance = new BladeInstance("{$path}/views", "{$path}/cache/views");
        }

        return self::$instance;
    }


    /**
     * Add another extension to use to search for template files.
     *
     * @param string $extension (eg 'blade.php', or 'template')
     */
    public static function addExtension(string $extension): void
    {
        static::getInstance()->addExtension($extension);
    }


    /**
     * Register a custom Blade compiler.
     */
    public static function extend(callable $compiler): void
    {
        static::getInstance()->extend($compiler);
    }


    /**
     * Register a handler for custom directives.
     */
    public static function directive(string $name, callable $handler): void
    {
        static::getInstance()->directive($name, $handler);
    }


    /**
     * Register a component alias directive.
     *
     * @param string $path Path to blade component e.g. `components.radio-input`.
     * @param string|null $alias Name of the component alias. By default the component filename will be used
     */
    public function aliasComponent(string $path, ?string $alias = null): void
    {
        static::getInstance()->aliasComponent($path, $alias);
    }


    /**
     * Register a class-based component alias directive.
     */
    public function component(string $class, ?string $alias = null, string $prefix = ""): void
    {
        static::getInstance()->component($class, $alias, $prefix);
    }


    /**
     * Register a custom conditional directive.
     */
    public static function if(string $name, callable $handler): void
    {
        static::getInstance()->if($name, $handler);
    }


    /**
     * Add a path to look for views in.
     */
    public static function addPath(string $path): void
    {
        static::getInstance()->addPath($path);
    }


    /**
     * Check if a view exists.
     *
     * @param string $view The name of the view to check
     *
     * @return bool
     */
    public static function exists(string $view): bool
    {
        return static::getInstance()->exists($view);
    }


    /**
     * Share data across all views.
     *
     * @param string $key The name of the variable to share
     * @param mixed $value The value to assign to the variable
     */
    public static function share($key, $value = null): void
    {
        static::getInstance()->share($key, $value);
    }


    /**
     * Register a composer.
     *
     * @param string $key The name of the composer to register
     * @param mixed $value The closure or class to use
     *
     * @return array
     */
    public static function composer(string $key, $value): array
    {
        return static::getInstance()->composer($key, $value);
    }


    /**
     * Register a creator.
     *
     * @param string $key The name of the creator to register
     * @param mixed $value The closure or class to use
     *
     * @return array
     */
    public static function creator(string $key, $value): array
    {
        return static::getInstance()->creator($key, $value);
    }


    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace The namespace to use
     * @param array|string $hints The hints to apply
     */
    public static function addNamespace(string $namespace, $hints): void
    {
        static::getInstance()->addNamespace($namespace, $hints);
    }


    /**
     * Replace the namespace hints for the given namespace.
     *
     * @param string $namespace The namespace to replace
     * @param array|string $hints The hints to use
     */
    public static function replaceNamespace(string $namespace, $hints): void
    {
        static::getInstance()->replaceNamespace($namespace, $hints);
    }


    /**
     * Get the evaluated view contents for the given path.
     *
     * @param string $path The path of the file to use
     * @param array $data The parameters to pass to the view
     * @param array $mergeData The extra data to merge
     *
     * @return ViewInterface The generated view
     */
    public static function file(string $path, array $data = [], array $mergeData = []): ViewInterface
    {
        return static::getInstance()->file($path, $data, $mergeData);
    }


    /**
     * Generate a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     * @param array $mergeData The extra data to merge
     *
     * @return ViewInterface The generated view
     */
    public static function make(string $view, array $params = [], array $mergeData = []): ViewInterface
    {
        return static::getInstance()->make($view, $params, $mergeData);
    }


    /**
     * Get the content by generating a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     *
     * @return string The generated content
     */
    public static function render(string $view, array $params = []): string
    {
        return static::getInstance()->render($view, $params);
    }
}
