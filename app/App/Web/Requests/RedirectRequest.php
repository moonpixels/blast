<?php

namespace App\Web\Requests;

use Domain\Link\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RedirectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        /** @var Link $link */
        $link = $this->route('link');

        return [
            'password' => [
                'sometimes',
                Rule::requiredIf($link->has_password),
                'string',
            ],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        /** @var Link $link */
        $link = $this->route('link');

        return [
            function (Validator $validator) use ($link) {
                if (
                    $link->has_password
                    && $this->validated('password')
                    && ! $link->passwordMatches($this->validated('password'))
                ) {
                    $validator->errors()->add('password', __('The provided password is incorrect.'));
                }
            },
        ];
    }
}
