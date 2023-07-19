<?php

namespace App\Exceptions;

use Exception;

class ReservedAliasException extends Exception
{
    protected $message = 'The alias is reserved.';
}
