<?php

namespace Domain\Link\DTOs;

use Spatie\LaravelData\Data;

class DomainData extends Data
{
    public function __construct(
        public string $host,
    ) {
    }
}
