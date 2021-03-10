<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\Directives;
use Illuminate\View\Compilers\BladeCompiler;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class DirectivesTest extends TestCase
{
    /** @var Directives */
    private $directives;

    /** @var BladeCompiler&MockInterface */
    private $compiler;


    protected function setUp(): void
    {
        $this->directives = (new Directives())
            ->withoutNamespace()
            ->withoutUse()
            ->withoutCss()
            ->withoutJs();

        $this->compiler = Mockery::mock(BladeCompiler::class);
    }


    protected function tearDown(): void
    {
        Mockery::close();
    }


    public function testDefaults(): void
    {
        $directives = new Directives();

        $this->compiler->shouldReceive("directive")->with("namespace", Mockery::any());
        $this->compiler->shouldReceive("directive")->with("use", Mockery::any());
        $this->compiler->shouldReceive("directive")->with("css", Mockery::any());
        $this->compiler->shouldReceive("directive")->with("js", Mockery::any());

        $directives->register($this->compiler);
        $this->assertTrue(true);
    }


    public function testWithNamespace(): void
    {
        $this->directives->withNamespace();

        $this->compiler->shouldReceive("directive")->with("namespace", Mockery::any());

        $this->directives->register($this->compiler);
        $this->assertTrue(true);
    }


    public function testWithUse(): void
    {
        $this->directives->withUse();

        $this->compiler->shouldReceive("directive")->with("use", Mockery::any());

        $this->directives->register($this->compiler);
        $this->assertTrue(true);
    }


    public function testWithCss(): void
    {
        $this->directives->withCss();

        $this->compiler->shouldReceive("directive")->with("css", Mockery::any());

        $this->directives->register($this->compiler);
        $this->assertTrue(true);
    }


    public function testWithJs(): void
    {
        $this->directives->withJs();

        $this->compiler->shouldReceive("directive")->with("js", Mockery::any());

        $this->directives->register($this->compiler);
        $this->assertTrue(true);
    }
}
