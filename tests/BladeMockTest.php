<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\BladeInstance;
use Mockery;

class BladeMockTest extends \PHPUnit_Framework_TestCase
{
    protected $blade;
    protected $finder;
    protected $factory;

    public function setUp()
    {
        $this->blade = new BladeInstance(__DIR__ . "/views", getCachePath());

        $class = new \ReflectionClass($this->blade);

        $this->finder = Mockery::mock(FileViewFinder::class);
        $property = $class->getProperty("finder");
        $property->setAccessible(true);
        $property->setValue($this->blade, $this->finder);

        $this->factory = Mockery::mock(FileViewFinder::class);
        $property = $class->getProperty("factory");
        $property->setAccessible(true);
        $property->setValue($this->blade, $this->factory);
    }


    public function testAddPath()
    {
        $this->finder->shouldReceive("addLocation")->once()->with("/tmp");
        $this->assertSame($this->blade, $this->blade->addPath("/tmp"));
    }


    public function testExists()
    {
        $this->factory->shouldReceive("exists")->once()->with("test-view")->andReturn(true);
        $this->assertTrue($this->blade->exists("test-view"));
    }


    public function testDoesntExist()
    {
        $this->factory->shouldReceive("exists")->once()->with("test-view")->andReturn(false);
        $this->assertFalse($this->blade->exists("test-view"));
    }


    public function testShare()
    {
        $this->factory->shouldReceive("share")->once()->with("site", "main");
        $this->assertSame($this->blade, $this->blade->share("site", "main"));
    }


    public function testComposer()
    {
        $this->factory->shouldReceive("composer")->once()->with("site", "main", 4);
        $this->assertSame($this->blade, $this->blade->composer("site", "main", 4));
    }


    public function testCreator()
    {
        $this->factory->shouldReceive("creator")->once()->with("site", "main");
        $this->assertSame($this->blade, $this->blade->creator("site", "main"));
    }


    public function testAddNamespace()
    {
        $this->factory->shouldReceive("addNamespace")->once()->with("name", "hint");
        $this->assertSame($this->blade, $this->blade->addNamespace("name", "hint"));
    }


    public function testFile()
    {
        $this->factory->shouldReceive("file")->once()->with("stuff", ["one" => 1], ["two" => 2])->andReturn("file");
        $this->assertSame("file", $this->blade->file("stuff", ["one" => 1], ["two" => 2]));
    }


    public function testMake()
    {
        $this->factory->shouldReceive("make")->once()->with("stuff", ["one" => 1], ["two" => 2])->andReturn("view");
        $this->assertSame("view", $this->blade->make("stuff", ["one" => 1], ["two" => 2]));
    }


    public function testRender()
    {
        $view = Mockery::mock(View::class);
        $view->shouldReceive("render")->once()->with()->andReturn("content");

        $this->factory->shouldReceive("make")->once()->with("stuff", ["one" => 1], [])->andReturn($view);
        $this->assertSame("content", $this->blade->render("stuff", ["one" => 1]));
    }
}
