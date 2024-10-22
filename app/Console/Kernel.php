<?php

namespace App\Console;

use App\Console\Commands\NotifyDocumentExpiration;
use App\Console\Commands\NotifyExpiredDocuments;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\NotifyDocumentExpiration::class,
    ];

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('documents:notify-expiry')->everyMinute();
        $schedule->command('documents:notify-expiry')->dailyAt('00:00');
    }

}
