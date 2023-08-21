<?php

namespace App\Domain\Link\Data;

use App\Domain\Link\Actions\FormatRawUrl;
use App\Domain\Link\Rules\ExternalHost;
use App\Domain\Link\Rules\NotReservedAlias;
use App\Domain\Team\Rules\BelongsToTeam;
use App\Support\Data\Contracts\DataRules;
use App\Support\Data\Transformers\HashableTransformer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class LinkData extends DataRules
{
    /**
     * Instantiate a new link data instance.
     */
    public function __construct(
        #[FromRouteParameterProperty('team', 'id')]
        public string|Optional $teamId,
        public string|Optional $destinationUrl,
        public string|Optional $alias,
        #[WithTransformer(HashableTransformer::class)]
        public string|null|Optional $password,
        public int|null|Optional $visitLimit,
        #[WithCast(DateTimeInterfaceCast::class, setTimeZone: 'UTC')]
        public Carbon|null|Optional $expiresAt,
    ) {
    }

    /**
     * Custom attributes for validation errors.
     */
    public static function attributes(): array
    {
        return [
            'destination_url' => __('URL'),
            'team_id' => __('team'),
        ];
    }

    /**
     * Prepare the data for the data pipeline.
     */
    public static function prepareForPipeline(Collection $properties): Collection
    {
        if (is_string($destinationUrl = $properties->get('destination_url'))) {
            $properties->merge([
                'destination_url' => FormatRawUrl::run($destinationUrl),
            ]);
        }

        return $properties;
    }

    /**
     * The validation rules that apply when updating the resource.
     */
    protected static function updateRules(): array
    {
        return array_merge_recursive(self::baseRules(), [
            'team_id' => ['sometimes'],
            'destination_url' => ['sometimes'],
            'alias' => [Rule::unique('links', 'alias')->ignore(request()->route('link'))],
        ]);
    }

    /**
     * The validation rules that apply when creating the resource.
     */
    protected static function createRules(): array
    {
        return array_merge_recursive(self::baseRules(), [
            'alias' => ['unique:links,alias'],
            'expires_at' => ['after:now'],
        ]);
    }

    /**
     * Base validation rules.
     */
    protected static function baseRules(): array
    {
        return [
            'team_id' => ['required', 'ulid', new BelongsToTeam(request()->user())],
            'destination_url' => ['required', 'url', 'max:2048', new ExternalHost],
            'alias' => ['sometimes', 'required', 'string', 'alpha_num:ascii', 'max:20', new NotReservedAlias],
            'password' => ['sometimes', 'nullable', 'string'],
            'visit_limit' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:16777215'],
            'expires_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
