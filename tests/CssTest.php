<?php

namespace duncan3dc\LaravelTests;

class CssTest extends AbstractTest
{
    public function testString(): void
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/css/ok.css'>", "@css('ok')");
    }

    public function testExtension(): void
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/css/ok.css'>", "@css('ok.css')");
    }

    public function testPath(): void
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/alt/ok.css'>", "@css('/alt/ok.css')");
    }

    public function testVariable(): void
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='<?php echo e(\$file); ?>'>", "@css(\$file)");
    }

    public function testUrl(): void
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='https://res.com/ok.css'>", "@css('https://res.com/ok.css')");
    }

    public function testInsecureUrl(): void
    {
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='http://res.com/ok.css'>", "@css('http://res.com/ok.css')");
    }

    public function testAlternativePath(): void
    {
        $this->directives = $this->directives->withCss("styles");
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/styles/path1.css'>", "@css('path1')");
    }

    public function testRootPath(): void
    {
        $this->directives = $this->directives->withCss("/");
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/path2.css'>", "@css('path2')");
    }

    public function testNoPath(): void
    {
        $this->directives = $this->directives->withCss("");
        $this->assertTemplateString("<link rel='stylesheet' type='text/css' href='/path3.css'>", "@css('path3')");
    }
}
