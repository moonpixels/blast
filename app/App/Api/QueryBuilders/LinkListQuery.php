<?php

namespace App\Api\QueryBuilders;

use Domain\Link\Models\Link;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LinkListQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        $query = Link::query()
            ->forUser($request->user());

        parent::__construct($query, $request);

        $this->allowedFilters([
            AllowedFilter::exact('team_id'),
            AllowedFilter::callback('search', fn (Builder $query, $value) => $this->searchLinks($query, $value)),
        ]);
    }

    protected function searchLinks(Builder $query, mixed $value): void
    {
        $query->where('alias', 'like', "%{$value}%")
            ->orWhere('destination_path', 'like', "%{$value}%")
            ->orWhereRelation('domain', 'host', 'like', "%{$value}%");
    }
}
