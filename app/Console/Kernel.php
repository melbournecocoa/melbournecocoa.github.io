<?php

namespace App\Console;

use App\Console\Commands\CocoaheadsBootstrap;
use App\Console\Commands\Generate2017Events;
use App\Console\Commands\CreateAdmin;
use App\Console\Commands\Generate2016Events;
use App\Console\Commands\LoadEvents;
use App\Console\Commands\LoadPosts;
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
        LoadPosts::class,
        LoadEvents::class,
        CreateAdmin::class,
        Generate2017Events::class,
        Generate2016Events::class,
        CocoaheadsBootstrap::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
    }
}
