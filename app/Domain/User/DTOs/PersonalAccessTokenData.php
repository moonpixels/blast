<?php

namespace Domain\User\DTOs;

use Spatie\LaravelData\Data;

class PersonalAccessTokenData extends Data
{
    public function __construct(
        public string $name
    ) {
    }
}
