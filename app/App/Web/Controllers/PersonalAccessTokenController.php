<?php

namespace App\Web\Controllers;

use App\Api\Resources\PersonalAccessTokenResource;
use App\Controller;
use App\Web\Requests\TokenCreateRequest;
use Domain\User\Actions\CreatePersonalAccessTokenAction;
use Domain\User\Actions\DeletePersonalAccessTokenAction;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenController extends Controller
{
    /**
     * Show a list of the user's tokens.
     */
    public function index(): Response
    {
        return inertia('settings/api/index', [
            'tokens' => PersonalAccessTokenResource::collection(auth()->user()->tokens),
            'plainTextToken' => session('plainTextToken'),
        ]);
    }

    /**
     * Create a new token.
     */
    public function store(TokenCreateRequest $request): RedirectResponse
    {
        $this->authorize('create', PersonalAccessToken::class);

        $token = CreatePersonalAccessTokenAction::run($request->user(), $request->toDTO());

        session()->flash('plainTextToken', $token->plainTextToken);

        return back()->with('success', [
            'title' => __('Token created'),
            'message' => __('The API token has been created.'),
        ]);
    }

    /**
     * Delete the given token.
     */
    public function destroy(PersonalAccessToken $token): RedirectResponse
    {
        $this->authorize('delete', $token);

        DeletePersonalAccessTokenAction::run($token);

        return back()->with('success', [
            'title' => __('Token deleted'),
            'message' => __('The API token has been deleted.'),
        ]);
    }
}
