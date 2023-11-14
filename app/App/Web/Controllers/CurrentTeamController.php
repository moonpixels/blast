<?php

namespace App\Web\Controllers;

use App\Controller;
use App\Web\Requests\CurrentTeamUpdateRequest;
use Domain\Team\Models\Team;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CurrentTeamController extends Controller
{
    /**
     * Update the user's current team.
     */
    public function update(CurrentTeamUpdateRequest $request): RedirectResponse
    {
        $team = Team::find($request->toDTO()->current_team_id);

        $request->user()->switchTeam($team);

        return redirect(config('fortify.home'), Response::HTTP_SEE_OTHER);
    }
}
