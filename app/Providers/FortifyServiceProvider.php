<?php

namespace App\Providers;

use App\Domain\User\Actions\CreateUser;
use App\Domain\User\Actions\ResetUserPassword;
use App\Domain\User\Actions\UpdateUserPassword;
use App\Domain\User\Actions\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
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
        Fortify::createUsersUsing(CreateUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

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

            return Inertia::render('Auth/Register');
        });

        Fortify::loginView(function () {
            return Inertia::render('Auth/Login', [
                'status' => session('status'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('Auth/ForgotPassword', [
                'status' => session('status'),
            ]);
        });

        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('Auth/ResetPassword', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });

        Fortify::confirmPasswordView(function () {
            return Inertia::render('Auth/ConfirmPassword');
        });

        Fortify::twoFactorChallengeView(function () {
            return Inertia::render('Auth/TwoFactorChallenge');
        });

        Fortify::verifyEmailView(function (Request $request) {
            if ($request->session()->get('status') === 'verification-link-sent') {
                session()->flash('success', [
                    'title' => __('Email verification resent'),
                    'message' => __('A new email verification link has been sent to :email',
                        ['email' => $request->user()->email]),
                ]);
            }

            return Inertia::render('Auth/VerifyEmail', [
                'status' => session('status'),
                'email' => $request->user()->email,
            ]);
        });
    }
}
