<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    public function insert($data)
    {
        DB::table('blogs')->insert([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'authorName' => $validated['authorName'],
            'user_id' => auth()->id(),
        ]);
    }
}
