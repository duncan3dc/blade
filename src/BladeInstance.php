<?php

namespace duncan3dc\Laravel;

use duncan3dc\Helpers\Env;
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
class BladeInstance
{
    /**
     * @var Factory $factory The internal cache of the Factory to only instantiate it once
     */
    protected $factory;

    /**
     * @var FileViewFinder $finder The internal cache of the FileViewFinder to only instantiate it once
     */
    protected $finder;

    /**
     * Get the laravel view finder.
     *
     * @return FileViewFinder
     */
    protected function getViewFinder()
    {
        if (!$this->finder) {
            $this->finder = new FileViewFinder(new Filesystem, [Env::path("views")]);
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
        $resolver->register("blade", function() {
            $path = Env::path("cache/views");
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $blade = new BladeCompiler(new Filesystem, $path);

            Blade::extendBlade($blade);

            return new CompilerEngine($blade);
        });

        $this->factory = new Factory($resolver, $this->getViewFinder(), new Dispatcher);

        return $this->factory;
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
     * Generate a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     *
     * @return View The generated view
     */
    public function make($view, array $params = [])
    {
        return $this->getViewFactory()->make($view, $params);
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
