<?php

namespace App\Domain\Link\Rules;

use App\Support\Concerns\HasUrlInput;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ExternalHost implements ValidationRule
{
    use HasUrlInput;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Str::isUrl($value)) {
            $fail(__('validation.url', ['attribute' => $attribute]));
        } else {
            $appHost = $this->parseUrlInput(config('app.url'))['host'];
            $inputHost = $this->parseUrlInput($value)['host'];

            if ($appHost === $inputHost) {
                $fail('The :attribute must be an external URL.');
            }
        }
    }
}
