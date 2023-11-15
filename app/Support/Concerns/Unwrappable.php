<?php

namespace Support\Concerns;

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
