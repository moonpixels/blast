<?php

namespace App\Http\Controllers\Web;

use App\Domain\Team\Models\Team;
use App\Domain\User\Data\UserData;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CurrentTeamController extends Controller
{
    /**
     * Update the user's current team.
     */
    public function update(UserData $data): RedirectResponse
    {
        request()->user()->switchTeam(Team::find($data->currentTeamId));

        return redirect(config('fortify.home'), Response::HTTP_SEE_OTHER);
    }
}
