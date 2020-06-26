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
    /**
     * @var BladeInstance|null $instance The internal cache of the BladeInstance to only instantiate it once
     */
    private static $instance;


    /**
     * Set the BladeInstance object to use.
     *
     * @param BladeInstance $instance The instance to use
     *
     * @return void
     */
    public static function setInstance(BladeInstance $instance): void
    {
        static::$instance = $instance;
    }


    /**
     * Get the BladeInstance object.
     *
     * @return BladeInterface
     */
    public static function getInstance(): BladeInterface
    {
        if (!static::$instance) {
            # Calculate the parent of the vendor directory
            $path = realpath(__DIR__ . "/../../../..");
            if (!is_string($path) || !is_dir($path)) {
                throw new \RuntimeException("Unable to locate the root directory: {$path}");
            }

            static::$instance = new BladeInstance("{$path}/views", "{$path}/cache/views");
        }

        return static::$instance;
    }


    /**
     * Add another extension to use to search for template files.
     *
     * @param string $extension (eg 'blade.php', or 'template')
     *
     * @return BladeInterface
     */
    public static function addExtension(string $extension): BladeInterface
    {
        return static::getInstance()->addExtension($extension);
    }


    /**
     * Register a custom Blade compiler.
     *
     * @param callable $compiler
     *
     * @return BladeInterface
     */
    public static function extend(callable $compiler): BladeInterface
    {
        return static::getInstance()->extend($compiler);
    }


    /**
     * Register a handler for custom directives.
     *
     * @param string $name
     * @param callable $handler
     *
     * @return BladeInterface
     */
    public static function directive(string $name, callable $handler): BladeInterface
    {
        return static::getInstance()->directive($name, $handler);
    }


    /**
     * Register a component alias directive.
     *
     * @param string $path Path to blade component e.g. `components.radio-input`.
     * @param string|null $alias Name of the component alias. By default the component filename will be used
     *
     * @return BladeInterface
     */
    public function aliasComponent(string $path, string $alias=null): BladeInterface
    {
        return static::getInstance()->aliasComponent($path, $alias);
    }


    /**
     * @deprecated Use aliasComponent()
     */
    public function component(string $path, string $alias=null): BladeInterface
    {
        return static::getInstance()->component($path, $alias);
    }


    /**
     * Register an custom conditional directive.
     *
     * @param string $name
     * @param callable $handler
     *
     * @return BladeInterface
     */
    public static function if(string $name, callable $handler): BladeInterface
    {
        return static::getInstance()->if($name, $handler);
    }


    /**
     * Add a path to look for views in.
     *
     * @param string $path The path to look in
     *
     * @return BladeInterface
     */
    public static function addPath(string $path): BladeInterface
    {
        return static::getInstance()->addPath($path);
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
     *
     * @return BladeInterface
     */
    public static function share(string $key, $value = null): BladeInterface
    {
        return static::getInstance()->share($key, $value);
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
     *
     * @return BladeInterface
     */
    public static function addNamespace(string $namespace, $hints): BladeInterface
    {
        return static::getInstance()->addNamespace($namespace, $hints);
    }


    /**
     * Replace the namespace hints for the given namespace.
     *
     * @param string $namespace The namespace to replace
     * @param array|string $hints The hints to use
     *
     * @return BladeInterface
     */
    public static function replaceNamespace(string $namespace, $hints): BladeInterface
    {
        return static::getInstance()->replaceNamespace($namespace, $hints);
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
