<?php

namespace Domain\Link\Rules;

use Closure;
use Domain\Link\Actions\CheckLinkAliasIsAllowedAction;
use Illuminate\Contracts\Validation\ValidationRule;

class NotReservedAliasRule implements ValidationRule
{
    /**
     * Ensure the link alias is not reserved.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! CheckLinkAliasIsAllowedAction::run($value)) {
            $fail('The :attribute has already been taken.');
        }
    }
}
