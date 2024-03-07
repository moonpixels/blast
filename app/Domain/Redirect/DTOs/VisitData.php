<?php

namespace Domain\Redirect\DTOs;

use Spatie\LaravelData\Dto;

class VisitData extends Dto
{
    public function __construct(
        public ?string $user_agent,
        public ?string $referer,
    ) {
    }
}
