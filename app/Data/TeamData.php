<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class TeamData extends Data
{
    public function __construct(
        public ?string $ownerId,
        public string $name,
        public bool $personalTeam = false,
    ) {
    }
}
