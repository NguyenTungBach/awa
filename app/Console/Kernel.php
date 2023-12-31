<?php

namespace App\Console;

use Carbon\Carbon;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('command:cronDeleteCourseCommand')->monthlyOn(1, '02:00')->withoutOverlapping();
//        $schedule->command('command:cronDeleteDriverCommand')->monthly()->withoutOverlapping();
        $schedule->command('command:cronUpdateDriverDayOffCommand')->monthlyOn(1, '04:00')->withoutOverlapping();
        $schedule->command('command:cronChangeStatusCommand')->dailyAt('4:00')->withoutOverlapping();
        $schedule->command('command:cronAutoGetResponseResultAI')->everyMinute()->withoutOverlapping();
        // $schedule->command('create:cashOutStatistical')->dailyAt('00:00')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
