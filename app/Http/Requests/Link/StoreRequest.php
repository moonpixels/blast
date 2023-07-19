<?php

namespace App\Http\Requests\Link;

use App\Actions\Links\FormatRawUrl;
use App\Rules\BelongsToTeam;
use App\Rules\NotReservedAlias;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'url' => ['required', 'string', 'url', 'active_url', 'max:2048'],
            'alias' => [
                'sometimes', 'string', 'alpha_num:ascii', 'max:20', 'unique:links,alias', new NotReservedAlias,
            ],
            'team_id' => ['bail', 'required', 'ulid', 'exists:teams,id', new BelongsToTeam($this->user())],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'url' => __('URL'),
            'alias' => __('alias'),
            'team_id' => __('team'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'url' => $this->input('url') ? FormatRawUrl::run($this->input('url')) : null,
        ]);
    }
}
