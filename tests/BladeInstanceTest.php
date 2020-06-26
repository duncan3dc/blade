<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\BladeInstance;
use Illuminate\Contracts\View\View;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function str_replace;
use function trim;

class BladeInstanceTest extends TestCase
{
    /** @var BladeInstance */
    private $blade;


    public function setUp(): void
    {
        $this->blade = new BladeInstance(__DIR__ . "/views", Utils::getCachePath());
    }


    public function testAddExtension1(): void
    {
        $this->blade->addExtension("custom");
        $expected = file_get_contents(__DIR__ . "/views/view16.html");
        $result = $this->blade->render("view16", ["title" => "Test Title"]);
        $this->assertSame($expected, $result);
    }


    public function testBasicMake(): void
    {
        $result = $this->blade->make("view1")->render();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testBasicRender(): void
    {
        $result = $this->blade->render("view1");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testParametersMake(): void
    {
        $result = $this->blade->make("view2", ["title" => "Test Title"])->render();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testParametersRender(): void
    {
        $result = $this->blade->render("view2", ["title" => "Test Title"]);
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testAltPath(): void
    {
        $this->blade->addPath(__DIR__ . "/views/alt");
        $result = $this->blade->render("view3");
        $this->assertSame(file_get_contents(__DIR__ . "/views/alt/view3.blade.php"), $result);
    }


    public function testNamespace(): void
    {
        $result = $this->blade->render("view4");
        $this->assertSame("duncan3dc\\Laravel", trim($result));
    }


    public function testUse(): void
    {
        $result = $this->blade->render("view5");
        $this->assertSame("stuff", trim($result));
    }


    public function testRawOutput(): void
    {
        $result = $this->blade->render("view6");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view6.html"), $result);
    }


    public function testEscapedOutput(): void
    {
        $result = $this->blade->render("view7");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view7.html"), $result);
    }


    public function testShare(): void
    {
        $this->blade->share("shareData", "shared");
        $result = $this->blade->render("view8");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view8.html"), $result);
    }


    public function testComposer(): void
    {
        $this->blade->composer("*", function (View $view) {
            $view->with("items", ["One", "Two", "Three"]);
        });
        $result = $this->blade->render("view9");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view9.html"), $result);
    }


    public function testCreator(): void
    {
        $this->blade->creator("*", function (View $view) {
            $view->with("items", ["One", "Two", "Three"]);
        });
        $result = $this->blade->render("view9");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view9.html"), $result);
    }


    public function testExists1(): void
    {
        $this->assertTrue($this->blade->exists("view1"));
    }


    public function testDoesntExist(): void
    {
        $this->assertFalse($this->blade->exists("no-such-view"));
    }


    public function testInheritance(): void
    {
        $result = $this->blade->render("view10");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view10.html"), $result);
    }


    public function testInheritanceAltPath(): void
    {
        $this->blade->addPath(__DIR__ . "/views/alt");
        $result = $this->blade->render("view11");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view11.html"), $result);
    }


    public function testCustomCompiler(): void
    {
        $this->blade->extend(function (string $value) {
            return str_replace("Original", "New", $value);
        });
        $result = $this->blade->render("view12");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view12.html"), $result);
    }


    public function testCustomDirective(): void
    {
        $this->blade->directive("normandie", function (string $parameter) {
            $parameter = trim($parameter, "()");
            return "inguz({$parameter});";
        });

        $result = $this->blade->render("view13");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view13.html"), $result);
    }


    public function testAliasComponent1(): void
    {
        $this->blade->aliasComponent("self_help_fest");

        $result = $this->blade->render("view19");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view19.html"), $result);
    }


    public function testAliasComponent2(): void
    {
        $this->blade->aliasComponent("self_help_fest", "selfhelp");

        $result = $this->blade->render("view20");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view20.html"), $result);
    }


    public function testComponent1(): void
    {
        $this->blade->component("self_help_fest");

        $result = $this->blade->render("view19");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view19.html"), $result);
    }


    public function testComponent2(): void
    {
        $this->blade->component("self_help_fest", "selfhelp");

        $result = $this->blade->render("view20");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view20.html"), $result);
    }


    public function customConditionProvider(): iterable
    {
        yield [false, "off"];
        yield [true, "on"];
    }
    /**
     * @dataProvider customConditionProvider
     */
    public function testCustomConditions(bool $global, string $expected): void
    {
        $this->blade->if("global", function () use ($global) {
            return $global;
        });

        $result = $this->blade->render("view14");
        $this->assertSame("{$expected}\n", $result);
    }
    /**
     * @dataProvider customConditionProvider
     */
    public function testCustomConditionArguments(bool $global, string $expected): void
    {
        $this->blade->if("global", function (bool $global) {
            return $global;
        });

        $result = $this->blade->render("view15", [
            "global"    =>  $global,
        ]);
        $this->assertSame("{$expected}\n", $result);
    }


    /**
     * Ensure we support the basic PHP engine.
     */
    public function testRender1(): void
    {
        $result = $this->blade->render("view17", ["title" => "Test Title"]);
        $this->assertSame(file_get_contents(__DIR__ . "/views/view17.html"), $result);
    }


    /**
     * Ensure we support the basic text file engine.
     */
    public function testRender2(): void
    {
        $result = $this->blade->render("view18");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view18.html"), $result);
    }
}
