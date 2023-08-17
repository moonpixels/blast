<?php

namespace App\Domain\Redirect\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class RedirectData extends Data
{
    /**
     * Instantiate a new team data instance.
     */
    public function __construct(
        public string|Optional $password,
    ) {
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public static function rules(): array
    {
        return [
            'password' => ['required', 'string'],
        ];
    }
}
