<?php

namespace App\Support\Concerns;

/**
 * A trait to make a resource unwrappable.
 *
 * Expects the implementing class to be a subclass of `JsonResource`.
 */
trait Unwrappable
{
    /**
     * Create an instance of the resource without wrapping.
     */
    public static function createWithoutWrapping(mixed $resource): self
    {
        $jsonResource = self::make($resource);

        $jsonResource->withoutWrapping();

        return $jsonResource;
    }
}
