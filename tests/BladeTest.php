<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;
use Illuminate\Contracts\View\View;
use Mockery;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function str_replace;
use function trim;

class BladeTest extends TestCase
{
    public function setUp(): void
    {
        $blade = new BladeInstance(__DIR__ . "/views", Utils::getCachePath());
        Blade::setInstance($blade);
    }


    public function testAddExtension1(): void
    {
        Blade::addExtension("custom");
        $expected = file_get_contents(__DIR__ . "/views/view16.html");
        $result = Blade::render("view16", ["title" => "Test Title"]);
        $this->assertSame($expected, $result);
    }


    public function testBasicMake(): void
    {
        $result = Blade::make("view1")->render();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testBasicRender(): void
    {
        $result = Blade::render("view1");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testParametersMake(): void
    {
        $result = Blade::make("view2", ["title" => "Test Title"])->render();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testParametersRender(): void
    {
        $result = Blade::render("view2", ["title" => "Test Title"]);
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testAltPath(): void
    {
        Blade::addPath(__DIR__ . "/views/alt");
        $result = Blade::render("view3");
        $this->assertSame(file_get_contents(__DIR__ . "/views/alt/view3.blade.php"), $result);
    }


    public function testNamespace(): void
    {
        $result = Blade::render("view4");
        $this->assertSame("duncan3dc\\Laravel", trim($result));
    }


    public function testUse(): void
    {
        $result = Blade::render("view5");
        $this->assertSame("stuff", trim($result));
    }


    public function testRawOutput(): void
    {
        $result = Blade::render("view6");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view6.html"), $result);
    }


    public function testEscapedOutput(): void
    {
        $result = Blade::render("view7");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view7.html"), $result);
    }


    public function testShare(): void
    {
        Blade::share("shareData", "shared");
        $result = Blade::render("view8");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view8.html"), $result);
    }


    public function testComposer(): void
    {
        Blade::composer("*", function (View $view) {
            $view->with("items", ["One", "Two", "Three"]);
        });
        $result = Blade::render("view9");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view9.html"), $result);
    }


    public function testCreator(): void
    {
        Blade::creator("*", function (View $view) {
            $view->with("items", ["One", "Two", "Three"]);
        });
        $result = Blade::render("view9");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view9.html"), $result);
    }


    public function testExists1(): void
    {
        $this->assertTrue(Blade::exists("view1"));
    }


    public function testDoesntExist(): void
    {
        $this->assertFalse(Blade::exists("no-such-view"));
    }


    public function testOverrideInstance(): void
    {
        $this->assertInstanceOf(BladeInstance::class, Blade::getInstance());
        $blade = Mockery::mock(BladeInstance::class);
        Blade::setInstance($blade);
        $this->assertSame($blade, Blade::getInstance());
    }


    public function testCustomCompiler(): void
    {
        Blade::extend(function (string $value) {
            return str_replace("Original", "New", $value);
        });
        $result = Blade::render("view12");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view12.html"), $result);
    }
}
