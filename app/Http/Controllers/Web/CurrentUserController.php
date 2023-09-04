<?php

namespace App\Http\Controllers\Web;

use App\Domain\User\Actions\DeleteUser;
use App\Http\Controllers\Controller;
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

        if (DeleteUser::run(request()->user())) {
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
     * Show the form for editing the current user.
     */
    public function edit(): Response
    {
        return Inertia::render('User/Edit', [
            'status' => Inertia::lazy(fn () => session('status')),
            'twoFactorQrCode' => Inertia::lazy(fn () => auth()->user()?->twoFactorQrCodeSvg()),
            'twoFactorRecoveryCodes' => Inertia::lazy(fn () => auth()->user()?->recoveryCodes()),
        ]);
    }
}