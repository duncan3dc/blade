<?php

namespace duncan3dc\Laravel;

use duncan3dc\Helpers\Env;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Environment;

/**
 * Standalone class for generating text using blade templates.
 */
class Blade
{
    /**
     * @var Environment $environment The internal cache of the Environment to only instantiate it once
     */
    protected static $environment;

    /**
     * Get the laravel environment object.
     *
     * @return Environment
     */
    protected static function getEnvironment()
    {
        if (static::$environment) {
            return static::$environment;
        }

        $container = new Container;

        $container->bindShared("files", function() {
            return new Filesystem;
        });

        $container->bindShared("events", function() {
            return new Dispatcher;
        });

        $container->bindShared("view.finder", function($app) {
            return new FileViewFinder($app->make("files"), [Env::path("views")]);
        });

        $container->bindShared("view.engine.resolver", function($app) use($container) {
            $container->bindShared("blade.compiler", function($app) {
                $path = Env::path("cache/views");
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $blade = new BladeCompiler($app->make("files"), $path);

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

                return $blade;
            });
            $resolver = new EngineResolver;
            $resolver->register("blade", function() use ($app) {
                return new CompilerEngine($app->make("blade.compiler"), $app->make("files"));
            });
            return $resolver;
        });

        static::$environment = new Environment($container->make("view.engine.resolver"), $container->make("view.finder"), $container->make("events"));
        static::$environment->setContainer($container);

        return static::$environment;
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
        static::getEnvironment()->getContainer()->make("view.finder")->addLocation($path);
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
        return static::getEnvironment()->exists($view);
    }


    /**
     * Generate a view.
     *
     * @param string $view The name of the view to make
     * @param array $params The parameters to pass to the view
     *
     * @return string The generated content
     */
    public static function make($view, array $params = [])
    {
        return static::getEnvironment()->make($view, $params);
    }
}
