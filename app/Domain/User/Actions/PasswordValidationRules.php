<?php

namespace Domain\User\Actions;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the password validation rules.
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', new Password];
    }
}
