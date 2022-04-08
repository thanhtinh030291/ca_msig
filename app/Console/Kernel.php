<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\AuthPermissionCommand;
use App\Console\Commands\GetCPS;
use App\Console\Commands\GetHBS;
use App\Console\Commands\GetRenewPayment;
use App\Console\Commands\UpdateFile;
use App\Console\Commands\UploadMfile;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AuthPermissionCommand::class,
        GetCPS::class,
        GetHBS::class,
        GetRenewPayment::class,
        UpdateFile::class,
        UploadMfile::class,
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
        //          ->hourly();command:GetCPS
        $schedule->command('command:GetCPS')->everyMinute();
        $schedule->command('command:GetHBS')->everyMinute();
        //$schedule->command('command:GetRenewPayment')->everyMinute();
        //$schedule->command('command:UpdateFile')->everyMinute();
        $schedule->command('command:CheckFinishAndPay')->everyFiveMinutes();
        $schedule->command('command:UploadMfile')->dailyAt('22:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
