<?php

namespace App\Concerns;

use App\Exceptions\InvalidUrlException;
use Illuminate\Support\Str;

trait HasUrlInput
{
    /**
     * Parse the given URL input.
     *
     * @throws InvalidUrlException
     */
    public static function parseUrlInput(string $url): array
    {
        $host = Str::lower(parse_url($url, PHP_URL_HOST));

        if (! $host) {
            throw InvalidUrlException::invalidHost();
        }

        $path = Str::after($url, $host) ?: null;

        return [
            'host' => $host,
            'path' => $path,
        ];
    }
}
