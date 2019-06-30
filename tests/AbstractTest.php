<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\Directives;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    /** @var BladeCompiler */
    private $blade;

    /** @var Directives */
    protected $directives;


    public function setUp(): void
    {
        $this->blade = new BladeCompiler(new Filesystem(), "/tmp/phpunit/cache/views");

        $this->directives = new Directives();
    }


    public function assertTemplateString(string $expected, string $template): void
    {
        $this->directives->register($this->blade);

        $result = $this->blade->compileString($template);

        $this->assertSame($expected, $result);
    }
}
