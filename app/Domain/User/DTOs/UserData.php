<?php

namespace Domain\User\DTOs;

use Spatie\LaravelData\Dto;

class UserData extends Dto
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password,
        public ?string $current_team_id,
    ) {
    }
}
