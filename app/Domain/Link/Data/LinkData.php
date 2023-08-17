<?php

namespace App\Domain\Link\Data;

use App\Domain\Link\Actions\FormatRawUrl;
use App\Domain\Link\Rules\NotReservedAlias;
use App\Domain\Team\Rules\BelongsToTeam;
use App\Support\DataTransformers\HashableTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class LinkData extends Data
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
     * Get the validation rules that apply to the request.
     */
    public static function rules(): array
    {
        $baseRules = collect([
            'team_id' => ['required', 'ulid', new BelongsToTeam(request()->user())],
            'destination_url' => ['required', 'url', 'max:2048'],
            'alias' => ['sometimes', 'required', 'string', 'alpha_num:ascii', 'max:20', new NotReservedAlias],
            'password' => ['sometimes', 'nullable', 'string'],
            'visit_limit' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:16777215'],
            'expires_at' => ['sometimes', 'nullable', 'date'],
        ]);

        if (request()->isMethod(Request::METHOD_PUT)) {
            return $baseRules->mergeRecursive([
                'team_id' => ['sometimes'],
                'destination_url' => ['sometimes'],
                'alias' => [Rule::unique('links', 'alias')->ignore(request()->route('link'))],
            ])->toArray();
        }

        return $baseRules->mergeRecursive([
            'alias' => ['unique:links,alias'],
            'expires_at' => ['after:now'],
        ])->toArray();
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
        $destinationUrl = $properties->get('destination_url');

        return $properties->merge([
            'destination_url' => $destinationUrl ? FormatRawUrl::run($destinationUrl) : null,
        ]);
    }
}
