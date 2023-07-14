<?php

namespace App\Exceptions;

use App\Enums\Exceptions\InvalidUrlExceptionType;
use Exception;

class InvalidUrlException extends Exception
{
    /**
     * The exception type.
     */
    public InvalidUrlExceptionType $type;

    /**
     * Indicate that the host is invalid.
     */
    public static function invalidHost(): self
    {
        $instance = new self('The host is invalid.');

        $instance->type = InvalidUrlExceptionType::InvalidHost;

        return $instance;
    }
}
