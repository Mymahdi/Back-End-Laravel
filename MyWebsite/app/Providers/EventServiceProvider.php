<?php

namespace App\Providers;

use App\Events\NotifyPublishedBlog;
use App\Listeners\SendBlogPublishedNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        NotifyPublishedBlog::class => [
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
