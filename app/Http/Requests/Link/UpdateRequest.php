<?php

namespace App\Http\Requests\Link;

use App\Actions\Links\FormatRawUrl;
use App\Models\Link;
use App\Rules\BelongsToTeam;
use App\Rules\NotReservedAlias;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        /** @var Link $link */
        $link = $this->route('link');

        return [
            'destination_url' => ['required', 'string', 'url', 'active_url', 'max:2048'],
            'alias' => [
                'sometimes',
                'string',
                'alpha_num:ascii',
                'max:20',
                Rule::unique('links', 'alias')->ignore($link->id),
                new NotReservedAlias,
            ],
            'password' => ['sometimes', 'nullable', 'string'],
            'visit_limit' => ['sometimes', 'nullable', 'integer', 'between:1,16777215'],
            'expires_at' => ['sometimes', 'nullable', 'date'],
            'team_id' => ['bail', 'required', 'ulid', 'exists:teams,id', new BelongsToTeam($this->user())],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'destination_url' => __('URL'),
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
            'destination_url' => $this->input('destination_url') ? FormatRawUrl::run($this->input('destination_url')) : null,
        ]);
    }
}
