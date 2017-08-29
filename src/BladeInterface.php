<?php

namespace duncan3dc\Laravel;

use Illuminate\Contracts\View\Factory as FactoryInterface;
use Illuminate\Contracts\View\View as ViewInterface;

/**
 * Standalone class for generating text using blade templates.
 */
interface BladeInterface extends FactoryInterface
{
    /**
     * Register a custom Blade compiler.
     *
     * @param callable $compiler
     *
     * @return $this
     */
    public function extend(callable $compiler): BladeInterface;


    /**
     * Register a handler for custom directives.
     *
     * @param string $name
     * @param callable $handler
     *
     * @return $this
     */
    public function directive(string $name, callable $handler): BladeInterface;


    /**
     * Register an custom conditional directive.
     *
     * @param string $name
     * @param callable $handler
     *
     * @return $this
     */
    public function if(string $name, callable $handler): BladeInterface;


    /**
     * Add a path to look for views in.
     *
     * @param string $path The path to look in
     *
     * @return $this
     */
    public function addPath(string $path): BladeInterface;


    /**
     * Check if a view exists.
     *
     * @param string $view The name of the view to check
     *
     * @return bool
     */
    public function exists($view): bool;


    /**
     * Share data across all views.
     *
     * @param string $key The name of the variable to share
     * @param mixed $value The value to assign to the variable
     *
     * @return $this
     */
    public function share($key, $value = null): BladeInterface;


    /**
     * Register a composer.
     *
     * @param string $key The name of the composer to register
     * @param mixed $value The closure or class to use
     *
     * @return $this
     */
    public function composer($key, $value): BladeInterface;


    /**
     * Register a creator.
     *
     * @param string $key The name of the creator to register
     * @param mixed $value The closure or class to use
     *
     * @return $this
     */
    public function creator($key, $value): BladeInterface;


    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace The namespace to use
     * @param array|string $hints The hints to apply
     *
     * @return $this
     */
    public function addNamespace($namespace, $hints): BladeInterface;


    /**
     * Replace the namespace hints for the given namespace.
     *
     * @param string $namespace The namespace to replace
     * @param array|string $hints The hints to use
     *
     * @return $this
     */
    public function replaceNamespace($namespace, $hints): BladeInterface;


    /**
     * Get the evaluated view contents for the given path.
     *
     * @param string $path The path of the file to use
     * @param array $data The parameters to pass to the view
     * @param array $mergeData The extra data to merge
     *
     * @return ViewInterface The generated view
     */
    public function file($path, $data = [], $mergeData = []): ViewInterface;


    /**
     * Generate a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     * @param array $mergeData The extra data to merge
     *
     * @return ViewInterface The generated view
     */
    public function make($view, $params = [], $mergeData = []): ViewInterface;


    /**
     * Get the content by generating a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     *
     * @return string The generated content
     */
    public function render(string $view, array $params = []): string;
}
