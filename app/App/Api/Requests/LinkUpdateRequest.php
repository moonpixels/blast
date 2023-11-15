<?php

namespace App\Api\Requests;

use Domain\Link\DTOs\DomainData;
use Domain\Link\DTOs\LinkData;
use Domain\Link\Models\Link;
use Domain\Link\Rules\ExternalHostRule;
use Domain\Link\Rules\NotReservedAliasRule;
use Domain\Link\Support\Helpers\Url;
use Domain\Team\Rules\BelongsToTeamRule;
use Illuminate\Foundation\Http\FormRequest;

class LinkUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        /** @var Link $link */
        $link = $this->route('link');

        return [
            'team_id' => ['sometimes', 'required', 'ulid', new BelongsToTeamRule(request()->user())],
            'destination_url' => ['sometimes', 'required', 'url', 'max:2048', new ExternalHostRule],
            'alias' => [
                'sometimes',
                'required',
                'string',
                'alpha_num:ascii',
                'max:20',
                'unique:links,alias,{link->id}',
                new NotReservedAliasRule,
            ],
            'password' => ['sometimes', 'nullable', 'string'],
            'visit_limit' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:16777215'],
            'expires_at' => ['sometimes', 'nullable', 'date', 'after:now'],
        ];
    }

    /**
     * Convert the request into a DTO.
     */
    public function toDTO(): LinkData
    {
        /** @var Link $link */
        $link = $this->route('link');

        $destinationUrl = Url::parseUrl($this->validated('destination_url', $link->destination_url));

        return LinkData::from([
            'team_id' => $this->validated('team_id', $link->team_id),
            'domain' => DomainData::from([
                'host' => $destinationUrl['host'],
            ]),
            'destination_path' => $destinationUrl['path'],
            'alias' => $this->validated('alias', $link->alias),
            'password' => $this->validated('password', $link->password),
            'visit_limit' => $this->validated('visit_limit', $link->visit_limit),
            'expires_at' => $this->validated('expires_at', $link->expires_at),
        ]);
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        if ($this->input('destination_url')) {
            $this->merge([
                'destination_url' => Url::formatFromInput($this->input('destination_url')),
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'destination_url' => __('URL'),
            'team_id' => __('team'),
        ];
    }
}
