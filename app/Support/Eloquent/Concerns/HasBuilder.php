<?php

namespace Support\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Builder;
use ReflectionClass;
use Support\Eloquent\Attributes\WithBuilder;

trait HasBuilder
{
    /**
     * Create a new Eloquent query builder for the model.
     */
    public function newEloquentBuilder($query): Builder
    {
        if ($attribute = $this->resolveQueryBuilderAttribute()) {
            return new $attribute->builderClass($query);
        }

        return parent::newEloquentBuilder($query);
    }

    /**
     * Resolve the query builder attribute.
     */
    protected function resolveQueryBuilderAttribute(): ?WithBuilder
    {
        $reflectionClass = new ReflectionClass($this);

        $attributes = $reflectionClass->getAttributes(WithBuilder::class);

        if (empty($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
