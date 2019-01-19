<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\Directives;
use Illuminate\View\Compilers\CompilerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class DirectivesTest extends TestCase
{
    private $directives;

    public function setUp()
    {
        $this->directives = (new Directives())
            ->withoutNamespace()
            ->withoutUse()
            ->withoutCss()
            ->withoutJs();

        $this->compiler = Mockery::mock(CompilerInterface::class);
    }


    public function tearDown()
    {
        Mockery::close();
    }


    public function testDefaults()
    {
        $directives = new Directives();

        $this->compiler->shouldReceive("directive")->with("namespace", Mockery::any());
        $this->compiler->shouldReceive("directive")->with("use", Mockery::any());
        $this->compiler->shouldReceive("directive")->with("css", Mockery::any());
        $this->compiler->shouldReceive("directive")->with("js", Mockery::any());

        $directives->register($this->compiler);
        $this->assertTrue(true);
    }


    public function testWithNamespace()
    {
        $this->directives->withNamespace();

        $this->compiler->shouldReceive("directive")->with("namespace", Mockery::any());

        $this->directives->register($this->compiler);
        $this->assertTrue(true);
    }


    public function testWithUse()
    {
        $this->directives->withUse();

        $this->compiler->shouldReceive("directive")->with("use", Mockery::any());

        $this->directives->register($this->compiler);
        $this->assertTrue(true);
    }


    public function testWithCss()
    {
        $this->directives->withCss();

        $this->compiler->shouldReceive("directive")->with("css", Mockery::any());

        $this->directives->register($this->compiler);
        $this->assertTrue(true);
    }


    public function testWithJs()
    {
        $this->directives->withJs();

        $this->compiler->shouldReceive("directive")->with("js", Mockery::any());

        $this->directives->register($this->compiler);
        $this->assertTrue(true);
    }
}
