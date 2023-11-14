<?php

namespace Domain\Team\DTOs;

use Spatie\LaravelData\Data;

class TeamData extends Data
{
    public function __construct(
        public string $name,
        public bool $personal_team = false
    ) {
    }
}
