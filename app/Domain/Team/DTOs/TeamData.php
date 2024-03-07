<?php

namespace Domain\Team\DTOs;

use Spatie\LaravelData\Dto;

class TeamData extends Dto
{
    public function __construct(
        public string $name,
        public bool $personal_team = false
    ) {
    }
}
