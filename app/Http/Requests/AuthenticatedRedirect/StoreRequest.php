<?php

namespace App\Http\Requests\AuthenticatedRedirect;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string'],
        ];
    }
}
