<?php

namespace duncan3dc\LaravelTests;

class CssTest extends AbstractTest
{

    public function testString()
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/css/ok.css'>", "@css('ok')");
    }

    public function testExtension()
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/css/ok.css'>", "@css('ok.css')");
    }

    public function testPath()
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/alt/ok.css'>", "@css('/alt/ok.css')");
    }

    public function testVariable()
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='<?php echo e(\$file); ?>'>", "@css(\$file)");
    }
}
