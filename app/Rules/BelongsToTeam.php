<?php

namespace App\Rules;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

readonly class BelongsToTeam implements ValidationRule
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
        $team = Team::findOrFail($value);

        if (! $this->user->belongsToTeam($team)) {
            $fail('The :attribute field must contain a valid team ID that you belong to.');
        }
    }
}
