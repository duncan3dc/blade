<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\Directives;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    private $blade;
    protected $directives;

    public function setUp()
    {
        $this->blade = new BladeCompiler(new Filesystem, "/tmp/phpunit/cache/views");

        $this->directives = new Directives;
    }

    public function assertTemplateString($expected, $template)
    {
        $this->directives->register($this->blade);

        $result = $this->blade->compileString($template);

        $this->assertSame($expected, $result);
    }
}
