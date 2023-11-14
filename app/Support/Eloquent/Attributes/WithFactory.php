<?php

namespace Support\Eloquent\Attributes;

use Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @property Factory $factoryClass
 */
#[Attribute(Attribute::TARGET_CLASS)]
class WithFactory
{
    public function __construct(
        public string $factoryClass,
    ) {
    }
}
