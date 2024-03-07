<?php

namespace Domain\Team\DTOs;

use Spatie\LaravelData\Dto;

class TeamInvitationData extends Dto
{
    public function __construct(
        public string $email,
    ) {
    }
}
