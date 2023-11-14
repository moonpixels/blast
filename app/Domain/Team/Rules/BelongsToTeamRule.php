<?php

namespace Domain\Team\Rules;

use Closure;
use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class BelongsToTeamRule implements ValidationRule
{
    public function __construct(
        protected User $user
    ) {
    }

    /**
     * Ensure that the user belongs to the team.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $team = Team::find($value);

        if (! $team || ! $this->user->belongsToTeam($team)) {
            $fail('The :attribute field must contain a valid team ID that you belong to.');
        }
    }
}
