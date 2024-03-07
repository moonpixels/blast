<?php

namespace Domain\Link\DTOs;

use Spatie\LaravelData\Dto;

class DomainData extends Dto
{
    public function __construct(
        public string $host,
    ) {
    }
}
