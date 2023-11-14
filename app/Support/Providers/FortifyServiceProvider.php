<?php

namespace Support\Providers;

use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\ResetUserPasswordAction;
use Domain\User\Actions\UpdateUserPasswordAction;
use Domain\User\Actions\UpdateUserProfileInformationAction;
use Domain\User\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateUserAction::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformationAction::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPasswordAction::class);
        Fortify::resetUserPasswordsUsing(ResetUserPasswordAction::class);

        RateLimiter::for('login', function (Request $request) {
            $email = $request->input('email');

            return Limit::perMinute(10)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(10)->by($request->session()->get('login.id'));
        });

        Fortify::registerView(function () {
            if (config('blast.disable_registration')) {
                abort(403, __('Registration is currently disabled'));
            }

            return inertia('register');
        });

        Fortify::loginView(function () {
            return inertia('login', [
                'status' => session('status'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return inertia('forgot-password', [
                'status' => session('status'),
            ]);
        });

        Fortify::resetPasswordView(function (Request $request) {
            return inertia('reset-password', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });

        Fortify::confirmPasswordView(function () {
            return inertia('confirm-password');
        });

        Fortify::twoFactorChallengeView(function () {
            return inertia('two-factor-challenge');
        });

        Fortify::verifyEmailView(function (Request $request) {
            if ($request->session()->get('status') === 'verification-link-sent') {
                session()->flash('success', [
                    'title' => __('Email verification resent'),
                    'message' => __('A new email verification link has been sent to :email',
                        ['email' => $request->user()->email]),
                ]);
            }

            return inertia('verify-email', [
                'status' => session('status'),
                'email' => $request->user()->email,
            ]);
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->input('email'))->first();

            if (! $user) {
                return null;
            }

            if (! Hash::check($request->input('password'), $user->password)) {
                return null;
            }

            if ($user->isBlocked()) {
                throw ValidationException::withMessages([
                    'email' => __('Your account has been blocked.'),
                ]);
            }

            return $user;
        });
    }
}
