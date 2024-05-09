<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\FetchMercadonaProducts::class,
        \App\Console\Commands\CheckPriceUpdates::class,
    ];    

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:mercadona')->daily();
        $schedule->command('price:check')->daily();
    }
    

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}