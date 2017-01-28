<?php

namespace duncan3dc\Laravel;

use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;

/**
 * Standalone class for generating text using blade templates.
 */
class BladeInstance implements FactoryContract
{
    /**
     * @var string $path The default path for views.
     */
    protected $path;

    /**
     * @var string $cache The default path for cached php.
     */
    protected $cache;

    /**
     * @var Factory $factory The internal cache of the Factory to only instantiate it once.
     */
    protected $factory;

    /**
     * @var FileViewFinder $finder The internal cache of the FileViewFinder to only instantiate it once.
     */
    protected $finder;


    /**
     * Create a new instance of the blade view factory.
     *
     * @param string $path The default path for views
     * @param string $cache The default path for cached php
     */
    public function __construct($path, $cache)
    {
        $this->path = $path;
        $this->cache = $cache;
    }

    /**
     * Get the laravel view finder.
     *
     * @return FileViewFinder
     */
    protected function getViewFinder()
    {
        if (!$this->finder) {
            $this->finder = new FileViewFinder(new Filesystem, [$this->path]);
        }

        return $this->finder;
    }


    /**
     * Get the laravel view factory.
     *
     * @return Factory
     */
    protected function getViewFactory()
    {
        if ($this->factory) {
            return $this->factory;
        }

        $resolver = new EngineResolver;
        $resolver->register("blade", function () {
            if (!is_dir($this->cache)) {
                mkdir($this->cache, 0777, true);
            }

            $blade = new BladeCompiler(new Filesystem, $this->cache);

            Blade::registerDirectives($blade);

            return new CompilerEngine($blade);
        });

        $this->factory = new Factory($resolver, $this->getViewFinder(), new Dispatcher);

        return $this->factory;
    }


    /**
     * Get the internal compiler in use.
     *
     * @return CompilerEngine
     */
    private function getCompiler()
    {
        return $this
            ->getViewFactory()
            ->getEngineResolver()
            ->resolve("blade")
            ->getCompiler();
    }


    /**
     * Register a custom Blade compiler.
     *
     * @param callable $compiler
     *
     * @return static
     */
    public function extend(callable $compiler)
    {
        $this
            ->getCompiler()
            ->extend($compiler);

        return $this;
    }


    /**
     * Register a handler for custom directives.
     *
     * @param string $name
     * @param callable $handler
     *
     * @return static
     */
    public function directive($name, callable $handler)
    {
        $this
            ->getCompiler()
            ->directive($name, $handler);

        return $this;
    }


    /**
     * Add a path to look for views in.
     *
     * @param string $path The path to look in
     *
     * @return static
     */
    public function addPath($path)
    {
        $this->getViewFinder()->addLocation($path);

        return $this;
    }


    /**
     * Check if a view exists.
     *
     * @param string $view The name of the view to check
     *
     * @return boolean
     */
    public function exists($view)
    {
        return $this->getViewFactory()->exists($view);
    }


    /**
     * Share data across all views.
     *
     * @param string $key The name of the variable to share
     * @param mixed $value The value to assign to the variable
     *
     * @return static
     */
    public function share($key, $value = null)
    {
        $this->getViewFactory()->share($key, $value);

        return $this;
    }


    /**
     * Register a composer.
     *
     * @param string $key The name of the composer to register
     * @param mixed $value The closure or class to use
     *
     * @return static
     */
    public function composer($key, $value, $priority = null)
    {
        $this->getViewFactory()->composer($key, $value, $priority);

        return $this;
    }


    /**
     * Register a creator.
     *
     * @param string $key The name of the creator to register
     * @param mixed $value The closure or class to use
     *
     * @return static
     */
    public function creator($key, $value)
    {
        $this->getViewFactory()->creator($key, $value);

        return $this;
    }



    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace The namespace to use
     * @param array|string $hints The hints to apply
     *
     * @return static
     */
    public function addNamespace($namespace, $hints)
    {
        $this->getViewFactory()->addNamespace($namespace, $hints);

        return $this;
    }


    /**
     * Replace the namespace hints for the given namespace.
     *
     * @param string $namespace The namespace to replace
     * @param array|string $hints The hints to use
     *
     * @return $this
     */
    public function replaceNamespace($namespace, $hints)
    {
        $this->getViewFactory()->replaceNamespace($namespace, $hints);

        return $this;
    }


    /**
     * Get the evaluated view contents for the given path.
     *
     * @param string $path The path of the file to use
     * @param array $data The parameters to pass to the view
     * @param array $mergeData The extra data to merge
     *
     * @return View The generated view
     */
    public function file($path, $data = [], $mergeData = [])
    {
        return $this->getViewFactory()->file($path, $data, $mergeData);
    }


    /**
     * Generate a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     *
     * @return View The generated view
     */
    public function make($view, $params = [], $mergeData = [])
    {
        return $this->getViewFactory()->make($view, $params, $mergeData);
    }


    /**
     * Get the content by generating a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     *
     * @return string The generated content
     */
    public function render($view, array $params = [])
    {
        return $this->make($view, $params)->render();
    }
}
