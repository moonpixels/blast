<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class LinkData extends Data
{
    public function __construct(
        public string $teamId,
        public ?string $alias,
        public ?string $password,
        public ?Carbon $expiresAt,
        public string $destinationUrl,
    ) {
    }
}
