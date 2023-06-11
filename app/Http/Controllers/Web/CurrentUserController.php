<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CurrentUserController extends Controller
{
    /**
     * Instantiate the controller.
     */
    public function __construct(protected readonly UserService $userService)
    {
        $this->middleware(['password.confirm']);
    }

    /**
     * Delete the current user.
     */
    public function destroy(Request $request, StatefulGuard $guard): RedirectResponse
    {
        $guard->logout();

        if ($this->userService->deleteUser($request->user())) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        }

        $guard->login($request->user());

        return back()->with('error', [
            'title' => __('Something went wrong'),
            'message' => __('There was an issue processing your request. Please try again later.'),
        ]);
    }
}
