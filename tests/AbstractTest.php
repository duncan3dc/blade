<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\Blade;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
    protected $blade;

    public function setUp()
    {
        $this->blade = new BladeCompiler(new Filesystem, "/tmp/phpunit/cache/views");

        Blade::registerDirectives($this->blade);
    }

    public function assertTemplateString($expected, $template)
    {
        $result = $this->blade->compileString($template);

        $this->assertSame($expected, $result);
    }
}
