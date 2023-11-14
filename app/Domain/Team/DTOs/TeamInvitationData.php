<?php

namespace Domain\Team\DTOs;

use Spatie\LaravelData\Data;

class TeamInvitationData extends Data
{
    public function __construct(
        public string $email,
    ) {
    }
}
