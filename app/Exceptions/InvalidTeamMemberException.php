<?php

namespace App\Exceptions;

use Exception;

class InvalidTeamMemberException extends Exception
{
    /**
     * Indicate that the user is already on the team.
     */
    public static function alreadyOnTeam(): self
    {
        return new self('The user is already on the team.');
    }
}
