<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Helpers\Env;
use duncan3dc\Laravel\Blade;

class BladeTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        Env::usePath(__DIR__);
    }

    public function testBasicMake()
    {
        $result = Blade::make("view1")->render();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testBasicRender()
    {
        $result = Blade::render("view1");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testParametersMake()
    {
        $result = Blade::make("view2", ["title" => "Test Title"])->render();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testParametersRender()
    {
        $result = Blade::render("view2", ["title" => "Test Title"]);
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testAltPath()
    {
        Blade::addPath(__DIR__ . "/views/alt");
        $result = Blade::render("view3");
        $this->assertSame(file_get_contents(__DIR__ . "/views/alt/view3.blade.php"), $result);
    }


    public function testNamespace()
    {
        $result = Blade::render("view4");
        $this->assertSame("duncan3dc\\Laravel", trim($result));
    }


    public function testUse()
    {
        $result = Blade::render("view5");
        $this->assertSame(Env::getMachineName(), trim($result));
    }


    public function testRawOutput()
    {
        $result = Blade::render("view6");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view6.html"), $result);
    }


    public function testEscapedOutput()
    {
        $result = Blade::render("view7");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view7.html"), $result);
    }


    public function testShare()
    {
        Blade::share("shareData", "shared");
        $result = Blade::render("view8");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view8.html"), $result);
    }


    public function testComposer()
    {
        Blade::composer("*", function($view) {
            $view->with("items", ["One", "Two", "Three"]);
        });
        $result = Blade::render("view9");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view9.html"), $result);
    }


    public function testCreator()
    {
        Blade::creator("*", function($view) {
            $view->with("items", ["One", "Two", "Three"]);
        });
        $result = Blade::render("view9");
        $this->assertSame(file_get_contents(__DIR__ . "/views/view9.html"), $result);
    }


    public function testExists1()
    {
        $this->assertTrue(Blade::exists("view1"));
    }


    public function testDoesntExist()
    {
        $this->assertFalse(Blade::exists("no-such-view"));
    }
}
