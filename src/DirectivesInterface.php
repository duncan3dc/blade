<?php

namespace duncan3dc\Laravel;

use Illuminate\View\Compilers\CompilerInterface;

interface DirectivesInterface
{
    /**
     * Add extra directives to the blade templating compiler.
     *
     * @param CompilerInterface $blade The compiler to extend
     *
     * @return void
     */
    public function register(CompilerInterface $blade): void;
}
