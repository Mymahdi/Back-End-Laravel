<?php

namespace App\Listeners;

use App\Mail\BlogNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Events\NotifyPublishedBlog;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class SendBlogPublishedNotification
{
    /**
     * Handle the event.
     *
     * @param \App\Events\NotifyPublishedBlog $event
     * @return void
     */
    public function handle(NotifyPublishedBlog $event): void
    {
        $blog = $event->blog;
        $users = User::where('id', '!=', $blog->user_id)->get();

        Log::info('NotifyPublishedBlog listener triggered', [
            'blog_id' => $blog->id,
            'blog_title' => $blog->title,
            'total_users' => $users->count(),
        ]);
        foreach ($users as $user) {
            Mail::to($user->email)->send(new BlogNotification($user,$blog));
            Notification::create([
                'link' => route('blog.show', ['id' => $blog->id]),
                'title' => $blog->title,
                'user_id' => $user->id,
                'blog_id' => $blog->id,
                'is_read' => false,
            ]);
        }
    }
}
