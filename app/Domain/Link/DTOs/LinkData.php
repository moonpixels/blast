<?php

namespace Domain\Link\DTOs;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class LinkData extends Data
{
    public function __construct(
        public string $team_id,
        public DomainData $domain,
        public ?string $destination_path,
        public ?string $alias,
        public ?string $password,
        public ?int $visit_limit,
        #[WithCast(DateTimeInterfaceCast::class, setTimeZone: 'UTC')]
        public ?CarbonImmutable $expires_at,
    ) {
    }
}
