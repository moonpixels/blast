<?php

namespace Domain\User\DTOs;

use Spatie\LaravelData\Dto;

class PersonalAccessTokenData extends Dto
{
    public function __construct(
        public string $name
    ) {
    }
}
