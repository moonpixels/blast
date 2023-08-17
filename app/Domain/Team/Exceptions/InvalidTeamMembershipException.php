<?php

namespace App\Domain\Team\Exceptions;

use Exception;

class InvalidTeamMembershipException extends Exception
{
    /**
     * Indicate that the user is already on the team.
     */
    public static function alreadyOnTeam(): self
    {
        return new self('The user is already on the team.');
    }
}
