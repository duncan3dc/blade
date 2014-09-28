<?php

namespace duncan3dc\Laravel;

use duncan3dc\Helpers\Env;

class BladeTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        Env::usePath(__DIR__);
    }


    public function testView1()
    {
        $result = Blade::make("view1")->__toString();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view1.blade.php"), $result);
    }


    public function testView2()
    {
        $result = Blade::make("view2", ["title" => "Test Title"])->__toString();
        $this->assertSame(file_get_contents(__DIR__ . "/views/view2.html"), $result);
    }


    public function testView3()
    {
        Blade::addPath(__DIR__ . "/views/alt");
        $result = Blade::make("view3")->__toString();
        $this->assertSame(file_get_contents(__DIR__ . "/views/alt/view3.blade.php"), $result);
    }
}
