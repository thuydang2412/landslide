<?php

namespace App\Console;

use App\Console\Commands\CallPesslApi;
use App\Console\Commands\CallPesslHistoryApi;
use App\Console\Commands\CallWarningHazardLevel;
use App\Console\Commands\CallWorldWeatherApi;
use App\Console\Commands\CalculateBoundary;
use App\Console\Commands\CallIGPApi;
use App\Console\Commands\CallWorldWeatherHistoryApi;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CallWorldWeatherApi::class,
        CalculateBoundary::class,
        CallIGPApi::class,
        CallWorldWeatherHistoryApi::class,
        CallPesslApi::class,
        CallPesslHistoryApi::class,
        CallWarningHazardLevel::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
