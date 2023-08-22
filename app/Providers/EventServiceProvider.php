<?php

namespace App\Providers;

use App\Domain\Team\Events\TeamDeleted;
use App\Domain\Team\Listeners\DeleteTeamLinks;
use App\Domain\Team\Models\Team;
use App\Domain\Team\Observers\TeamObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TeamDeleted::class => [
            DeleteTeamLinks::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Team::observe(TeamObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
