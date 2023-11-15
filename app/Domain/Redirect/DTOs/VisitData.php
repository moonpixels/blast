<?php

namespace Domain\Redirect\DTOs;

use Spatie\LaravelData\Data;

class VisitData extends Data
{
    public function __construct(
        public ?string $user_agent,
        public ?string $referer,
    ) {
    }
}
