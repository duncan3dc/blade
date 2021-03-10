<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\BladeInstance;
use duncan3dc\Laravel\BladeInterface;
use duncan3dc\ObjectIntruder\Intruder;
use Illuminate\Contracts\View\View as ViewInterface;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class BladeMockTest extends TestCase
{
    /** @var BladeInterface */
    private $blade;

    /** @var FileViewFinder|MockInterface */
    private $finder;

    /** @var Factory|MockInterface */
    private $factory;


    protected function setUp(): void
    {
        $this->blade = new BladeInstance(__DIR__ . "/views", Utils::getCachePath());

        $intruder = new Intruder($this->blade);

        $this->finder = Mockery::mock(FileViewFinder::class);
        $intruder->finder = $this->finder;

        $this->factory = Mockery::mock(Factory::class);
        $intruder->factory = $this->factory;
    }


    public function testAddPath(): void
    {
        $this->finder->shouldReceive("addLocation")->once()->with("/tmp");
        $this->assertSame($this->blade, $this->blade->addPath("/tmp"));
    }


    public function testExists(): void
    {
        $this->factory->shouldReceive("exists")->once()->with("test-view")->andReturn(true);
        $this->assertTrue($this->blade->exists("test-view"));
    }


    public function testDoesntExist(): void
    {
        $this->factory->shouldReceive("exists")->once()->with("test-view")->andReturn(false);
        $this->assertFalse($this->blade->exists("test-view"));
    }


    public function testShare(): void
    {
        $this->factory->shouldReceive("share")->once()->with("site", "main");
        $this->assertSame($this->blade, $this->blade->share("site", "main"));
    }


    public function testComposer(): void
    {
        $this->factory->shouldReceive("composer")->once()->with("site", "main")->andReturn(["passthru"]);
        $this->assertSame(["passthru"], $this->blade->composer("site", "main"));
    }


    public function testCreator(): void
    {
        $this->factory->shouldReceive("creator")->once()->with("site", "main")->andReturn(["passthru"]);
        $this->assertSame(["passthru"], $this->blade->creator("site", "main"));
    }


    public function testAddNamespace(): void
    {
        $this->factory->shouldReceive("addNamespace")->once()->with("name", "hint");
        $this->assertSame($this->blade, $this->blade->addNamespace("name", "hint"));
    }


    public function testReplaceNamespace(): void
    {
        $this->factory->shouldReceive("replaceNamespace")->once()->with("name", "hint");
        $this->assertSame($this->blade, $this->blade->replaceNamespace("name", "hint"));
    }


    public function testFile(): void
    {
        $view = Mockery::mock(ViewInterface::class);
        $this->factory->shouldReceive("file")->once()->with("stuff", ["one" => 1], ["two" => 2])->andReturn($view);
        $this->assertSame($view, $this->blade->file("stuff", ["one" => 1], ["two" => 2]));
    }


    public function testMake(): void
    {
        $view = Mockery::mock(ViewInterface::class);
        $this->factory->shouldReceive("make")->once()->with("stuff", ["one" => 1], ["two" => 2])->andReturn($view);
        $this->assertSame($view, $this->blade->make("stuff", ["one" => 1], ["two" => 2]));
    }


    public function testRender(): void
    {
        $view = Mockery::mock(ViewInterface::class);
        $view->shouldReceive("render")->once()->with()->andReturn("content");

        $this->factory->shouldReceive("make")->once()->with("stuff", ["one" => 1], [])->andReturn($view);
        $this->assertSame("content", $this->blade->render("stuff", ["one" => 1]));
    }
}
