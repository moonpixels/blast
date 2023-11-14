<?php

namespace Support\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class WithBuilder
{
    public function __construct(
        public string $builderClass,
    ) {
    }
}
