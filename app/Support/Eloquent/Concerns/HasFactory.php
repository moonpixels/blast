<?php

namespace Support\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory as HasFactoryTrait;
use ReflectionClass;
use Support\Eloquent\Attributes\WithFactory;

trait HasFactory
{
    use HasFactoryTrait;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ?Factory
    {
        if ($attribute = (new self)->resolveFactoryAttribute()) {
            return $attribute->factoryClass::new();
        }

        return null;
    }

    /**
     * Resolve the factory attribute.
     */
    protected function resolveFactoryAttribute(): ?WithFactory
    {
        $reflectionClass = new ReflectionClass($this);

        $attributes = $reflectionClass->getAttributes(WithFactory::class);

        if (empty($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
