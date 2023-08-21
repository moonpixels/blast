<?php

namespace App\Domain\Redirect\Data;

use App\Support\Data\Contracts\DataRules;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class RedirectData extends DataRules
{
    /**
     * Instantiate a new team data instance.
     */
    public function __construct(
        public string|Optional $password,
    ) {
    }

    /**
     * The validation rules that apply when updating the resource.
     */
    protected static function updateRules(): array
    {
        return [];
    }

    /**
     * The validation rules that apply when creating the resource.
     */
    protected static function createRules(): array
    {
        return [
            'password' => ['required', 'string'],
        ];
    }
}
