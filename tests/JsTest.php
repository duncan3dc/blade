<?php

namespace duncan3dc\LaravelTests;

class JsTest extends AbstractTest
{

    public function testString()
    {
        $this->assertTemplateString("<script type='text/javascript' src='/js/ok.js'></script>", "@js('ok')");
    }

    public function testExtension()
    {
        $this->assertTemplateString("<script type='text/javascript' src='/js/ok.js'></script>", "@js('ok.js')");
    }

    public function testPath()
    {
        $this->assertTemplateString("<script type='text/javascript' src='/alt/ok.js'></script>", "@js('/alt/ok.js')");
    }

    public function testVariable()
    {
        $this->assertTemplateString("<script type='text/javascript' src='<?php echo e(\$file); ?>'></script>", "@js(\$file)");
    }
}
