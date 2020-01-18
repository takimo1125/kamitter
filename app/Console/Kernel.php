<?php
namespace App\Console;
use App\Console\Commands\AutoLike;
use App\Console\Commands\AutoTweet;
use App\Console\Commands\AutoFollow;
use App\Console\Commands\AutoUnfollow;
use App\Console\Commands\InspectActiveUser;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\InspectNotFollowback;
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
        \App\Console\Commands\AutoFollow::class,
        \App\Console\Commands\AutoUnfollow::class,
        \App\Console\Commands\AutoTweet::class,
        \App\Console\Commands\AutoLike::class,
        \App\Console\Commands\InspectActiveUser::class,
        \App\Console\Commands\InspectNotFollowback::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('auto:tweet')->everyMinute();
        $schedule->command('auto:like')->hourlyAt(50);
        $schedule->command('auto:unfollow')->hourly();
        $schedule->command('auto:follow')->hourly();
        $schedule->command('inspect:followback')->hourlyAt(10);
        $schedule->command('inspect:active')->hourlyAt(30);

        $schedule->command('auto:tweet')->everyMinute(1);
        $schedule->command('auto:like')->hourlyAt(51);
        $schedule->command('auto:unfollow')->hourly(1);
        $schedule->command('auto:follow')->hourly(1);
        $schedule->command('inspect:followback')->hourlyAt(11);
        $schedule->command('inspect:active')->hourlyAt(31);
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
