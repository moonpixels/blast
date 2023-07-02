<?php

namespace App\Concerns;

/**
 * A trait to make an action class executable.
 *
 * Expects the implementing class to have a `handle` method containing the
 * business logic for the action.
 */
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
