<?php

namespace App\Providers;

use App\Jobs\ExportWeeklyBlogs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Schedule $schedule): void
    {
        $schedule->job(new ExportWeeklyBlogs())->weeklyOn(1, '02:00');
    }
}
