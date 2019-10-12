<?php

namespace duncan3dc\Laravel;

use Illuminate\View\Compilers\BladeCompiler;

use function assert;
use function in_array;
use function is_string;
use function strlen;
use function substr;
use function trim;

class Directives implements DirectivesInterface
{
    /**
     * @var bool $namespace Whether to apply the namespace directive or not.
     */
    private $namespace = true;

    /**
     * @var bool $use Whether to apply the use directive or not.
     */
    private $use = true;

    /**
     * @var string|null $css The path to use for the css directive, or null to not apply the directive.
     */
    private $css = "css";

    /**
     * @var string|null $js The path to use for the javascript directive, or null to not apply the directive.
     */
    private $js = "js";


    /**
     * Shorthand function to clone the current instance with a modified parameter.
     *
     * @param string $parameter The name of the parameter to modify
     * @param mixed $value The value to set the parameter to
     *
     * @return self The new modified instance
     */
    private function clone(string $parameter, $value): self
    {
        $directives = clone $this;
        $directives->$parameter = $value;
        return $directives;
    }


    /**
     * Get a new instance with the namespace directive applied.
     *
     * @return self
     */
    public function withNamespace(): self
    {
        return $this->clone("namespace", true);
    }


    /**
     * Get a new instance without the namespace directive applied.
     *
     * @return self
     */
    public function withoutNamespace(): self
    {
        return $this->clone("namespace", false);
    }


    /**
     * Get a new instance with the use directive applied.
     *
     * @return self
     */
    public function withUse(): self
    {
        return $this->clone("use", true);
    }


    /**
     * Get a new instance without the use directive applied.
     *
     * @return self
     */
    public function withoutUse(): self
    {
        return $this->clone("use", false);
    }


    /**
     * Get a new instance with the css directive applied.
     *
     * @param string $path The default path to the css files
     *
     * @return self
     */
    public function withCss(string $path = "css"): self
    {
        return $this->clone("css", $path);
    }


    /**
     * Get a new instance without the css directive applied.
     *
     * @return self
     */
    public function withoutCss(): self
    {
        return $this->clone("css", null);
    }


    /**
     * Get a new instance with the javascript directive applied.
     *
     * @param string $path The default path to the javascript files
     *
     * @return self
     */
    public function withJs(string $path = "js"): self
    {
        return $this->clone("js", $path);
    }


    /**
     * Get a new instance without the javascript directive applied.
     *
     * @return self
     */
    public function withoutJs(): self
    {
        return $this->clone("js", null);
    }


    /**
     * Register all the active directives to the blade templating compiler.
     *
     * @param BladeCompiler $blade The compiler to extend
     *
     * @return void
     */
    public function register(BladeCompiler $blade): void
    {
        if ($this->namespace) {
            $blade->directive("namespace", function ($parameter) {
                return "<?php namespace {$parameter} ?>";
            });
        }

        if ($this->use) {
            $blade->directive("use", function ($parameter) {
                return "<?php use {$parameter} ?>";
            });
        }

        if ($this->css !== null) {
            $blade->directive("css", function ($parameter) {
                assert(is_string($this->css));
                $file = $this->assetify($parameter, "css", $this->css);
                return "<link rel='stylesheet' type='text/css' href='{$file}'>";
            });
        }

        if ($this->js !== null) {
            $blade->directive("js", function ($parameter) {
                assert(is_string($this->js));
                $file = $this->assetify($parameter, "js", $this->js);
                return "<script type='text/javascript' src='{$file}'></script>";
            });
        }
    }


    /**
     * Convert a simple name into a full asset path.
     *
     * @param string $file The simple file name
     * @param string $type The type of asset (css/js)
     * @param string $path The path the asset is stored at
     *
     * @return string The full path to the asset
     */
    private function assetify(string $file, string $type, string $path): string
    {
        if (in_array(substr($file, 0, 1), ["'", '"'], true)) {
            $file = trim($file, "'\"");
        } else {
            return "{{ {$file} }}";
        }

        if (substr($file, 0, 8) === "https://") {
            return $file;
        }

        if (substr($file, 0, 7) === "http://") {
            return $file;
        }

        if (substr($file, 0, 1) !== "/") {
            $path = trim($path, "/");
            if (strlen($path) > 0) {
                $path = "/{$path}/";
            } else {
                $path = "/";
            }
            $file = $path . $file;
        }

        if (substr($file, (strlen($type) + 1) * -1) !== ".{$type}") {
            $file .= ".{$type}";
        }

        return $file;
    }
}
