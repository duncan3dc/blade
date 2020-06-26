<?php

namespace duncan3dc\Laravel;

use Illuminate\Contracts\View\View as ViewInterface;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

use function is_dir;
use function method_exists;
use function mkdir;

/**
 * Standalone class for generating text using blade templates.
 */
class BladeInstance implements BladeInterface
{
    /**
     * @var string $path The default path for views.
     */
    private $path;

    /**
     * @var string $cache The default path for cached php.
     */
    private $cache;

    /**
     * @var DirectivesInterface $directives The custom directives to apply to this instance.
     */
    private $directives;

    /**
     * @var Factory|null $factory The internal cache of the Factory to only instantiate it once.
     */
    private $factory;

    /**
     * @var FileViewFinder|null $finder The internal cache of the FileViewFinder to only instantiate it once.
     */
    private $finder;

    /**
     * @var BladeCompiler|null The internal cache of the BladeCompiler to only instantiate it once.
     */
    private $compiler;

    /**
     * @var ConditionHandler|null $conditionHandler The custom conditionals that have been registered.
     */
    private $conditionHandler;


    /**
     * Create a new instance of the blade view factory.
     *
     * @param string $path The default path for views
     * @param string $cache The default path for cached php
     * @param DirectivesInterface $directives
     */
    public function __construct(string $path, string $cache, DirectivesInterface $directives = null)
    {
        $this->path = $path;
        $this->cache = $cache;

        if ($directives === null) {
            $directives = new Directives();
        }
        $this->directives = $directives;
    }


    /**
     * @return EngineResolver
     */
    private function getResolver(): EngineResolver
    {
        $resolver = new EngineResolver();

        $resolver->register("blade", function () {
            $blade = $this->getCompiler();
            return new CompilerEngine($blade);
        });

        $resolver->register("file", function () {
            return new FileEngine();
        });

        $resolver->register("php", function () {
            return new PhpEngine();
        });

        return $resolver;
    }


    /**
     * Get the laravel view finder.
     *
     * @return FileViewFinder
     */
    private function getViewFinder(): FileViewFinder
    {
        if (!$this->finder) {
            $this->finder = new FileViewFinder(new Filesystem(), [$this->path]);
        }

        return $this->finder;
    }


    /**
     * Get the laravel view factory.
     *
     * @return Factory
     */
    private function getViewFactory(): Factory
    {
        if ($this->factory) {
            return $this->factory;
        }

        $this->factory = new Factory($this->getResolver(), $this->getViewFinder(), new Dispatcher());

        return $this->factory;
    }


    /**
     * Get the internal compiler in use.
     *
     * @return BladeCompiler
     */
    private function getCompiler(): BladeCompiler
    {
        if ($this->compiler) {
            return $this->compiler;
        }

        if (!is_dir($this->cache)) {
            mkdir($this->cache, 0777, true);
        }

        $blade = new BladeCompiler(new Filesystem(), $this->cache);

        $this->directives->register($blade);

        $this->compiler = $blade;

        return $this->compiler;
    }


    /**
     * @inheritdoc
     */
    public function addExtension(string $extension): BladeInterface
    {
        $this
            ->getViewFactory()
            ->addExtension($extension, "blade");

        return $this;
    }


    /**
     * Register a custom Blade compiler.
     *
     * @param callable $compiler
     *
     * @return $this
     */
    public function extend(callable $compiler): BladeInterface
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
     * @return $this
     */
    public function directive(string $name, callable $handler): BladeInterface
    {
        $this
            ->getCompiler()
            ->directive($name, $handler);

        return $this;
    }


    /** @inheritDoc */
    public function aliasComponent(string $path, string $alias = null): BladeInterface
    {
        $compiler = $this->getCompiler();
        if (method_exists($compiler, "aliasComponent")) {
            $compiler->aliasComponent($path, $alias);
        } else {
            $compiler->component($path, $alias);
        }

        return $this;
    }


    /**
     * @deprecated Use aliasComponent()
     */
    public function component(string $path, string $alias = null): BladeInterface
    {
        $compiler = $this->getCompiler();
        if (method_exists($compiler, "aliasComponent")) {
            $compiler->aliasComponent($path, $alias);
        } else {
            $compiler->component($path, $alias);
        }

        return $this;
    }


    /**
     * Register an custom conditional directive.
     *
     * @param string $name
     * @param callable $handler
     *
     * @return $this
     */
    public function if(string $name, callable $handler): BladeInterface
    {
        if (!$this->conditionHandler) {
            $this->conditionHandler = new ConditionHandler();
            $this->share("_condition_handler", $this->conditionHandler);
        }

        $this->conditionHandler->add($name, $handler);

        $this->directive($name, function (string $expression) use ($name) {
            if ($expression === "") {
                $expression = "null";
            }
            return "<?php if (\$_condition_handler->check('{$name}', {$expression})): ?>";
        });

        $this->directive("end{$name}", function () {
            return "<?php endif; ?>";
        });

        return $this;
    }


    /**
     * Add a path to look for views in.
     *
     * @param string $path The path to look in
     *
     * @return $this
     */
    public function addPath(string $path): BladeInterface
    {
        $this->getViewFinder()->addLocation($path);

        return $this;
    }


    /**
     * Check if a view exists.
     *
     * @param string $view The name of the view to check
     *
     * @return bool
     */
    public function exists($view): bool
    {
        return $this->getViewFactory()->exists($view);
    }


    /**
     * Share data across all views.
     *
     * @param string $key The name of the variable to share
     * @param mixed $value The value to assign to the variable
     *
     * @return $this
     */
    public function share($key, $value = null): BladeInterface
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
     * @return array
     */
    public function composer($key, $value): array
    {
        return $this->getViewFactory()->composer($key, $value);
    }


    /**
     * Register a creator.
     *
     * @param string $key The name of the creator to register
     * @param mixed $value The closure or class to use
     *
     * @return array
     */
    public function creator($key, $value): array
    {
        return $this->getViewFactory()->creator($key, $value);
    }



    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace The namespace to use
     * @param array|string $hints The hints to apply
     *
     * @return $this
     */
    public function addNamespace($namespace, $hints): BladeInterface
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
    public function replaceNamespace($namespace, $hints): BladeInterface
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
     * @return ViewInterface The generated view
     */
    public function file($path, $data = [], $mergeData = []): ViewInterface
    {
        return $this->getViewFactory()->file($path, $data, $mergeData);
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
    public function make($view, $params = [], $mergeData = []): ViewInterface
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
    public function render(string $view, array $params = []): string
    {
        return $this->make($view, $params)->render();
    }
}
