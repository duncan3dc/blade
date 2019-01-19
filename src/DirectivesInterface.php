<?php

namespace duncan3dc\Laravel;

use Illuminate\View\Compilers\BladeCompiler;

interface DirectivesInterface
{
    /**
     * Add extra directives to the blade templating compiler.
     *
     * @param BladeCompiler $blade The compiler to extend
     *
     * @return void
     */
    public function register(BladeCompiler $blade): void;
}
