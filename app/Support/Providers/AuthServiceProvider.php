<?php

namespace Support\Providers;

// use Illuminate\Support\Facades\Gate;
use Domain\Link\Models\Link;
use Domain\Link\Policies\LinkPolicy;
use Domain\Team\Models\Team;
use Domain\Team\Models\TeamInvitation;
use Domain\Team\Policies\TeamInvitationPolicy;
use Domain\Team\Policies\TeamPolicy;
use Domain\User\Policies\PersonalAccessTokenPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Sanctum\PersonalAccessToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Link::class => LinkPolicy::class,
        PersonalAccessToken::class => PersonalAccessTokenPolicy::class,
        Team::class => TeamPolicy::class,
        TeamInvitation::class => TeamInvitationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject(__('Verify your email address'))
                ->markdown('emails.auth.verify-email', [
                    'url' => $url,
                ]);
        });
    }
}
