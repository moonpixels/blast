<?php

namespace App\Domain\Link\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class DomainData extends Data
{
    /**
     * Instantiate a new domain data instance.
     */
    public function __construct(
        public string|Optional $host,
    ) {
    }
}
