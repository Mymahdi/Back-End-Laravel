<?php

namespace App\Listeners;

use App\Events\BlogPublished;
use App\Mail\BlogNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Events\NoitfyPublishedBlog;

class SendBlogPublishedNotification
{
    /**
     * Handle the event.
     *
     * @param \App\Events\NoitfyPublishedBlog $event
     * @return void
     */
    public function handle(NoitfyPublishedBlog $event)
    {
        $blog = $event->blog;
        $users = User::where('id', '!=', $blog->user_id)->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new BlogNotification($user,$blog));
        }
    }
}
