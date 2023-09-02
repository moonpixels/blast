<?php

namespace App\Http\Controllers\Web;

use App\Domain\User\Actions\CreatePersonalAccessToken;
use App\Domain\User\Actions\DeletePersonalAccessToken;
use App\Domain\User\Data\PersonalAccessTokenData;
use App\Domain\User\Resources\PersonalAccessTokenResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenController extends Controller
{
    /**
     * Show the personal access token management page.
     */
    public function index(): Response
    {
        return inertia('Settings/Api/Index', [
            'tokens' => PersonalAccessTokenResource::collection(auth()->user()->tokens),
            'plainTextToken' => session('plainTextToken'),
        ]);
    }

    /**
     * Store a new personal access token.
     */
    public function store(PersonalAccessTokenData $data): RedirectResponse
    {
        $this->authorize('create', PersonalAccessToken::class);

        $token = CreatePersonalAccessToken::run(auth()->user(), $data);

        session()->flash('plainTextToken', $token->plainTextToken);

        return back()->with('success', [
            'title' => __('Token created'),
            'message' => __('The API token has been created.'),
        ]);
    }

    /**
     * Delete the given personal access token.
     */
    public function destroy(PersonalAccessToken $token): RedirectResponse
    {
        $this->authorize('delete', $token);

        DeletePersonalAccessToken::run($token);

        return back()->with('success', [
            'title' => __('Token deleted'),
            'message' => __('The API token has been deleted.'),
        ]);
    }
}
