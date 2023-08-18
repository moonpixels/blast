<?php

namespace App\Domain\Team\Rules;

use App\Domain\Team\Models\Team;
use App\Domain\User\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BelongsToTeam implements ValidationRule
{
    /**
     * Instantiate the rule.
     */
    public function __construct(protected User $user)
    {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $team = Team::find($value);

        if (! $team || ! $this->user->belongsToTeam($team)) {
            $fail('The :attribute field must contain a valid team ID that you belong to.');
        }
    }
}
