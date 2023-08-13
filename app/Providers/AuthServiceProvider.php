<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Link;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\TeamMembership;
use App\Policies\LinkPolicy;
use App\Policies\TeamInvitationPolicy;
use App\Policies\TeamMembershipPolicy;
use App\Policies\TeamPolicy;
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
        TeamMembership::class => TeamMembershipPolicy::class,
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
