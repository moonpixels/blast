<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurrentTeam\UpdateRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;

class CurrentTeamController extends Controller
{
    /**
     * Update the user's current team.
     */
    public function update(UpdateRequest $request): RedirectResponse
    {
        $team = Team::findOrFail($request->validated()['team_id']);

        $request->user()->switchTeam($team);

        return redirect(config('fortify.home'), 303);
    }
}
