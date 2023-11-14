<?php

namespace Domain\User\DTOs;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password,
        public ?string $current_team_id,
    ) {
    }
}
