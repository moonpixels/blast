<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Domain\Link\Models\Link;
use App\Domain\Link\Policies\LinkPolicy;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Models\TeamInvitation;
use App\Domain\Team\Policies\TeamInvitationPolicy;
use App\Domain\Team\Policies\TeamPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        TeamInvitation::class => TeamInvitationPolicy::class,
        Link::class => LinkPolicy::class,
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
