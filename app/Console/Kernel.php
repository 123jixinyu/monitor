<?php

namespace App\Console;

use App\Console\Commands\Mysql;
use App\Console\Commands\Sphinx;
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
        Commands\Inspire::class,
        Sphinx::class,
        Mysql::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('monitor:sphinx')->everyMinute();//监控sphinx
        $schedule->command('monitor:mysql')->everyMinute();//监控mysql
    }
}
