<?php

namespace App\Providers;

use App\Events\BlogPublished;
use App\Listeners\SendBlogPublishedNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        BlogPublished::class => [
            SendBlogPublishedNotification::class,
        ],
    ];
    /**
     */
    public function register(): void
    {
        //
    }

    /**
     */
    public function boot(): void
    {
        //
    }
}
