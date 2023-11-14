<?php

namespace Domain\Team\Exceptions;

use Exception;

class InvalidTeamMemberException extends Exception
{
    /**
     * Create an exception for when the user is already on the team.
     */
    public static function alreadyOnTeam(): self
    {
        return new self('The user is already on the team.');
    }
}
