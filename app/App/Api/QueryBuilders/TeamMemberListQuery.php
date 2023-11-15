<?php

namespace App\Api\QueryBuilders;

use Domain\Team\Models\Team;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TeamMemberListQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        /** @var Team $team */
        $team = $request->route('team');

        $query = User::query()
            ->whereIn('id', $team->members()->pluck('id'))
            ->orderBy('name');

        parent::__construct($query, $request);

        $this->allowedFilters([
            AllowedFilter::callback('search', fn (Builder $query, $value) => $this->searchMembers($query, $value)),
        ]);
    }

    protected function searchMembers(Builder $query, mixed $value): void
    {
        $query->where('email', 'like', "%{$value}%")
            ->orWhere('name', 'like', "%{$value}%");
    }
}
