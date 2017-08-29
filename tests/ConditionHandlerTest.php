<?php

namespace duncan3dc\LaravelTests;

use duncan3dc\Laravel\ConditionHandler;
use PHPUnit\Framework\TestCase;

class ConditionHandlerTest extends TestCase
{
    private $handler;


    public function setUp()
    {
        $this->handler = new ConditionHandler;
    }


    public function testAdd1()
    {
        $result = $this->handler->add("test", "trim");
        $this->assertSame($this->handler, $result);
    }
    public function testAdd2()
    {
        $result = $this->handler->add("test", "trim");

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("A conditional by this name already exists: @test");
        $this->handler->add("test", "trim");
    }


    public function testCheck1()
    {
        $this->handler->add("test", "trim");

        $result = $this->handler->check("test", " ok ");
        $this->assertSame("ok", $result);
    }
    public function testCheck2()
    {
        $this->handler->add("test", function () {
            return true;
        });

        $result = $this->handler->check("test");
        $this->assertSame(true, $result);
    }
    public function testCheck3()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Unknown conditional: @test");
        $this->handler->check("test");
    }
}
