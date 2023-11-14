<?php

namespace App\Api\QueryBuilders;

use Domain\Team\Models\Team;
use Domain\Team\Models\TeamInvitation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TeamInvitationListQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        /** @var Team $team */
        $team = $request->route('team');

        $query = TeamInvitation::query()
            ->forTeam($team)
            ->with('team')
            ->orderByDesc('created_at');

        parent::__construct($query, $request);

        $this->allowedFilters([
            AllowedFilter::callback(
                'search',
                fn (Builder $query, $value) => $query->where('email', 'like', "%{$value}%")
            ),
        ]);
    }
}
