<?php

namespace duncan3dc\LaravelTests;

class JsTest extends AbstractTest
{
    public function testString(): void
    {
        $this->assertTemplateString("<script type='text/javascript' src='/js/ok.js'></script>", "@js('ok')");
    }

    public function testExtension(): void
    {
        $this->assertTemplateString("<script type='text/javascript' src='/js/ok.js'></script>", "@js('ok.js')");
    }

    public function testPath(): void
    {
        $this->assertTemplateString("<script type='text/javascript' src='/alt/ok.js'></script>", "@js('/alt/ok.js')");
    }

    public function testVariable(): void
    {
        $this->assertTemplateString("<script type='text/javascript' src='<?php echo e(\$file); ?>'></script>", "@js(\$file)");
    }

    public function testUrl(): void
    {
        $this->assertTemplateString("<script type='text/javascript' src='https://res.com/ok.js'></script>", "@js('https://res.com/ok.js')");
    }

    public function testInsecureUrl(): void
    {
        $this->assertTemplateString("<script type='text/javascript' src='http://res.com/ok.js'></script>", "@js('http://res.com/ok.js')");
    }

    public function testAlternativePath(): void
    {
        $this->directives = $this->directives->withJs("scripts");
        $this->assertTemplateString("<script type='text/javascript' src='/scripts/path1.js'></script>", "@js('path1')");
    }

    public function testRootPath(): void
    {
        $this->directives = $this->directives->withJs("/");
        $this->assertTemplateString("<script type='text/javascript' src='/path2.js'></script>", "@js('path2')");
    }

    public function testNoPath(): void
    {
        $this->directives = $this->directives->withJs("");
        $this->assertTemplateString("<script type='text/javascript' src='/path3.js'></script>", "@js('path3')");
    }
}
