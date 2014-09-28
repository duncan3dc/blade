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

class Blade
{
    protected static $environment;

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
                return new BladeCompiler($app->make("files"), $path);
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


    public static function make($view, array $params = [])
    {
        return static::getEnvironment()->make($view, $params);
    }
}
