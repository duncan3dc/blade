<?php

namespace duncan3dc\Laravel;

use function array_key_exists;

class ConditionHandler
{
    /**
     * @var array<string, callable> $conditions The conditions registered.
     */
    private array $conditions = [];


    /**
     * Register an custom conditional directive.
     *
     * @return $this
     */
    public function add(string $name, callable $handler): ConditionHandler
    {
        if (array_key_exists($name, $this->conditions)) {
            throw new \UnexpectedValueException("A conditional by this name already exists: @{$name}");
        }

        $this->conditions[$name] = $handler;

        return $this;
    }


    /**
     * Call a registered conditional directive.
     */
    public function check(string $name, mixed ...$params): mixed
    {
        if (!array_key_exists($name, $this->conditions)) {
            throw new \UnexpectedValueException("Unknown conditional: @{$name}");
        }

        $function = $this->conditions[$name];

        return $function(...$params);
    }
}
