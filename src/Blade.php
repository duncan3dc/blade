<?php

namespace duncan3dc\Laravel;

use Illuminate\View\Compilers\CompilerInterface;
use function is_dir;
use function realpath;

/**
 * Standalone class for generating text using blade templates.
 */
class Blade
{
    /**
     * @var BladeInstance $instance The internal cache of the BladeInstance to only instantiate it once
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
    public static function __callStatic(string $name, array $arguments)
    {
        return static::getInstance()->$name(...$arguments);
    }


    /**
     * Add extra directives to the blade templating compiler.
     *
     * @param CompilerInterface $blade The compiler to extend
     *
     * @return void
     */
    public static function registerDirectives(CompilerInterface $blade): void
    {
        \trigger_error('Blade::registerDirectives() is deprecated in favour of using the Directives class', \E_USER_DEPRECATED);

        $directives = new Directives;
        $directives->register($blade);
    }
}
