<?php

namespace duncan3dc\Laravel;

use duncan3dc\Helpers\Env;

class BladeTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        Env::usePath(__DIR__);
    }


    public function testBasic()
    {
        $result = Blade::make("view1")->__toString();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testParameters()
    {
        $result = Blade::make("view2", ["title" => "Test Title"])->__toString();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testAltPath()
    {
        Blade::addPath(__DIR__ . "/views/alt");
        $result = Blade::make("view3")->__toString();
        $this->assertSame(file_get_contents(__DIR__ . "/views/alt/view3.blade.php"), $result);
    }


    public function testNamespace()
    {
        $result = Blade::make("view4")->__toString();
        $this->assertSame("duncan3dc\\Laravel", $result);
    }


    public function testUse()
    {
        $result = Blade::make("view5")->__toString();
        $this->assertSame(Env::getMachineName(), $result);
    }
}
