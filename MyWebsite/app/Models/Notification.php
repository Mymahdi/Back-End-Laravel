<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'link', 
        'title', 
        'user_id', 
        'blog_id', 
        'is_read'
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
