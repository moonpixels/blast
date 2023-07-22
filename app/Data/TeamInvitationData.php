<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class TeamInvitationData extends Data
{
    public function __construct(
        public ?string $teamId,
        public string $email,
    ) {
    }
}
