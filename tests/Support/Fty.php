<?php

namespace Tests\Support;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * Factory helper.
 */
class Fty
{
    /**
     * Build a factory with the given states.
     */
    public static function build(Factory $factory, array $states = []): Factory
    {
        foreach ($states as $state => $attributes) {
            if (is_int($state)) {
                $state = $attributes;
                $attributes = [];
            }

            $attributes = Arr::wrap($attributes);

            $factory = $factory->{$state}(...$attributes);
        }

        return $factory;
    }
}
