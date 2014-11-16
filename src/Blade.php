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
class Blade
{
    /**
     * @var Factory $factory The internal cache of the Factory to only instantiate it once
     */
    protected static $factory;

    /**
     * @var FileViewFinder $finder The internal cache of the FileViewFinder to only instantiate it once
     */
    protected static $finder;

    /**
     * Get the laravel view factory.
     *
     * @return Factory
     */
    protected static function getViewFactory()
    {
        if (static::$factory) {
            return static::$factory;
        }

        $files = new Filesystem;

        static::$finder = new FileViewFinder($files, [Env::path("views")]);

        $resolver = new EngineResolver;
        $resolver->register("blade", function() use ($files) {
            $path = Env::path("cache/views");
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $blade = new BladeCompiler($files, $path);

            # Allow namespace declarations in views
            $blade->extend(function($view, $compiler) {
                $pattern = $compiler->createMatcher("namespace");
                return preg_replace_callback($pattern, function($matches) {
                    return $matches[1] . "<?php namespace " . substr($matches[2], 1, -1) . " ?>";
                }, $view);
            });

            # Allow use imports in views
            $blade->extend(function($view, $compiler) {
                $pattern = $compiler->createMatcher("use");
                return preg_replace_callback($pattern, function($matches) {
                    return $matches[1] . "<?php use " . substr($matches[2], 1, -1) . " ?>";
                }, $view);
            });

            return new CompilerEngine($blade, $files);
        });

        static::$factory = new Factory($resolver, static::$finder, new Dispatcher);

        return static::$factory;
    }


    /**
     * Add a path to look for views in.
     *
     * @param string $path The path to look in
     *
     * @return void
     */
    public static function addPath($path)
    {
        static::getViewFactory();
        static::$finder->addLocation($path);
    }


    /**
     * Check if a view exists.
     *
     * @param string $view The name of the view to check
     *
     * @return boolean
     */
    public static function exists($view)
    {
        return static::getViewFactory()->exists($view);
    }


    /**
     * Generate a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     *
     * @return View The generated view
     */
    public static function make($view, array $params = [])
    {
        return static::getViewFactory()->make($view, $params);
    }


    /**
     * Get the content by generating a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     *
     * @return string The generated content
     */
    public static function render($view, array $params = [])
    {
        return static::make($view, $params)->render();
    }
}
