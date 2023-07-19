<?php

namespace App\Rules;

use App\Actions\Links\CheckLinkAliasIsAllowed;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotReservedAlias implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! CheckLinkAliasIsAllowed::run($value)) {
            $fail('The :attribute has already been taken.');
        }
    }
}
