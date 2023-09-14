<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\DispatchQueueCheckJobsCommand;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Monitoring tasks...
        $schedule->command(DispatchQueueCheckJobsCommand::class)
            ->everyMinute()
            ->doNotMonitor();
        $schedule->command('model:prune', ['--model' => MonitoredScheduledTaskLogItem::class])
            ->daily()
            ->doNotMonitor();

        // Application tasks...
        $schedule->command('horizon:snapshot')->everyFiveMinutes();
        $schedule->command('sitemap:generate')->daily()->graceTimeInMinutes(25);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
