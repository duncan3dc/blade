<?php

namespace duncan3dc\Laravel;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\View;

/**
 * Standalone class for generating text using blade templates.
 */
class Blade
{
    /**
     * @var BladeInstance $instance The internal cache of the BladeInstance to only instantiate it once
     */
    protected static $instance;


    /**
     * Set the BladeInstance object to use.
     *
     * @param BladeInstance $instance The instance to use
     *
     * @return void
     */
    public static function setInstance(BladeInstance $instance)
    {
        static::$instance = $instance;
    }


    /**
     * Get the BladeInstance object.
     *
     * @return BladeInstance
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            # Calculate the parent of the vendor directory
            $path = realpath(__DIR__ . "/../../../..");
            if (!is_dir($path)) {
                throw new \RuntimeException("Unable to locate the root directory: {$path}");
            }

            static::$instance = new BladeInstance("{$path}/views", "{$path}/cache/views");
        }

        return static::$instance;
    }


    /**
     * Allow all the methods of BladeInstance to be called.
     *
     * @param string $name The name of the method to run
     * @param array $arguments The parameters to pass to the method
     *
     * @return mixed
     */
    public static function __callStatic($name, array $arguments)
    {
        return static::getInstance()->$name(...$arguments);
    }


    /**
     * Add extra directives to the blade templating compiler.
     *
     * @param BladeCompiler $blade The compiler to extend
     *
     * @return void
     */
    public static function registerDirectives(BladeCompiler $blade)
    {
        $keywords = [
            "namespace",
            "use",
        ];
        foreach ($keywords as $keyword) {
            $blade->directive($keyword, function ($parameter) use ($keyword) {
                $parameter = trim($parameter, "()");
                return "<?php {$keyword} {$parameter} ?>";
            });
        }

        $assetify = function ($file, $type) {
            $file = trim($file, "()");

            if (in_array(substr($file, 0, 1), ["'", '"'], true)) {
                $file = trim($file, "'\"");
            } else {
                return "{{ {$file} }}";
            }

            if (substr($file, 0, 1) !== "/") {
                $file = "/{$type}/{$file}";
            }
            if (substr($file, (strlen($type) + 1) * -1) !== ".{$type}") {
                $file .= ".{$type}";
            }
            return $file;
        };

        $blade->directive("css", function ($parameter) use ($assetify) {
            $file = $assetify($parameter, "css");
            return "<link rel='stylesheet' type='text/css' href='{$file}'>";
        });

        $blade->directive("js", function ($parameter) use ($assetify) {
            $file = $assetify($parameter, "js");
            return "<script type='text/javascript' src='{$file}'></script>";
        });
    }
}
