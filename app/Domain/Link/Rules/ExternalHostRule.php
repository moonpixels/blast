<?php

namespace Domain\Link\Rules;

use Closure;
use Domain\Link\Support\Helpers\Url;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class ExternalHostRule implements ValidationRule
{
    /**
     * Ensure the URL has an external host.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Str::isUrl($value)) {
            $fail(__('validation.url', ['attribute' => $attribute]));
        } else {
            $appHost = Url::parseUrl(config('app.url'))['host'];
            $inputHost = Url::parseUrl($value)['host'];

            if ($appHost === $inputHost) {
                $fail('The :attribute must be an external URL.');
            }
        }
    }
}
