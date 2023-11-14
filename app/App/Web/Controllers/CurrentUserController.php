<?php

namespace App\Web\Controllers;

use App\Controller;
use Domain\User\Actions\DeleteUserAction;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CurrentUserController extends Controller
{
    /**
     * Delete the current user.
     */
    public function destroy(StatefulGuard $guard): RedirectResponse
    {
        $guard->logout();

        if (DeleteUserAction::run(request()->user())) {
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect('/');
        }

        $guard->login(request()->user());

        return back()->with('error', [
            'title' => __('Something went wrong'),
            'message' => __('There was an issue processing your request. Please try again later.'),
        ]);
    }

    /**
     * Show the current user's profile settings.
     */
    public function edit(): Response
    {
        return inertia('settings/profile/index', [
            'status' => Inertia::lazy(fn () => session('status')),
            'twoFactorQrCode' => Inertia::lazy(fn () => auth()->user()?->twoFactorQrCodeSvg()),
            'twoFactorRecoveryCodes' => Inertia::lazy(fn () => auth()->user()?->recoveryCodes()),
        ]);
    }
}
