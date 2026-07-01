<?php

namespace duncan3dc\Laravel;

use Illuminate\Container\Container;
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

use function assert;
use function is_dir;
use function mkdir;

/**
 * Standalone class for generating text using blade templates.
 */
final class BladeInstance implements BladeInterface
{
    /**
     * @var string $path The default path for views.
     */
    private string $path;

    /**
     * @var string $cache The default path for cached php.
     */
    private string $cache;

    /**
     * @var DirectivesInterface $directives The custom directives to apply to this instance.
     */
    private DirectivesInterface $directives;

    /**
     * @var Factory|null $factory The internal cache of the Factory to only instantiate it once.
     */
    private ?Factory $factory = null;

    /**
     * @var FileViewFinder|null $finder The internal cache of the FileViewFinder to only instantiate it once.
     */
    private ?FileViewFinder $finder = null;

    /**
     * @var BladeCompiler|null The internal cache of the BladeCompiler to only instantiate it once.
     */
    private ?BladeCompiler $compiler = null;

    /**
     * @var ConditionHandler|null $conditionHandler The custom conditionals that have been registered.
     */
    private ?ConditionHandler $conditionHandler = null;


    /**
     * @param array<string> $paths
     */
    public static function fromPaths(array $paths, string $cache, ?DirectivesInterface $directives = null): BladeInterface
    {
        $instance = null;
        foreach ($paths as $path) {
            if ($instance === null) {
                $instance = new self($path, $cache, $directives);
                continue;
            }
            $instance->addPath($path);
        }
        if ($instance === null) {
            throw new \InvalidArgumentException("You must provide at least one view template path");
        }
        return $instance;
    }


    /**
     * Create a new instance of the blade view factory.
     *
     * @param string $path The default path for views
     * @param string $cache The default path for cached php
     */
    public function __construct(string $path, string $cache, ?DirectivesInterface $directives = null)
    {
        $this->path = $path;
        $this->cache = $cache;

        if ($directives === null) {
            $directives = new Directives();
        }
        $this->directives = $directives;
    }


    private function getResolver(): EngineResolver
    {
        $resolver = new EngineResolver();

        $resolver->register("blade", function () {
            $blade = $this->getCompiler();
            return new CompilerEngine($blade);
        });

        $resolver->register("file", function () {
            return new FileEngine(new Filesystem());
        });

        $resolver->register("php", function () {
            return new PhpEngine(new Filesystem());
        });

        return $resolver;
    }


    /**
     * Get the laravel view finder.
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
     */
    private function getViewFactory(): Factory
    {
        if ($this->factory) {
            return $this->factory;
        }

        $this->factory = new Factory($this->getResolver(), $this->getViewFinder(), new Dispatcher());

        # Unfortunately the component() method pulls from the global container state, so we need to register our view factory there
        $container = Container::getInstance();
        $container->instance(\Illuminate\Contracts\View\Factory::class, $this->factory);
        $container->instance("view", $this->factory);

        return $this->factory;
    }


    /**
     * Get the internal compiler in use.
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


    public function addExtension(string $extension): BladeInterface
    {
        $this
            ->getViewFactory()
            ->addExtension($extension, "blade");

        return $this;
    }


    public function extend(callable $compiler): BladeInterface
    {
        $this
            ->getCompiler()
            ->extend($compiler);

        return $this;
    }


    public function directive(string $name, callable $handler): BladeInterface
    {
        $this
            ->getCompiler()
            ->directive($name, $handler);

        return $this;
    }


    public function aliasComponent(string $path, ?string $alias = null): BladeInterface
    {
        $this
            ->getCompiler()
            ->aliasComponent($path, $alias);
        return $this;
    }


    public function component(string $class, ?string $alias = null, string $prefix = ""): BladeInterface
    {
        $this
            ->getCompiler()
            ->component($class, $alias, $prefix);
        return $this;
    }


    public function if(string $name, callable $handler): BladeInterface
    {
        if (!$this->conditionHandler) {
            $this->conditionHandler = new ConditionHandler();
            $this->share("_condition_handler", $this->conditionHandler);
        }
        assert($this->conditionHandler instanceof ConditionHandler);

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


    public function addPath(string $path): BladeInterface
    {
        $this->getViewFinder()->addLocation($path);

        return $this;
    }


    public function exists($view): bool
    {
        return $this->getViewFactory()->exists($view);
    }


    public function share($key, $value = null): BladeInterface
    {
        $this->getViewFactory()->share($key, $value);

        return $this;
    }


    public function composer($views, $callback): array
    {
        return $this->getViewFactory()->composer($views, $callback);
    }


    public function creator($views, $callback): array
    {
        return $this->getViewFactory()->creator($views, $callback);
    }


    public function addNamespace($namespace, $hints): BladeInterface
    {
        $this->getViewFactory()->addNamespace($namespace, $hints);
        return $this;
    }


    public function replaceNamespace($namespace, $hints): BladeInterface
    {
        $this->getViewFactory()->replaceNamespace($namespace, $hints);
        return $this;
    }


    public function file($path, $data = [], $mergeData = []): ViewInterface
    {
        return $this->getViewFactory()->file($path, $data, $mergeData);
    }


    public function make($view, $data = [], $mergeData = []): ViewInterface
    {
        return $this->getViewFactory()->make($view, $data, $mergeData);
    }


    public function render(string $view, array $data = []): string
    {
        return $this->make($view, $data)->render();
    }
}
