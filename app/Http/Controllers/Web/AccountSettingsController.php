<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class AccountSettingsController extends Controller
{
    /**
     * Show the account settings page.
     */
    public function edit(): Response
    {
        return Inertia::render('AccountSettings/Edit', [
            'status' => Inertia::lazy(fn () => session('status')),
            'twoFactorQrCode' => Inertia::lazy(fn () => auth()->user()?->twoFactorQrCodeSvg()),
            'twoFactorRecoveryCodes' => Inertia::lazy(fn () => auth()->user()?->recoveryCodes()),
        ]);
    }
}
