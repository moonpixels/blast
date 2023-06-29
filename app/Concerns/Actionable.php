<?php

namespace App\Concerns;

trait Actionable
{
    /**
     * Execute the action.
     */
    public static function execute(mixed ...$args): mixed
    {
        return static::make()->handle(...$args);
    }

    /**
     * Make a new action instance.
     */
    public static function make(): static
    {
        return app(static::class);
    }
}
